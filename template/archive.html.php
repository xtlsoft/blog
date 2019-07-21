<?php include __DIR__ . "/header.html.php" ?>
<?php
global $articles;
$rslt = [];
foreach ($articles as $category) {
    $rslt = array_merge($rslt, $category);
}
usort($rslt, function ($a, $b) {
    return $a['time'] < $b['time'];
});
?>
<div class="mdui-typo">
    <h1>文章列表</h1>
    <ul>
        <?php foreach ($rslt as $v): ?>
            <li>
                <a href="/read/<?= $v['category_id'] ?>/<?= $v['id'] ?>.html"><?= $v['title'] ?></a>
                &nbsp; &nbsp;
                <small style="color: grey;">
                    <?= $v['category'] ?>,&nbsp;
                    <?= date('Y-m-d H:i:s', $v['time']) ?>
                </small>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php include __DIR__ . "/copyright.html.php" ?>
<?php include __DIR__ . "/footer.html.php" ?>