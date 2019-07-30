<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $vars['title'] ?> - 徐天乐 :: 技术博客</title>
    <link rel="stylesheet" href="/mdui/css/mdui.min.css" />
    <link rel="stylesheet" href="/highlight.js/style.min.css" />
    <script src="/mdui/js/mdui.min.js"></script>
    <script>
        function jump(url) {
            window.location.href = url;
        }
    </script>
    <style>
        body,
        html {
            height: 100%;
        }
    </style>
</head>

<body class="mdui-appbar-with-toolbar mdui-theme-primary-blue mdui-drawer-body-left" style="height: 100%;">
    <header>
        <div class="mdui-appbar mdui-appbar-fixed">
            <div class="mdui-toolbar mdui-color-theme-900">
                <a href="javascript:;" mdui-drawer="{target: '#drawer-main'}" class="mdui-btn mdui-btn-icon mdui-ripple"><i class="mdui-icon material-icons">menu</i></a>
                <a href="/" class="mdui-typo-headline mdui-ripple">徐天乐 :: 技术博客</a>
                <a href="javascript:;" class="mdui-typo-title mdui-ripple"><?= $vars['title'] ?></a>
                <div class="mdui-toolbar-spacer"></div>
                <a href="/" class="mdui-btn mdui-btn-icon mdui-ripple"><i class="mdui-icon material-icons">home</i></a>
                <a href="https://www.xtlsoft.top/zh-cn" class="mdui-btn mdui-btn-icon mdui-ripple"><i class="mdui-icon material-icons">account_circle</i></a>
            </div>
        </div>
    </header>
    <div class="mdui-drawer" id="drawer-main">
        <ul class="mdui-list mdui-ripple">
            <?php
            $navbar = [
                ["name" => "首页", "icon" => "home", "link" => "/"],
                ["name" => "分类", "icon" => "list", "link" => "/categories.html"],
                ["name" => "归档", "icon" => "archive", "link" => "/archive.html"],
                ["name" => "友情链接", "icon" => "link", "link" => "/link.html"],
                ["name" => "个人主页", "icon" => "account_circle", "link" => "https://www.xtlsoft.top/zh-cn"],
                ["name" => "Mail", "icon" => "mail", "link" => "mailto:xtl@xtlsoft.top"]
            ];
            ?>
            <?php foreach ($navbar as $item) : ?>
                <li class="mdui-list-item mdui-ripple">
                    <i class="mdui-list-item-icon mdui-icon material-icons"><?= $item['icon'] ?></i>
                    <div onclick="jump('<?= $item['link'] ?>');" class="mdui-list-item-content"><?= $item['name'] ?></div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div id="main" style="margin: 0px 15px; min-height: 90%;">