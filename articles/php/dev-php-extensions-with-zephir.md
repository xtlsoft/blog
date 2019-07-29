# 使用 Zephir 开发 PHP 拓展

% xtlsoft, 2019-07-26 22:24:31

Zephir 真是一个极佳的开发 PHP 拓展的语言，并且还有几个坑。

> 这不是我的风格吗（
>
> ——黎明余光

好不开玩笑，步入正题。

## 为何

初见 Zephir 是某个大佬 star 了它，然后我才后知后觉地发现有这么个东西。其实去年就想写写关于它的文章（因为 Zephir 的中文资料少得可怜，中文文档还是谷歌机翻…），不过种种原因搁置了。

由于<del>Zend Engine 的 C API 太难用了</del>我的 C 水平太菜了，并且<del>反手就是一个内存泄漏</del>不会好好管理内存，所以这种方便又<del>坑多</del>稳定的东西自然是个好选择。不过它真的能大幅度降低开发周期。

多说一句，大名鼎鼎的 Phalcon 框架从 2.x 开始就是用 Zephir 写的了。

## 安装

<del>如果去年写了这篇文章，那么安装还是个坑，那时候还没自动打包版，composer 装的依赖有坑。</del>

然而今年它着实在安装时还是附带了一个坑。

首先是安装 `Zephir Parser` 拓展，比较简单，直接 `phpize` 后 `./configure && make && sudo make install` 即可。但是我还是碰到了一些锅，装 `php-dev` 时出了问题，详见上一篇文章。

然后就是本体了。`Parser` 是 C 写的，然而编译器本体是 PHP 写的。有三种方式，phar 包，composer 全局安装，composer 局部安装。我自然是  `composer g install phalcon/zephir` 了。然而，依赖冲突！？我仔细看看，它居然 require 了一堆 3.4.* 的 Symfony 组件，而我本地的 psysh 都要求的是 4.x 。我并不想搞坏我这些全局包的依赖环境，所以选择了直接 phar 包全局。（然而到最后证明还是局部 composer 最棒，因为可以随便 hook 编译器，实现一些骚操作，比如 <https://github.com/xtlsoft/zephir-c-call>）

## Hello, World

这是必不可少的。

```sh
zephir init helloworld
cd helloworld
echo "namespace Helloworld; class Hello { public static function say() { echo \"Hello!\"; } }" > helloworld/hello.zep
zephir build
```

然后将 `extension = helloworld.so` 加入 `php.ini` 中，就可以通过如下调用看到效果了：

```php
<?php
\Helloworld\Hello::say();
```

结果输出：

```plain
Hello!
```

