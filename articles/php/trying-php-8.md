# PHP 8 初尝试：性能，新特性和方向

% xtlsoft, 2020-07-05 16:33:02

PHP 8 Alpha 1 发布已经有几天了，然而我一直咕咕咕到现在才来尝“鲜”。

[@idawnlight](https://github.com/idawnlight) 已经提供了一个现成的 Docker 镜像，并且他已经将博客运行在了 PHP 8 上。据反馈，性能提升在某些情况下并不明显，但在某些情况下非常显著。本文将对 PHP 8 的性能，新特性进行更多的探索，同时阐明社区应当持有的态度和最佳发展方向。

## 编译 PHP 8

```plain
➜  php-8.0.0alpha1 ls
CODING_STANDARDS.md  Makefile.objects    UPGRADING.INTERNALS  buildconf      configure.ac  main                 sapi
CONTRIBUTING.md      NEWS                Zend                 buildconf.bat  docs          modules              scripts
EXTENSIONS           README.REDIST.BINS  appveyor             config.log     ext           pear                 tests
LICENSE              README.md           azure                config.nice    include       php.ini-development  tmp-php.ini
Makefile             TSRM                azure-pipelines.yml  config.status  libs          php.ini-production   travis
Makefile.fragments   UPGRADING           build                configure      libtool       run-tests.php        win32
```

下载下来的源码目录大概就是这样的。

### 初次尝试

首先我们不打任何标记 `configure` 一下，顺便先把依赖问题解决掉。

```bash
$ ./configure --prefix=/home/xtlsoft/labs/php8/

......

configure: error: Package requirements (libxml-2.0 >= 2.7.6) were not met:

No package 'libxml-2.0' found

Consider adjusting the PKG_CONFIG_PATH environment variable if you
installed software in a non-standard prefix.

Alternatively, you may set the environment variables LIBXML_CFLAGS
and LIBXML_LIBS to avoid the need to call pkg-config.
See the pkg-config man page for more details.
```

### 解决依赖问题

好吧，我们还是先装依赖。对着 `docker-lemp` 的 `Dockerfile`，结合我自己的 `configure` 参数，我们可以安装如下必要工具包：（Ubuntu 18.04 LTS, WSL 2）

#### 通过包管理器的安装

（通过不断尝试，以下大概是一个最小列表，如果还有不全的，请在下方评论。）

**请注意：** 一定要确保安装完整依赖，否则你会卡在 `configure` 阶段很长时间。对于其它版本的 linux 发行版，可以从 Build Guidelines 等地方获得相关帮助。以下包名可能随着操作系统版本的变化而发生改变，请善用 `apt search` 命令进行模糊匹配，以安装正确的软件包。

```bash
sudo apt install autoconf re2c curl libedit-dev libxml2-dev libcurl4-openssl-dev libsodium-dev libsqlite3-dev argon2 minizip libgmp-dev libc-client-dev libkrb5-dev libldap2-dev libpq-dev libargon2-0-dev libzip-dev
```

推荐先使用 `ppa:ondrej/php` 源（可在 `launchpad` 找到，使用 `add-apt-repository` 即可，注意先设置 `LC_LOCAL=C`），可以避免一些找不到包的错误。

#### 通过源代码的安装

但是 `oniguruma` 这个正则库（被 `mbstring` 用到）目前 Ubuntu 源内还没有，需要编译安装。

```bash
$ curl -O https://github.com/kkos/oniguruma/releases/download/v6.9.5_rev1/onig-6.9.5-rev1.tar.gz
......

$ tar -zxf onig-6.9.5-rev1.tar.gz
$ cd onig-6.9.5

$ ./configure
......
configure: creating ./config.status
config.status: creating Makefile
config.status: creating src/Makefile
config.status: creating test/Makefile
config.status: creating sample/Makefile
config.status: creating onig-config
config.status: creating src/config.h
config.status: executing depfiles commands
config.status: executing libtool commands
config.status: executing default commands
$ make -j8
......
make[2]: Leaving directory '/home/xtlsoft/labs/php8/onig-6.9.5/src'
make[1]: Leaving directory '/home/xtlsoft/labs/php8/onig-6.9.5/src'
Making all in test
make[1]: Entering directory '/home/xtlsoft/labs/php8/onig-6.9.5/test'
make[1]: Nothing to be done for 'all'.
make[1]: Leaving directory '/home/xtlsoft/labs/php8/onig-6.9.5/test'
Making all in sample
make[1]: Entering directory '/home/xtlsoft/labs/php8/onig-6.9.5/sample'
make[1]: Nothing to be done for 'all'.
make[1]: Leaving directory '/home/xtlsoft/labs/php8/onig-6.9.5/sample'
make[1]: Entering directory '/home/xtlsoft/labs/php8/onig-6.9.5'
sed -e 's,[@]datadir[@],/usr/local/share,g' -e 's,[@]datarootdir[@],/usr/local/share,g' -e 's,[@]PACKAGE_VERSION[@],6.9.5,g' -e 's,[@]prefix[@],/usr/local,g' -e 's,[@]exec_prefix[@],/usr/local,g' -e 's,[@]libdir[@],/usr/local/lib,g' -e 's,[@]includedir[@],/usr/local/include,g' < ./oniguruma.pc.in > oniguruma.pc
make[1]: Leaving directory '/home/xtlsoft/labs/php8/onig-6.9.5'
$ sudo make install
......
---------------
Libraries have been installed in:
   /usr/local/lib

If you ever happen to want to link against installed libraries
in a given directory, LIBDIR, you must either use libtool, and
specify the full pathname of the library, or use the '-LLIBDIR'
flag during linking and do at least one of the following:
   - add LIBDIR to the 'LD_LIBRARY_PATH' environment variable
     during execution
   - add LIBDIR to the 'LD_RUN_PATH' environment variable
     during linking
   - use the '-Wl,-rpath -Wl,LIBDIR' linker flag
   - have your system administrator add LIBDIR to '/etc/ld.so.conf'

See any operating system documentation about shared libraries for
more information, such as the ld(1) and ld.so(8) manual pages.
------------------
......
```

### Configure 配置

接下来 Configure。我的配置：

（注意 `--prefix` 参数多数情况下配置为 `/` 即可。此处为了不引起环境冲突配置为另外的目录，请勿与我一致。）

```bash
# Initialize PHP INI directory
export PHP_INI_DIR=/home/xtlsoft/labs/php8/etc/
mkdir -p $PHP_INI_DIR/conf.d
# Configure commandline
./configure \
    --prefix=/home/xtlsoft/labs/php8/ \
    --with-config-file-path="$PHP_INI_DIR" \
    --with-config-file-scan-dir="$PHP_INI_DIR/conf.d" \
    --enable-option-checking=fatal \
    --with-mhash \
    --enable-ftp \
    --enable-mbstring \
    --enable-mysqlnd \
    --with-password-argon2 \
    --with-sodium=shared \
    --with-curl \
    --enable-bcmath \
    --with-zlib \
    --enable-exif \
    --with-ffi \
    --enable-gd \
    --with-gettext \
    --with-gmp \
    --enable-intl \
    --enable-mbstring \
    --with-mysqli \
    --with-imap \
    --with-kerberos \
    --with-imap-ssl \
    --with-ldap \
    --with-pdo-mysql \
    --with-pdo-pgsql \
    --enable-soap \
    --enable-sockets \
    --with-zip \
    --enable-zts \
    --with-pcre-jit \
    --with-libedit \
    --with-openssl \
    --with-zlib \
    --with-pear \
    --enable-fpm
```

终于，成功了：

```bash
Generating files
configure: patching main/php_config.h.in
configure: creating ./config.status
creating main/internal_functions.c
creating main/internal_functions_cli.c
config.status: creating main/build-defs.h
config.status: creating scripts/phpize
config.status: creating scripts/man1/phpize.1
config.status: creating scripts/php-config
config.status: creating scripts/man1/php-config.1
config.status: creating sapi/cli/php.1
config.status: creating sapi/fpm/php-fpm.conf
config.status: creating sapi/fpm/www.conf
config.status: creating sapi/fpm/init.d.php-fpm
config.status: creating sapi/fpm/php-fpm.service
config.status: creating sapi/fpm/php-fpm.8
config.status: creating sapi/fpm/status.html
config.status: creating sapi/phpdbg/phpdbg.1
config.status: creating sapi/cgi/php-cgi.1
config.status: creating ext/phar/phar.1
config.status: creating ext/phar/phar.phar.1
config.status: creating main/php_config.h
config.status: executing default commands

+--------------------------------------------------------------------+
| License:                                                           |
| This software is subject to the PHP License, available in this     |
| distribution in the file LICENSE. By continuing this installation  |
| process, you are bound by the terms of this license agreement.     |
| If you do not agree with the terms of this license, you must abort |
| the installation process at this point.                            |
+--------------------------------------------------------------------+

Thank you for using PHP.
```

### 开始编译

```bash
make -j8
```

一般不会出大问题。

```bash
Build complete.
Don't forget to run 'make test'.
```

最终：

```bash
$ make install
Installing shared extensions:     /home/xtlsoft/labs/php8/lib/php/extensions/no-debug-zts-20190128/
Installing PHP CLI binary:        /home/xtlsoft/labs/php8/bin/
Installing PHP CLI man page:      /home/xtlsoft/labs/php8/php/man/man1/
Installing PHP FPM binary:        /home/xtlsoft/labs/php8/sbin/
Installing PHP FPM defconfig:     /home/xtlsoft/labs/php8/etc/
Installing PHP FPM man page:      /home/xtlsoft/labs/php8/php/man/man8/
Installing PHP FPM status page:   /home/xtlsoft/labs/php8/php/php/fpm/
Installing phpdbg binary:         /home/xtlsoft/labs/php8/bin/
Installing phpdbg man page:       /home/xtlsoft/labs/php8/php/man/man1/
Installing PHP CGI binary:        /home/xtlsoft/labs/php8/bin/
Installing PHP CGI man page:      /home/xtlsoft/labs/php8/php/man/man1/
Installing build environment:     /home/xtlsoft/labs/php8/lib/php/build/
Installing header files:          /home/xtlsoft/labs/php8/include/php/
Installing helper programs:       /home/xtlsoft/labs/php8/bin/
  program: phpize
  program: php-config
Installing man pages:             /home/xtlsoft/labs/php8/php/man/man1/
  page: phpize.1
  page: php-config.1
Installing PEAR environment:      /home/xtlsoft/labs/php8/lib/php/
```

（Test 这种东西才不会跑呢对吧（逃））

最终看到了：

```bash
$ php -v
PHP 8.0.0alpha1 (cli) (built: Jul  5 2020 18:04:50) ( ZTS )
Copyright (c) The PHP Group
Zend Engine v4.0.0-dev, Copyright (c) Zend Technologies
```

### 编译 Opcache 等拓展

我选择 `export PATH=/home/xtlsoft/labs/php8/bin/:$PATH` 来避免一些不必要的麻烦。

#### 编译 Opache + 启动 JIT

```bash
$ cd ext/opcache
$ phpize
Configuring for:
PHP Api Version:         20190128 
Zend Module Api No:      20190128 
Zend Extension Api No:   420190128
$ ./configure
$ make -j8
$ make install
```

然后在 PHP 的 `conf.d`（我这里是 `/home/xtlsoft/php8/etc/conf.d`）内创建 `10-opcache.ini`，包含如下内容：

```ini
[Zend Opcache]
zend_extension=opcache.so
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
opcache.revalidate_freq=60
opcache.fast_shutdown=1
opcache.enable_cli=1
opcache.jit=1205
opcache.jit_buffer_size=64M
```

关于 JIT 相关配置，查看 <https://www.laruence.com/2020/06/27/5963.html> 获得更多帮助。

我们一般将 `opcache.jit` 设置为 `1205` 或 `1255` 即可。（对于 CLI Applications，选择 0 会增加 VM 启动时间，但是常驻内存会获得最佳性能。对于 fpm 应用程序，选择 5，Opcache 引擎会根据热度动态 JIT）

（说实话 PHP 将 JIT 作为 Opcache 的一个组件提供我还是有点惊讶的）

#### 编译 pcntl 等

不再赘述。

```bash
$ cd pcntl
$ phpize
Configuring for:
PHP Api Version:         20190128
Zend Module Api No:      20190128
Zend Extension Api No:   420190128
$ ./configure --prefix=/home/xtlsoft/labs/php8/
......
$ make -j8
......
$ make install
Installing shared extensions:     /home/xtlsoft/labs/php8/lib/php/extensions/no-debug-zts-20190128/
$ cd ../posix
$ phpize
Configuring for:
PHP Api Version:         20190128
Zend Module Api No:      20190128
Zend Extension Api No:   420190128
$ ./configure --prefix=/home/xtlsoft/labs/php8/
......
$ make -j8
......
$ make install
Installing shared extensions:     /home/xtlsoft/labs/php8/lib/php/extensions/no-debug-zts-20190128/
```

最后创建配置文件：`conf.d/30-pcntl.ini` 和 `conf.d/30-posix.ini`。

```ini
; conf.d/30-pcntl.ini
extension=pcntl.so
; conf.d/30-posix.ini
extension=posix.so
```

### 更新 Composer

我的老版本 Composer 在 PHP 8 会炸，所以先更新下。

切换回 PHP 7.4.7 环境，运行：

```bash
$ composer self-update
You are running composer as "root", while "/home/xtlsoft/.composer" is owned by "xtlsoft"
Updating to version 1.10.8 (stable channel).
   Downloading (100%)
Use composer self-update --rollback to return to version 1.10.1
```

然后就不会出现报错了。

## 性能的狂欢

### 一些没用的 Microbench

测试机器：

```bash
Model: ThinkPad E490
CPU: Intel Core i5-8265U @ 8x 1.8GHz
Kernel: x86_64 Linux 4.19.104
OS: Ubuntu 18.04 bionic
RAM: 8192MiB
```

测试环境：

```bash
➜  lab php -v
PHP 8.0.0alpha1 (cli) (built: Jul  5 2020 18:04:50) ( ZTS )
Copyright (c) The PHP Group
Zend Engine v4.0.0-dev, Copyright (c) Zend Technologies
    with Zend OPcache v8.0.0alpha1, Copyright (c), by Zend Technologies
    with JIT

➜  lab /usr/bin/php -v
PHP 7.4.7 (cli) (built: Jun 12 2020 07:44:05) ( NTS )
Copyright (c) The PHP Group
Zend Engine v3.4.0, Copyright (c) Zend Technologies
    with Zend OPcache v7.4.7, Copyright (c), by Zend Technologies
```

下文中 `php` 均为 PHP 8.0.0 alpha1，`/usr/bin/php` 均为 PHP 7.4.7。

#### 毫无意义的 `bench01.php`

（随手写个大循环看看）

```php
<?php

$n = 10000;

$arr = [];

while ($n --) {
        $arr[$n] = 120 + 123 - 213 * 123 / 12;
        $c = 10000;
        while ($c --) {
                $arr[$n] *= 10;
                $arr[$n] -= 9 * $arr[$n];
        }
}
```

结果：

```bash
➜  lab time /usr/bin/php bench01.php
/usr/bin/php bench01.php  3.63s user 0.02s system 99% cpu 3.650 total
➜  lab time php bench01.php
php bench01.php  1.98s user 0.01s system 99% cpu 1.993 total
```

可以看出，性能近乎翻倍。

#### 测试 `strtolower` 的 `bench02.php`

`LOCALE` 已经设置为 `C`。

代码：

```php
<?php

$str = 'A LovEly CAt iN a CaPiTALized CITY.';
$str2 = '';

$c = 15;

while ($c --) {
        $str .= $str;
}

echo 'Length of str is: ' . strlen($str) . "\r\n";

$n = 10000;

while ($n --) {
        $str2 = strtolower($str);
}
```

结果：

```bash
➜  lab time /usr/bin/php -d 'memory_limit=2G' bench02.php
Length of str is: 1146880
/usr/bin/php -d 'memory_limit=2G' bench02.php  11.49s user 0.01s system 99% cpu 11.496 total
➜  lab time php -d 'memory_limit=2G' bench02.php
Length of str is: 1146880
php -d 'memory_limit=2G' bench02.php  0.91s user 0.03s system 99% cpu 0.938 total
```

足足差了 10 倍。

#### 如果改一改 `bench02.php` 的参数

我们将 `c`（测试字符串的长度为 $len_0 \times 2^c$）修改为较小值，同时缩小 `n` 的数值。

```php
<?php

$str = 'A LovEly CAt iN a CaPiTALized CITY.';
$str2 = '';

$c = 3;

while ($c --) {
        $str .= $str;
}

echo 'Length of str is: ' . strlen($str) . "\r\n";

$n = 500;

while ($n --) {
        $str2 = strtolower($str);
}
```

结果出乎意料，但在情理之中：

```bash
➜  lab time /usr/bin/php bench02_re.php
Length of str is: 280
/usr/bin/php bench02_re.php  0.00s user 0.01s system 98% cpu 0.014 total
➜  lab time php bench02_re.php
Length of str is: 280
php bench02_re.php  0.02s user 0.00s system 99% cpu 0.022 total
```

这一轮 PHP 7.4.7 几乎比 PHP 8.0.0 快了一倍。

不难猜测，这种速度上的差别是由于 PHP 8.0.0 的 VM 启动耗时增加造成的。在 `1205` 模式的 JIT 下，每次使用 CLI 启动一个 PHP 程序（在没有配置全局 Opcache 的情况下），VM 需要进行“预”编译。这个时间是非常可观的（毕竟需要启动一个编译器），特别是在项目文件特别巨大的情况下。

显然，在先进的 JIT 处理器中，这可以通过 Hotspot 等方式绕过。在 PHP 中，则需要将 `1205` 切换到 `1255` 模式。但是在常见的 PHP CLI 性能有关应用程序（一般的 CLI Utility 对于 VM 启动时间方面的要求其实并不是很苛刻，对于用户来说 100ms 的延时是 tolerable 的），例如常驻内存的 WebServer 中，的确需要对所有代码进行预编译来获得最佳性能。这个时候就需要我们按需选择。当然待推广后各大库可以通过增加 `<<jit>>` Attribute 的方式来标记，这时就无需全局 JIT 了。当然，如果 PHP 团队能够实现更加智能的 Hotspot 算法，那将是最好的解决办法，但是其中的开发成本可能是 PHP 社区一时无法承受的。

接下来我将会用更多的测试来展示这一性能损耗的具体情况及避免方式/调优方法。
