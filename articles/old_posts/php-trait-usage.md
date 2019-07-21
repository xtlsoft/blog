# PHP trait 用法详解

% xtlsoft, 2017-10-14 21:46:57

## 介绍

PHP 的类只能继承一个父类，要实现进一步的代码复用，需要使用 trait 实现。

> Trait 和 Class 相似，但仅仅旨在用细粒度和一致的方式来组合功能。 无法通过 trait 自身来实例化。它为传统继承增加了水平特性的组合；也就是说，应用的几个 Class 之间不需要继承。

## 使用

php.net 官方实例：

```php
<?php
trait ezcReflectionReturnInfo {
    function getReturnType() { /*1*/ }
    function getReturnDescription() { /*2*/ }
}

class ezcReflectionMethod extends ReflectionMethod {
    use ezcReflectionReturnInfo;
    /* ... */
}

class ezcReflectionFunction extends ReflectionFunction {
    use ezcReflectionReturnInfo;
    /* ... */
}
?>
```

我的例子：

```php
<?php
trait exampleTrait {
    function foo() {
        return "bar";
    }
}
class exampleBase {
    function ping(){
        return "pong";
    }
}
class example extends exampleBase {

    use exampleTrait;

    function runAll(){
        echo $this->foo() . PHP_EOL;
        echo $this->ping() . PHP_EOL;
    }

}
```

在类中 use 的 trait 的函数都会被定义到该类内。

> 整理时添加：然而， php 原生还是不支持模板/泛型。
