# 一个垃圾的点子：让你的 curl 更优雅

% xtlsoft, 2017-12-30 21:03:03

## 话不多说，代码拿来

```php
#!/usr/bin/php

<?php

    $m = $argv[1];
    $u = $argv[2];

    $ak = "";
    $a = "";

    foreach($argv as $k=>$v){

        if($k === 0 || $k === 1 || $k === 2) continue;

        if($k % 2){
            $ak = $v;
        }else{

            $a .= urlencode($ak) . "=" . urlencode($v) . "&";

        }

    }

    echo shell_exec("curl http://接口地址/$u -X $m -d \"$a\"") . "\r\n";
```

## 使用

保存上面的代码到 `req` ，然后就可以：

```bash
php req （请求方式，例如GET，POST） （请求URI，例如 test/version.json） [参数1名称 参数1内容 [参数2名称 参数2内容 [参数3名称 参数3内容 [...]]]]
```

## 由来

调试 Passport@XApps Authserver 的时候，觉得 curl 标准写法（`curl http://example.com/example.json -X GET -d "key1=val1&key2=val2&..."`）不够优雅、方便，于是。。。

## 后记

暂无。

> 整理时添加：没啥用。
