# Webpack 开发服务器 更换服务端口/IP

% xtlsoft, 2017-07-25 11:33:08

## 由来

想着，[iViewUI](http://www.iviewui.com "iViewUI")这么好看，可真是要试一试。
原来直接用 CDN 引用，总是不能加载所有样式。
现在，想试试用 NodeJS 和 Webpack 以及 CLI Tool 来试试。（于是我就上菜鸟教程花半个小时学了一下 NodeJS 和 Webpack，事实上发现不必要）

## 需求

我是在 VBox 下的 Ubuntu 16.04 LTS 下面尝试的，用的 NAT 映射，把在 8888 端口的 KodExplorer 映射出来写代码。
然而，`npm run dev`命令监听的是 `127.0.0.1:8888`，VBox 的 NAT 映射，只可以映射监听 0.0.0.0（全 IP）的服务。
想着，改源码！

## 实现

查找别人的博客，发现了有人说`dev-server.js`是监听端口的程序。
那么，强制改掉里面监听的段，就可以了！
使用 KodExplorer 的全文循环搜索功能，发现工作目录内一共有 3 个类似的文件：

- /node_modules/webpack/hot/dev-server.js
- /node_modules/webpack/hot/only-dev-server.js
- /node_modules/webpack/hot/webpack-dev-server.js

先看别人播客里面提到的`dev-server.js`，然而，里面连个`localhost`或`127.0.0.1`都没有，排除。
`only-dev-server.js`也不行。
而`webpack-dev-server.js`才是我们要改的文件。
我们只需要在 366 行

```javascript
addDevServerEntrypoints(wpOpt, options);
```

后添加 3 行：

```javascript
/*@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/
options.host = "0.0.0.0";
/*@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/
```

（这么多`@`是为了标识）
就可以了（`0.0.0.0`你也可以改为其他 IP）。

## 其他

博客换上 Editor.md 了！比 fckeditor 好用不知多少倍:joy:！
