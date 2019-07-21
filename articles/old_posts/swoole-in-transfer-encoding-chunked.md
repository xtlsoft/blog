# Swoole 在 Transfer-Encoding: chunked 的某些锅

% xtlsoft, 2018-07-31 08:32:28

## 由来

黎明的一张图片：
![picture](https://blog.xtlsoft.top/usr/uploads/2018/07/2495980785.png)

> 在只输出 "test" 的情况下，用 easy-swoole 有 40ms 的下载时间...
>
> 但是在比较大的返回数据上，就不会有这个 40ms...

## 分析

从 Swoole 的 log 来看，是这样的：

```plain
[2018-07-30 18:56:49 *50278.1]  TRACE   [Worker] send: sendn=173|type=0|content=<<EOF
HTTP/1.1 200 OK
Server: swoole-http-server
Content-Type: text/html
Connection: keep-alive
Date: Mon, 30 Jul 2018 10:56:48 GMT
Transfer-Encoding: chunked


EOF
[2018-07-30 18:56:49 *50278.1]  TRACE   [Worker] send: sendn=21|type=0|content=<<EOF
4
test

EOF
[2018-07-30 18:56:49 *50278.1]  TRACE   [Worker] send: sendn=17|type=0|content=<<EOF
0


EOF
```

## Transfer-Encoding: chunked

恩，找到蛛丝马迹。

参考：<https://blog.csdn.net/whatday/article/details/7571451>

> Transfer-Encoding: chunked 表示输出的内容长度不能确定，普通的静态页面、图片之类的基本上都用不到这个。

此时我注意到没有 `Content-Length` 头。

而且后面有多重返回。

## 猜测

会不会是中途从第一个 4 字节的包到第二个 0 字节的包，这样会造成长时间的延迟。

## 解决

原来，不能

```php
$server->send("sth");
$server->end("");
```

而应该直接：

```php
$server->end("");
```

但是，EasySwoole 的实现是前者，并且修改不方便。
具体修改办法参考黎明余光的博客。

> <https://blog.lim-light.com/archives/easy-swoole-in-transfer-encoding-chunked.html>
