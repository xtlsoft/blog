# PHP 直接采集百度 - 再也不要看百度丑陋的界面了

% xtlsoft, 2017-10-02 13:28:18

## 话不多说，代码拿来

```php
#!/usr/bin/php
<?php
/**
 * Searcher
 *
 * @author xtl<xtl@xtlsoft.top>
 *
 */

require_once "vendor/autoload.php";

use \QL\QueryList;
use \League\CLImate\CLImate;

$cli = new CLImate;

$word = $argv[1];

$url = "http://www.baidu.com/s?ie=utf-8&wd=$word";

$rule = array(
    'title' => array(".result>h3>a", 'text'),
    'link' => array(".result>h3>a", 'href')
);

$data = QueryList::get($url)->rules($rule)->query()->getData()->all();

//print_r($data->all());

foreach ($data as $k=>$v){
        $data[$k]['title'] = "<bold><blue>{$v['title']}</blue></bold>";
        $data[$k]['link']  = "<red><underline>{$v['link']}</underline></red>";
}

$cli->table($data);
```

## 屏幕截图

![screenshot](https://blog.xtlsoft.top/usr/uploads/2017/10/2841416807.png)

## 什么类库，自己猜去

防止伸手党 2333~~~

> 整理时添加：请勿相信百毒。
