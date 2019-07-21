# Golang 【伪】可选参数（Optional Parameters）

% xtlsoft, 2018-06-30 13:28:16

## 由来

> TL;DR 可以直接看最下面“我的解决方案”。

前几天，在 @gosrc （果子）的 Golang 群里，谈到可选参数的事情，这里我就来详细说明一下。

## 官方的支持

### 错误写法

Golang 官方并没有对可选参数提供任何支持，所以目前我们大家在其他语言中习惯的这种写法是错误的：

```go
func TestFuncWithOptionalParameters(arg1 string, arg2 int = true) {
    // Do something...
}
```

嗯，100% 无法编译。
另外，这种写法也是错误的：

```go
func TestFuncWithAnotherOptionalParameter(arg1 string, [arg2 int]) {
    // Do something...
}
```

嗯，仍然无法编译。

### 官方的说明

官方的文章中表示，可选参数不是十分有用，所以今后都不会支持。

## 常见的解决办法

### 不使用可选参数

这个我想我就不用多说了。
例如使用 `nil` ，最著名的算是 `net/http` 中 `ListenAndServe` 方法了。

```go
import "net/http"
func Serve() {
    // ":8080" 是端口号，而 nil 是 Handler 参数，为 nil 代表使用 http.Handle() 或 http.HandleFunc() 注册过的 Handler。
    http.ListenAndServe(":8080", nil)
}
```

### 常用解决方案

> 该方案常见于各大开源项目，但是不能实际上很好地解决问题，因为调用时依然不能像 Javascript，PHP，Python 这类语言一样直接忽略。

定义一个 Struct ，叫 XXXArguments 或 XXXParameters 之类的。
然后里面写明白参数列表。

```go
type ExampleFuncParameters struct {
    Arg1 string
    Arg2 bool `optional` // `optional` 仅是为了演示时方便识别，实际上没用。
}
```

函数可以这么写：

```go
func ExampleFunc(args *ExampleFuncParameters) {
    if !args.Arg2 {
        args.Arg2 = false
    }
    // Do something...
}
```

但是调用比较麻烦：

```go
ExampleFunc(&ExampleFuncParameters{
    Arg1: "Example",
    Arg2: true,
})
```

## 我的解决方案

这是我某次写项目时想到的。
主要用了 Golang 的不定参数用法，比较完美。

依然直接上例子：

```go
func MyMagicFunc(arg1 string, arg2a ...int) {
    if len(arg2a) == 1 {
        arg2 := arg2a[0]
    }else{
        arg2 := 0
    }
    // Do something
}
```

这样不用定义 Struct ，且调用方便：

```go
MyMagicFunc("aaa") // OK
MyMagicFunc("bbb", 2) // Also OK
```

就到这。

## 版权申明

顺便说一声，本博客除标明转载的部分，均为原创。若转载一般也是直接外链，除非他站无法正常访问。

转载可以，不过请写明：

> Written By Tianle Xu <xtl@xtlsoft.top> <https://blog.xtlsoft.top/>
