# Virtualmin Nginx 新建网站报错 "strict refs" in use

% xtlsoft, 2019-08-01 14:37:34

## 问题症状

```plain
Error - Perl execution failed

Can't use string ("/home/www/website") as an ARRAY ref while "strict refs" in use at virtualmin-nginx-lib.pl line 247.
```

## 问题分析

既然是 `"strict refs" in use` 导致的，那我们就关闭就可以了。

## 困难

查找 `virtualmin-nginx-lib.pl` 的位置。

这里我们使用 `fzf` 工具。

## 解决

很简单，将 `/usr/share/webmin/virtualmin-nginx/virtualmin-nginx-lib.pl` 文件中第三行

```perl
use strict;
```

注释起来，变成：

```perl
#use strict;
```

然后也不需要 `reload` ，直接重新尝试下即可。

## 写在后面

这个问题老早就有人反映了，到现在还没修好……