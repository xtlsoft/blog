<?php include __DIR__ . "/header.html.php" ?>
<?php global $categories ?>
<div class="mdui-container" style="margin: 20px 0px;">
    <div class="mdui-row-xs-1 mdui-row-sm-2 mdui-row-md-3 mdui-row-lg-4 mdui-row-xl-5 mdui-grid-list">
        <?php foreach ($categories as $i=>$n) : ?>
            <div class="mdui-col">
                <div class="mdui-grid-tile mdui-ripple">
                    <a href="/category/<?= $i ?>/1.html"><img src="https://cdn.jsdelivr.net/gh/idawnlight/typecho-theme-material@3.3.1/img/random/material-14.png"/></a>
                    <div class="mdui-grid-tile-actions">
                        <div class="mdui-grid-tile-text">
                            <div class="mdui-grid-tile-title"><?= $n ?></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include __DIR__ . "/copyright.html.php" ?>
<?php include __DIR__ . "/footer.html.php" ?>