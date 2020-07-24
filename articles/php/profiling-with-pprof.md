# 使用 pprof 调优 PHP 应用程序

% xtlsoft, 2019-07-31 14:05:26

本文中，我将分享使用 `apd` 及 `pprof` 分析 PHP 应用程式性能，及使用 `gperftools` 分析 PHP 本身性能的方法。

## 安装相应工具

### pprof

pprof 使用 Golang 写就，可以直接使用 `go get` 安装。

```bash
$ go version
go version go1.14.2 linux/amd64
$ go get github.com/google/pprof
$ ~/go/bin/pprof
usage:

Produce output in the specified format.

   pprof <format> [options] [binary] <source> ...

Omit the format to get an interactive shell whose commands can be used
to generate various views of a profile

   pprof [options] [binary] <source> ...

Omit the format and provide the "-http" flag to get an interactive web
interface at the specified host:port that can be used to navigate through
various views of a profile.

   pprof -http [host]:[port] [options] [binary] <source> ...

Details:
......
```

即可看到，安装成功了。

同时安装依赖 `graphviz`：

```bash
sudo apt install -y graphviz
```

### gperftools

```bash
$ wget https://github.com/gperftools/gperftools/releases/download/gperftools-2.7/gperftools-2.7.tar.gz
[======================>] 100%
$ tar xvf gperftools-2.7.tar.gz
$ cd gperftools-2.7
$ ./configure
......
$ make -j8
......
$ sudo make install
......
```

> WIP, 我太菜了！
