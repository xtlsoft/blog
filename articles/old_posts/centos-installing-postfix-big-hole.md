# CentOS 安装 postfix 的坑

% xtlsoft, 2017-08-06 12:52:18

## 由来

我想要在我的 BudgetVM 服务器上搭建一个企业邮件服务器。

## 问题症状

在 `service postfix start` 时，总是不行。
使用 `systemctl status postfix.service -l`，查看日志，发现：

```plain
Aug 06 12:42:58 XTLSOFT postfix/master[28061]: fatal: open lock file /var/lib/postfix/master.lock: cannot open file: Permission denied
```

## 解决

尝试 `chmod 7777 /var/lib/postfix && rm /var/lib/postfix/master.lock -f` 失败。
原讨论地址： <https://www.linuxquestions.org/questions/linux-server-73/open-lock-file-var-lib-postfix-master-lock-cannot-open-file-permission-denied-700674/>

其实只需要：`chmod 755 /`
然后就好了。

## 小结

真是诡异的解决办法。。。
