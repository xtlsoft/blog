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
$start = ($vars['limit']['page'] - 1) * $vars['limit']['number'];
$end = min($start + $vars['limit']['number'], count($articles));
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
        margin: 10px auto;
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
</style>

<ul class="list-unstyled">
    <?php for ($i = $start; $i < $end; ++$i) : ?>
        <?php $v = $rslt[$i]; ?>
        <?php $url = "/read/" . $v['id'] . ".html"; ?>
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
                        <img src="http://avatar.dawnlab.me/github/xtlsoft/" style="width: 50px; height: 50px; float: left; margin-top: 2px;" alt="avatar" class="mdui-img-circle" />
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
<?php include __DIR__ . "/footer.html.php"; ?>