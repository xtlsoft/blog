# WSL 2 在 Windows 10 Ver 2004 的一些 Bug

% xtlsoft, 2020-05-31 12:37:02

更新了 Microsoft Windows 10 Version 2004，期待已久的 WSL 2 着实让我失望了一回。

## Migration

从 WSL 1 迁移到 WSL 2 并不是非常 painless，尽管提供了一键转换脚本。

刚刚更新的系统是无法直接使用 WSL 2 的，需要前往 <https://aka.ms/wsl2kernel> 下载 WSL 2 内核更新组件并手动安装。当然这不是个事儿。

接下来就是将原来 WSL 1 的容器升级至 WSL 2。

```bash
$ wsl.exe --list --verbose
  NAME      STATE           VERSION
* Ubuntu    Running         1
```

于是我们

```bash
$ wsl.exe --set-version Ubuntu 2
Please wait until...
```

于是开始了无尽的等待。尽管提示只需要几分钟，可是在我配备 SSD 的机器上，整个过程花费了大约两个小时。

## Not Responding

如果更新中途通过其他终端唤起 `wsl.exe`，会导致卡死。强制结束 WSL 相关进程和服务，重新启动 Hyper-V 服务等都没有效果，此时唤起 `wsl.exe` 无论添加任何参数都会处于假死状态。

解决方法：关闭快速启动，进行深度重启，多次重启后可以通过：

```bash
$ wsl.exe --list --verbose
  NAME      STATE           VERSION
* Ubuntu    Stopped         1
```

来判断正常与否。

## Memory Leak

在迁移途中，我发现 `vmmem` 占用了 `4.16 GiB / 8.00 GiB` 的内存。

经过查找，GitHub 上有 issue 反映了这个问题，是由于 Linux 的内存缓存机制导致虚假分配了过多的内存给 WSL。

微软目前正在寻找该问题的解决方案，对于我们来说，可以通过一个 `crontab` 来定期释放内存：

<https://github.com/microsoft/WSL/issues/4166#issuecomment-526725261>

这里给出了详细的解决方案。

## Serial Devices

我的 esp32 开发工作流原本位于 WSL 下，但是由于使用 Hypervisor Platform 的 WSL 2 没有提供任何 interact 的 api, 目前只有三个选择：

1. 将工具链移到 Windows Host OS 下
2. 直接使用 Hypervisor 启动虚拟机，并分配串口设备，使用 Linux 的块设备流特性等转发块设备
3. 探索 Hyper-V 的隐藏 API

在更新之前需要谨慎考虑。

## Network Issues

这次 WSL 相当于一个虚拟机，不能共用 Network 了。特别是使用宽带连接时，不可以直接共享网络给子系统（这是 Hyper-V 的通病）。针对这种情况，解决方案只有开启透明端口转发等。由于解决方案比较通用，不再赘述。
