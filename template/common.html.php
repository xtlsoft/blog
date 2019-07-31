<?php include __DIR__ . "/header.html.php"; ?>
<div class="mdui-typo">
    <div>
        <?= $vars['content'] ?>
    </div>
</div>
<?php if (isset($vars['comment'])) : ?>
    <script>
        window.commentPath = '<?= $vars['comment'] ?>';
    </script>
    <?php include __DIR__ . "/comment.html.php"; ?>
<?php endif; ?>
<script>
    mdui.JQ("table").addClass("mdui-table mdui-table-hoverable");
</script>
<?php include __DIR__ . "/copyright.html.php"; ?>
<?php include __DIR__ . "/footer.html.php"; ?>