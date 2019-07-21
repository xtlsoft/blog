# CachedRecursion - 用普通的方式写缓存的递归

% xtlsoft, 2017-09-23 13:47:28

## 由来

非常偶然的，在写 NOIP 的程序时，诞生了这个想法。
但是，当然不是 C++ 版啦！这是个 php 类库。

## 安装

```sh
composer require xtlsoft/cachedrecursion
```

## 使用

```php
<?php
// Include Composer
require ("vendor/autoload.php");
// Use The Factory
use CachedRecursion/Factory;
// Creating Cached Function
$func = Factory::create([
    // Parameter name mapping
    "number"
] , function(\CachedRecursion\Parameter $param, \CachedRecursion\Next $next, \CachedRecursion\Resolve $solve){
    // Write Like Usual, but return $solve("the value"); and call $next for next.
    // A 5! example
    if($param['number'] == 1) return $resolve(1);
    else return $solve($param['number'] * $next($param['number'] - 1));
});

// Calling for result
echo $func(5); // 120
```

是不是很简单？
