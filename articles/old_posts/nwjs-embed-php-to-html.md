# nw.js 中在 HTML 内嵌 PHP

% xtlsoft, 2017-07-30 19:39:52

## 由来

nw.js 真的是一个很好用的桌面 hybird 应用引擎啊！
而我不想写 Nodejs，只想写 PHP...
于是就有了这个想法。

## 实现

不多说，直接贴代码：

```javascript
/** bridge.js **/
/** @author xtl <xtl@xtlsoft.top> **/

(window.util = require("util")),
  (window.sexec = require("child_process").spawn);

window.toUTF8 = function(gbk) {
  if (!gbk) {
    return "";
  }
  var utf8 = [];
  for (var i = 0; i < gbk.length; i++) {
    var s_str = gbk.charAt(i);
    if (!/^%u/i.test(escape(s_str))) {
      utf8.push(s_str);
      continue;
    }
    var s_char = gbk.charCodeAt(i);
    var b_char = s_char.toString(2).split("");
    var c_char = b_char.length == 15 ? [0].concat(b_char) : b_char;
    var a_b = [];
    a_b[0] = "1110" + c_char.splice(0, 4).join("");
    a_b[1] = "10" + c_char.splice(0, 6).join("");
    a_b[2] = "10" + c_char.splice(0, 6).join("");
    for (var n = 0; n < a_b.length; n++) {
      utf8.push(
        "%" +
          parseInt(a_b[n], 2)
            .toString(16)
            .toUpperCase()
      );
    }
  }
  return utf8.join("");
};

// fs = fs("php_bin\\php",['php\\index.php']);
// fs.stdout.on("data", function (d){
//     document.write("<pre>"+d+"</pre>");
// });

window.phpPath = "php_bin\\php";
window.phpScriptDirectory = "php\\";

window.php = {
  exec: function(command, callback) {
    fs = sexec(phpPath, ["-r", command]);
    fs.stdout.on("data", function(d) {
      callback(d);
    });
  },

  file: function(file, callback, arg) {
    fs = sexec(phpPath, [phpScriptDirectory + file, JSON.stringify(arg)]);
    fs.stdout.on("data", function(d) {
      callback(d);
    });
  },

  getScript: function(id) {
    return document.getElementById(id).innerHTML;
  }
};
```

```php
<?php
    /**
     * Bridge File
     *
     * bridge.php
     *
     * @package nwjs2php
     * @author xtl <xtl@xtlsoft.top>
     * @license MIT
     *
     */

    chdir(__DIR__);

function utf16_to_utf8($str) {
    $c0 = ord($str[0]);
    $c1 = ord($str[1]);

    if ($c0 == 0xFE && $c1 == 0xFF) {
        $be = true;
    } else if ($c0 == 0xFF && $c1 == 0xFE) {
        $be = false;
    } else {
        return $str;
    }

    $str = substr($str, 2);
    $len = strlen($str);
    $dec = '';
    for ($i = 0; $i < $len; $i += 2) {
        $c = ($be) ? ord($str[$i]) << 8 | ord($str[$i + 1]) :
                ord($str[$i + 1]) << 8 | ord($str[$i]);
        if ($c >= 0x0001 && $c <= 0x007F) {
            $dec .= chr($c);
        } else if ($c > 0x07FF) {
            $dec .= chr(0xE0 | (($c >> 12) & 0x0F));
            $dec .= chr(0x80 | (($c >>  6) & 0x3F));
            $dec .= chr(0x80 | (($c >>  0) & 0x3F));
        } else {
            $dec .= chr(0xC0 | (($c >>  6) & 0x1F));
            $dec .= chr(0x80 | (($c >>  0) & 0x3F));
        }
    }
    return $dec;
}

    $arg = utf16_to_utf8($argv[1]);

    $GLOBALS['_GET'] = json_decode($arg, true);

    foreach($_GET as $k => $v){
        $_GET[$k] = urldecode($v);
    }
```

## 使用

两种方法：

1、内嵌代码：

```javascript
php.exec("echo 'HelloWorld';//More Code here", function(d) {
  alert(d);
});
```

2、外置文件

```javascript
var data = { toEcho: "China I love you!" };
php.file(
  "index.php",
  function(d) {
    alert(d);
  },
  data
);
```

> 整理时补充：请避免如此粗暴的黑科技。
