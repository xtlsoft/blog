# 你所不知的 PHP - 请别无脑黑了

% xtlsoft, 2017-07-27 08:55:27

## 前言

在中国，很多企业十分保守，Python 和 NodeJS 都不敢用，而又因为没有对于 PHP 的深入了解，选择了 Java。
因为大家对于 PHP 了解误区较深，特此写这篇文章。

### PHP 成熟性

PHP 的出生确实比较低贱，只是一个统计访问量的小程序。经历无数版本的迭代，目前 PHP7.2-Alpha 和 JIT 分支都已经出世，PHP7.1 已经是 stable 了，目前没有发现任何漏洞。
并且，PHP 的安全性接口也很多，可以自己重写安全模块。

### PHP 没有 Module?

然而，你忘记了 PH 可以使用 C/C++编写拓展，而且类似 python 的“import from”，PHP 的“use as”也非常好用。
原生的 pear 和 pecl 你用不惯的话，composer 是一个好选择。非常类似 npm，但是略有差别。

### PHP 不支持异步？

这应该是大多数反对 PHP 的人的观点。
实话说，PHP 原生实现异步，稍有困难，不过通过 pcntl 自己写一个进程管理，也不是不可能。
现在，有了一个流行的拓展，叫做 Swoole，内部不仅实现 PHP 进程管理，还实现了封装好的 MySQL,PgSQL,Redis 等异步客户端，异步 IO，类似 NodeJS 的非阻塞 Web 服务器等。
如果你只需要异步 Socket/WebSocket/Http 服务器，或需要异步 Mysql/PgSQL/Redis 客户端，却不想安装拓展，那你可以试试 ReactPHP 和 Workerman。

### PHP 混乱的函数名

我承认，这是一个缺点。但相对 Java 一大堆类名，也差不到哪里去。并且 PHP 官方的文档还是很好用的，很通俗，相对 Java，要实现什么功能，只要用 http://php.net/ 的搜索框就可以了。
而且，有什么不想自己写的模块，可以到以下地方搜索：

> http://pecl.php.net/ > http://pear.php.net/ > http://packagist.org/

### PHP 只能用于 Web

这是一个最大的误区，这么说的人请看看 PHP-CLI，PHP-GTK 和 PHP 的 UI 扩展。

### PHP 语法太宽松

。。。
这一点，我只能：呵呵。
你嫌它宽松，你可以写得严谨一点啊！

### PHP 不能重载类，不能修改异常处理，不能……

你其实完全可以自己写一个扩展实现的哦！
问这个问题，就像问，为什么 Python 不能用指针？

> 整理时补充：别打我！
