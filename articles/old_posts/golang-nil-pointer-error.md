# Golang "invalid memory address or nil pointer dereference" 正解

% xtlsoft, 2018-06-14 21:29:31

## 出现

在写 Go-Router 的时候，SimpleHttpServer 中要一个 Request 对象的指针传递，但是设置 `(*Request).RouterVariable` 的时候报了这个错误。

## 查找资料

网上我找到的均没有正确地解释这个错误。

## 正解

其实这个 `nil pointer` 和 `nil map` 差不多，就是一个没有初始化的指针，实际上没有指向任何内存地址，所以会报错。

### 错误例子

```go
var a *Test
a.TestValue = 123
// panic: runtime error: invalid memory address or nil pointer dereference
```

### 正确例子

```go
var a = &Test{}
a.TestValue = 123
// 123
```
