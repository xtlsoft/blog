<?php include __DIR__ . "/header.html.php"; ?>
<?php
global $articles;
$rslt = [];
if ($vars['category'] === 'all') {
    foreach ($articles as $category) {
        $rslt = array_merge($rslt, $category);
    }
} else {
    $rslt = $articles[$vars['category']];
}
usort($rslt, function ($a, $b) {
    return $a['time'] < $b['time'];
});
$start = ($vars['limit']['page'] - 1) * $vars['limit']['number'];
$end = min($start + $vars['limit']['number'], count($rslt));
?>
<style>
    .list-unstyled {
        padding-left: 0px;
        list-style: none;
    }

    .list-unstyled li {
        display: block;
        margin-bottom: 5px;
    }

    .xblog-list-card-container {
        margin: 15px auto;
        max-width: 1050px;
        min-height: 400px;
    }

    .xblog-list-card-container .mdui-card-media {
        background-position: center center;
        background-repeat: no-repeat;
        /* background-attachment: fixed; */
        background-size: cover;
        background-color: #464646;
    }

    .xblog-footer-nav-text {
        box-sizing: border-box;
        display: inline-block;
        font-size: 20px;
        font-weight: 500;
        -webkit-font-smoothing: antialiased;
        height: 100%;
        line-height: 24px;
        padding-top: 24px;
        width: 100%;
    }

    .xblog-footer-nav-text .xblog-footer-nav-direction {
        font-size: 15px;
        line-height: 18px;
        margin-bottom: 1px;
        opacity: 0.55;
    }
</style>

<ul class="list-unstyled">
    <?php for ($i = $start; $i < $end; ++$i) : ?>
        <?php $v = $rslt[$i]; ?>
        <?php $url = "/read/" . $v['category_id'] . '/' . $v['id'] . ".html"; ?>
        <?php $bg_url = "https://cdn.jsdelivr.net/gh/idawnlight/typecho-theme-material@3.3.1/img/random/material-" . rand(1, 12) . ".png"; ?>
        <li>
            <div class="mdui-card xblog-list-card-container">
                <a href="<?= $url ?>">
                    <div class="mdui-card-media mdui-ripple" style="background-image: url(<?= $bg_url ?>);">
                        <div style="height: 300px;"></div>
                        <div class="mdui-card-media-covered">
                            <div class="mdui-card-primary">
                                <div class="mdui-card-primary-title"><?= $v['title'] ?></div>
                                <div class="mdui-card-primary-subtitle">分类：<?= $v['category'] ?></div>
                            </div>
                        </div>
                    </div>
                </a>
                <div class="mdui-card-content">
                    <?= substr(htmlspecialchars(strip_tags($v['content'])), 0, 90) ?>...
                    &nbsp; &nbsp; &nbsp;
                    <a href="<?= $url ?>">继续阅读</a>
                    <hr class="mdui-divider" style="margin: 10px 0;" />
                    <div>
                        <img src="http://avatar.dawnlab.me/github//" style="width: 50px; height: 50px; float: left; margin-top: 2px;" alt="avatar" class="mdui-img-circle" />
                        <div>
                            <ul class="list-unstyled" style="margin-left: 60px;">
                                <li><b>作者：</b><?= $v['author'] ?></li>
                                <li><b>时间：</b><?= date('Y-m-d H:i:s', $v['time']) ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </li>
    <?php endfor; ?>
</ul>
</div>
<div class="mdui-color-theme" style="height: 96px;">
    <div class="mdui-container">
        <div class="mdui-row">
            <?php if ($vars['limit']['page'] != 1) : ?>
                <a class="mdui-col-xs-2 mdui-col-sm-6 mdui-ripple mdui-color-theme" style="height: 96px; text-align:left;" href="./<?= $vars['limit']['page'] - 1 ?>.html">
                    <div class="xblog-footer-nav-text">
                        <i class="mdui-icon material-icons">arrow_back</i>
                        <span class="xblog-footer-nav-direction">Back</span>
                        <div style="margin-right: 0px;">上一页</div>
                    </div>
                </a>
            <?php else : ?>
                <div class="mdui-col-xs-2 mdui-col-sm-6"></div>
            <?php endif; ?>
            <?php if ($vars['limit']['page'] < ceil(count($rslt) / $vars['limit']['number'])) : ?>
                <a class="mdui-ripple mdui-color-theme mdui-col-xs-10 mdui-col-sm-6" style="height: 96px; text-align:right;" <?php if (isset($vars['next_url'])) : ?> href="<?= $vars['next_url'] ?>" <?php else : ?> href="./<?= $vars['limit']['page'] + 1 ?>.html" <?php endif; ?>>
                    <div class="xblog-footer-nav-text">
                        <i class="mdui-icon material-icons">arrow_forward</i>
                        <span class="xblog-footer-nav-direction">Next</span>
                        <div style="margin-right: 0px;">下一页</div>
                    </div>
                </a>
            <?php else : ?>
                <div class="mdui-col-xs-2 mdui-col-sm-6"></div>
            <?php endif; ?>
        </div>
    </div>
    <?php include __DIR__ . "/footer.html.php"; ?>