# OpenNebula 折腾小记 - 我不是我

% xtlsoft, 2017-10-14 21:37:34

## 由来

由于学校的一台闲置服务器想要装一个私有云平台，于是由于配置较低，选都不能选用了 OpenNebula。

## 安装

由于使用 lxd 作为虚拟化引擎，所以使用 LXDoNe 插件。
遇到的坑太多了。

### 1. 添加 host 后状态一直是 Error

这是个巨坑，原因是 LXDoNe 只支持 OpenNebula 5.2 和 5.3， 不支持最新的 5.4。
解决： 重新安装 OpenNebula。

### 2. 重新安装 OpenNebula 后，还是 5.4 版

需要进行数据库降级，由于过于繁琐，需要手工操作，而且没什么数据，所以直接删库。注意：apt remove 并不能拆卸干净，需要手工删除相关目录。而且需要重新初始化 lxd，否则会出现莫名其妙的错误。

### 3. host 状态还是 error

需要：

```sh
usermod oneadmin -G lxd
su oneadmin
lxc list
```

由此赋予 oneadmin 账户管理 lxd 的权限。

### 4. 创建实例时，失败，log 中有“Permission”之类的

需要：

```sh
chown 7777 /usr/lib/opennebula/ -Rf
service opennebula restart
```

### 5. 使用官方提供的镜像，无法登陆

官方的镜像问题一大堆，需要根据 LXDoNe 项目中的`Image.md`文件中的指引操作，创建一个自己的镜像。

## 其余问题

不支持热拔插，磁盘大小调整失败，cluster 失效，莫名其妙崩溃，虚拟网络经常重连等等。

## 结果

催生了 CloudSky 项目，<https://github.com/ourCloudSky/CloudSky/>
