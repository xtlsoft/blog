# 权限配置错误与 sshd、mysqld 无法启动的联系

% xtlsoft, 2017-05-01 10:28:07

（在迁移到新博客后有修改）

## 事例

> 今天非常奇怪，才买的服务器，ssh 就连不上了，但 Ajenti Panel 的 WebShell 正常！？
>
> 我认为重启应该会好，于是去管理面板重启服务器。没想到，不仅 ssh 连不上，博客的 MySQL 还崩溃了，直接 503！？
>
> 经过一番查找资料，终于，有了一丝线索。看见了这个：
> <http://www.cnblogs.com/no7dw/archive/2012/07/05/2577183.html>

我才记起来，之前在服务器上面执行过：

```sh
chmod 7777 / -Rf
```

嗯，这就是罪魁祸首了。

## 解决

```sh
chmod 0644 /etc/mysql/* -R
chmod 0600 /etc/ssh/* -R
service sshd restart
```

对于 CentOS:

```sh
chmod 0600 /var/empty/ssh -R
chmow root /var/empty/ssh -R
service sshd restart
```

对于云服务使用者，可以尝试使用 VNC Terminal 连接到服务器。
