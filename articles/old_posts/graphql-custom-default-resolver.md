# GraphQL-php 中自定义 defaultResolver 的坑

% xtlsoft, 2018-07-13 21:53:57

## 由来

在开发 Waddle 框架时，被 @idawnlight 催促修复 bug。。。

## 问题

居然不能 return 一个 object ？？

## 原因

其实是因为 GraphQL-php 拿到返回值后会递归调用 defaultResolver ，取得每个 field 的最终值。

## 解决方案

在 defaultResolver 中，不能忽略 rootValue ，而要判断：

```php
if ($rootValue != null) return $rootValue->{$fieldName};
```

更加详细的可以参考 Waddle 的实现。
