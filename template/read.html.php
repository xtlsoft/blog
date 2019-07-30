<?php
global $articles;
$v = $articles[$vars['category']][$vars['offset']];
$vars['title'] = $v['title'];
?>
<?php include __DIR__ . "/header.html.php"; ?>
<div class="mdui-typo">
    <h1>
        <?= $v['title'] ?>
        <br />
        <small>
            作者: <?= $v['author'] ?> &nbsp; &nbsp;
            时间: <?= date("Y-m-d H:i:s", $v['time']) ?> &nbsp; &nbsp;
            分类: <?= $v['category'] ?>
        </small>
    </h1>
    <div>
        <?= $v['content'] ?>
    </div>
</div>
<?php include __DIR__ . "/comment.html.php"; ?>
<script>
    mdui.JQ("table").addClass("mdui-table mdui-table-hoverable");
</script>
<?php include __DIR__ . "/copyright.html.php"; ?>
<?php include __DIR__ . "/footer.html.php"; ?>