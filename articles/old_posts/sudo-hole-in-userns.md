# sudo 在 userns 的坑

% xtlsoft, 2018-08-10 18:39:49

## 由来

包：github.com/xtlsoft/container

由于 Golang 将 Setsuid 等 Syscall 给去除了，所以目前我想到用 sudo 模拟。

（没有库？Shell 来凑～）

## 问题

一代码：

```go
package main

import (
    "os"

    "github.com/xtlsoft/container"
)

func main() {

    cmd := container.NewNS().
        ApplyUTS().
        ApplyPID().
        ApplyIPC().
        ApplyNet().
        ApplyMount().
        ApplyUser().
        Command("sudo", "-u", "nobody", "ls")

    cmd.Stdout = os.Stdout
    cmd.Stderr = os.Stderr

    cmd.Start()

    cmd.Wait()

}
```

乍一看没啥，但是执行得到：

```plain
sudo: PERM_SUDOERS: setresuid(-1, 1, -1): 无效的参数
sudo: 没有找到有效的 sudoers 资源，退出
sudo: 无法初始化策略插件
```

WTF???

## 查找资料

恩，非常棒，Google 和 Bing 搜索都是 0 收录，StackOverflow 搜索也没人问这种问题。

那么只有自己解决。

## 解决方案

恩，sudo 不能在 userns 内直接用。

最后我的解决方案是：
去掉

```go
    ApplyUser().
```

这一行。
