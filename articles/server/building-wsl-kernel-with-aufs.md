# 编译带有 AUFS 支持的 WSL 内核

% xtlsoft, 2020-07-24 09:13:22

## 由来

切换到 WSL 2 的目的是做容器开发。然而，WSL 2 Kernel 并没有编译进（实际上接近废弃）的 AUFS，但是我的部分需求不能被 OverlayFS 满足，仍然需要使用 AUFS。

显然我不能通过 `apt-get` 直接安装一个内核。

## 更改内核

从网上查找更换 WSL 内核的方法，一共找到了两种：

1. 直接替换 `C:\System32\lxss\tools\kernel` 文件
2. 在用户目录下新建 `.wslconfig` 文件，写入：

   ```ini
   [wsl2]
   kernel=KERNEL_PATH
   ```

   将 `KERNEL_PATH` 替换为内核路径。

## 编译一个内核

既然要更换内核，那么我们首先就要有一个内核。

### Pre-built Version

这里我编译好了一个，可以在 <https://github.com/xtlsoft/WSL2-Kernel-AUFS/releases/tag/wsl-aufs> 下载，记得点个 star 呀。 :-D

### Let's do it our own

首先 Clone 下 WSL-Kernel 和 AUFS-Standalone 的代码库。

```bash
git clone https://github.com/microsoft/WSL2-Linux-Kernel kernel
git clone https://github.com/sfjro/aufs4-standalone aufs4
```

这时请注意您所 Clone 的 Linux 内核版本。在我写这篇文章时，它是 4.19.84，所以我们应选择 `aufs4.19.63+`。

```bash
cd aufs4
git checkout aufs4.19.63+
```

然后退回到 `kernel` 目录下，开始打 Patch。

```bash
cat ../aufs4/aufs4-mmap.patch | patch -p1
cat ../aufs4/aufs4-base.patch | patch -p1
cat ../aufs4/aufs4-kbuild.patch | patch -p1
```

三个 Patch 的顺序无关。

这时可不能直接开始编译！仔细阅读 AUFS 文档，还有事情没有干！

```bash
cp ../aufs4/Documentation . -r
cp ../aufs4/fs/ . -r
cp ../aufs4-standalone/include/uapi/linux/aufs_type.h ./include/uapi/linux
```

接下来我们来修改一下编译配置，在 `Microsoft/config-wsl` 中任意位置增加一行：

```ini
CONFIG_AUFS_FS=y
```

最后，就可以开始编译了！

```bash
make KCONFIG_CONFIG=Microsoft/config-wsl -j8
```

过程中会问你一些问题，我除了 AUFS Debug 都选了 y。

最后会在当前目录生成 `vmlinuz`，在 `arch/x86/boot` 下生成 `bzImage`。

Enjoy!
