# NoiLab - 一个尽情编写 OI 程序的平台

% xtlsoft, 2017-12-29 18:49:30

## 概述

1. 项目开发者：Tianle Xu xtl@xtlsoft.top （我）
2. 项目隶属：SZCK（南通市通州区实验中学创客工作室）
3. GitHub: <https://github.com/SZCK/NoiLab/>
4. 公共地址：<http://noilab.xapps.top:23480/>

## 介绍

这是一个尽情编写 OI 程序的平台，直接编写 C++代码，然后保存并运行，实时出结果，而且支持标准的 stdin 输入，直接在右下角命令行里面输入就行。
无需安装什么，无需配置什么，有网络就可以用，手机上也可以编写（虽然体验不佳），可以直接在 NoiLinux 上搭建，使用 Workerman 构建。
每次编写的代码会保存在服务器上，下次通过生成的 uri 可以继续修改或分享。

## 切记

一定要先按保存然后才能运行！否则 100%出错！
例如：![例子](https://blog.xtlsoft.top/usr/uploads/2017/12/2056374266.png)

## 安装部署

很方便，仅需几步：

```bash
$ sudo apt install php php-cli composer php-pcntl php-posix nano
$ git clone https://github.com/SZCK/NoiLab master
$ cd NoiLab && composer install
$ nano ./Config.json
（修改配置，然后 Ctrl+X 再 Enter 退出 nano）
$ php ./start.php start -d
```

## 技术实现

前端方面，代码编辑器使用 ace。由于 ace 较庞大，GitHub 较慢，所以可以使用 Git@XApps 提供的镜像。终端使用 XTerm.js。
后端方面，使用 Workerman。

## 后记

在公共平台上，访问 `http://noilab.5ijpy.com:23480/bash` 可以看到终端。

> 整理时添加：公共平台都已经失效。
