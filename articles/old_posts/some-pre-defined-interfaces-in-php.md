# PHP 的一些内置接口和魔术方法

% xtlsoft, 2017-11-19 14:41:56

## 由来

写项目时，可能会为了美观，用到一些类似这些的接口。

## 魔术方法

`__get`：获取一个不存在的值
`__set`：设置一个不存在的值
`__unlink`：删除一个值
`__call`：调用一个不存在的方法
`__toString`：把类当作字符串
`__callStatic`：静态调用

## 接口

Countable：可以在类上使用 count()
ArrayAccess：数组式访问
Iterator：可以使用 foreach
