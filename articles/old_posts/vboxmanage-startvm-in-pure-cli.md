# VirtualBox 使用 VBoxManage 在纯命令行启动虚拟机

% xtlsoft, 2018-10-05 13:27:59

## 由来

一台只有 ssh 的服务器，需要在上面开虚拟机，并且不想每次都把 vnc 映射出来用 vnc 操作 VirtualBox 图形界面。

## 查找资料

`VBoxManage` 不就是我要的东西吗！果断：

```bash
sudo apt install VBoxManage
```

## 查看 help

运行 `VBoxManage` ，列出了一堆命令。里面有一个正是我们需要的：
`VBoxManage startvm {uuid/name}`
于是，我尝试：`VBoxManage startvm "Windows Server 2016"`
结果出错。

## 搜索 issue

查找官方 issue，竟然是因为没启动图形界面造成的？？？

```bash
vnc4server -geometry 1366x768
export SCREEN=:1
```

没用系列。

## 正解

我们需要使用 `无头模式`，就可以直接从命令行启动虚拟机：

```bash
VBoxManage startvm "Windows Server 2016" --type headless
```
