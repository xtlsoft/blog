# Ubuntu 18.04 中 php-dev 与 nodejs 冲突（libssl）解决方式

% xtlsoft, 2019-07-24 15:01:02

## 由来

在 Windows Subsystem Linux (WSL) 上执行 PHP 拓展开发工作。例行安装 `php-dev` 包。

提示我将拆卸 `npm`, `nodejs-dev`, `nodejs-gyp`, `nodejs`？

## 检查

翻查各种 apt 的输出，发现是 `php-dev` 依赖 `libssl-dev`，而 `nodejs-dev` 依赖 `libssl1.0-dev`，而这两个包实际上内容差不多（甚至完全一样），然后 Conflict 了。

怎么办呢？

正当我准备重新打 fake 包 `libssl-dev` 来解决这个问题的时候，我搜索到了一些东西。

## 搜索

发现并不是我第一次遇到这样的问题。我在 `deepin` 的论坛中查找到了类似问题。（好像是 `deb` 系的都存在过这样的问题？）

附上链接：<https://bbs.deepin.org/forum.php?mod=viewthread&tid=145483> （挺老了）

@jingle 叫重新 build `wiznote` 包（相当于此处的 `nodejs-dev` 包），但是我会选择打一个空包 `libssl-dev` 给 `php-dev` 吃。（之前也有用过这样的伎俩，详见装`ajenti`的那篇很老很老的文章）

好，接下来一位同学提示直接修改 `/var/lib/dpkg/status`。

## 解决

没啥悬念

```sh
sudo vim /var/lib/dpkg/status
# ------> (vim console) /nodejs-dev/
#   ----> (Press 'N' key)
#     --> (jumped to  `nodejs-dev` segment)
#       > (delete `libssl1.0-dev (>=xxxxx)` dependency (xxxxx can be the version))
sudo apt install -y php-dev
```

## 总结

希望软件源快更新 `php-dev` 的 `dependency`。
