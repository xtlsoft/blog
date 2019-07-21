# 使用 Gogs 搭建一台 Git 服务器

% xtlsoft, 2017-08-19 10:34:53

## 由来

Github 太慢，无意间看到 Gogs，就像搞个镜像。
没想到这么好用。

## 公共 Git 服务

我搭建了一个公共 Git 服务：
<https://git.xapps.top/>

（整理时补充：已不可使用）

## 遇到的坑

在重启服务器（不管是 Nginx 还是 Gogs）之后，已经登陆的用户再次访问会出现`invalid csrf token`错误。
解决：关闭 Nginx 的缓存，清除浏览器 cookies。

## 安装过程

安装过程已经制作成视频：
<https://www.bilibili.com/video/av13541685/>

<embed height="415" width="544" quality="high" allowfullscreen="true" type="application/x-shockwave-flash" src="//static.hdslb.com/miniloader.swf" flashvars="aid=13541685&page=1" pluginspage="//www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash"></embed>
