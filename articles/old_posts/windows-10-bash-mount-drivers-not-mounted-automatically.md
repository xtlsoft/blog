# Windows 10 第二子系统挂载未自动挂载的盘符

% xtlsoft, 2018-07-18 12:49:57

## 由来

在开发中，`Bash on Ubuntu on Windows 10` 是必不可少的神器，~~不然我用 Windows 有什么意思~~。

但是，每次我们添加了新的驱动器，插入了新的可移动存储器，`Bash on Ubuntu on Windows 10` 都不会帮我们自动识别，并不会挂载。

## 搜索资料

> 网上的解决方案是：Stop using stupid Bash on Windows 10, Start using CygWin.

但是又有一个问题，`cygwin` 并不能直接运行各种 Linux 下的 `elf`，并且内核不独立，软件安装没有包管理等等。

## 探索

实际上还是比较好理解的，既然 C 盘在 `/mnt/c`，那么我们就来 `mount | grep "/mnt/c"` 一下。

结果发现：

```text
C: on /mnt/c type drvfs (rw,noatime,uid=1000,gid=1000)
```

那么我们可以发现，这个 mount 的设备名称比较特殊，不是 `/dev/sdxn` 的格式，而是直接一个 `C:`。并且 `type` 是 `drvfs`。

## 解决

很简单，要 Mount 下 D 盘：

```sh
mkdir /mnt/d
sudo mount -t drvfs D: /mnt/d
```

要 mount 其他盘符，改一下 `d` 和 `D:` 就行。
