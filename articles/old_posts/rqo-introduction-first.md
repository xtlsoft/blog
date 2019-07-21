# Rqo - 一个轻快、完全标准化、模块化的 PHP 框架

% xtlsoft, 2017-10-22 08:58:56

## 什么是 Rqo

Rqo, 一个轻快、完全标准化、模块化的 PHP 框架。初衷是 MVVM 后端和 API 框架，但是实际上我还是写了 MVC 的模块。

哪里用得不愉快，可以直接自己替换掉这个模块。

GitHub： <https://github.com/xtlsoft/RQO>

## 灵感来源

说来也怪，在看 PHP-X 的源码时，想起了 ReactPHP，然后觉的 Workerman 一堆不足，并且觉得之前的 XPHP 框架太传统（虽然它的定位就是一个轻巧的传统的框架，放心没弃坑，只是下次更新内容太大，魔改成全事件处理了，工作量太大，加上开学了，还没来得及），于是就挖了一个新坑。

## 优点

- 完全模块化，可以随意替换，各种框架模式都独立出来。
- 基于 ReactPHP，非常迅速，原生支持协程（详见 <https://github.com/xtlsoft/XCoroutine> ）。
- 无需多余的配置，没有冗余的代码，直接运行，一步直达。
- 完全标准化，代码质量高，复用性强，使用了许多新特性。
- 针对性强，开发周期短，跨平台，一份代码在 Windows，Linux，Mac 上都可以直接运行，不需要像 Swoole/Workerman 一样修改。
- 接口丰富，拓展方便，看着舒心。
- 用法非常丰富，只要脑洞够大，怎么写，都可以。
- 5 年经验~~老司机~~倾情奉献，实战项目保证。
- 很多轮子不需要再造，完全内置，完全依赖 composer。
- 兼容 PHP-FIG 标准，并且提供了丰富的内置类库。

## 使用场景

很多。都可以。
原生支持以下 3 中协议进行交互，可以拓展：

1. HTTP (`\Rqo\Http\Route`)
2. WebSocket (`\Rqo\WebSocket\Route`)
3. GraphQL over HTTP (`\Rqo\Http\GraphQL\Route`)
   只要实例化不同的 route 就行。

## 例子

下面是一个例子：
(Application/Test/Application.php)

```php
<?php
    /**
     * TestApplication
     *
     * @author (YourName)<name@example.com>
     * @package Test
     * @license MIT
     *
     */

    namespace Application\Test;

    $App = new \Rqo\Application("127.0.0.1:28123", []); //Create An Application Which Listen On 28123

    $Route = new \Rqo\Http\Route();

    $Route->get("{any}", function(\Rqo\Http\Request $req){

        $html = '
<!Doctype html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Rqo Test Application</title>
    </head>
    <body>
        This is the test application in xtlsoft\' blog.
    </body>
</html>
        '

        return (
            new \Rqo\Http\Response(200) ->
            header(
                new \Rqo\Http\Header([
                    "content-type" => "text/html"
                ])
            ) ->
            write($html)
        );

    });

    $App->route($Route);

    \Rqo\Application::add($App);

```

> 整理时添加：弃坑已久。
