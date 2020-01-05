<link rel="stylesheet" href="/comment/comment.css" />
<script src="/comment/md5.js" type="text/javascript"></script>
<script src="/comment/comment.js" type="text/javascript"></script>
<?php if (getenv('MOCK_COMMENT')) : ?>
    <script src="/comment/comment-mock.js"></script>
<?php endif; ?>
<div class="mdui-typo">
    <br />
    <h1>评论</h1>
</div>
<div id="comment_base"></div>
<script>
    window.commentPresenter.init("#comment_base");
</script>