# 为项目更新过时的 composer 依赖

% xtlsoft, 2019-07-31, 14:05:26

## 由来

发现 `commonmark-ext-autolink` 会对于 `<https://link-to-some-site.tld>` 这种格式的链接解析两次，双重嵌套 `<a>` 标签。

我刚刚准备提 `issue` ，就发现这个 bug 在 `version 0.3.1` 或 `1.x` 中已经被解决了。回来更新吧！

配置中手动更新版本，`composer update`！

`Dependency resolve failed`

emmm…… 我明明改了其它依赖都是版本 `*` 啊！

## 解决

解决问题很粗暴：

```sh
rm ./composer.lock -f
rm ./vendor -rf
composer install
```

结束。

## 后记

文章真好水。

