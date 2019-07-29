<script src="/comment/comment.js" type="text/javascript"></script>
<script src="/comment/md5.js" type="text/javascript"></script>
<style>
    .xblog-comment-form .mdui-textfield {
        padding: 0;
    }

    .xblog-comment-form .xblog-padding-right {
        padding-right: 5px;
    }

    .xblog-comment-form .xblog-padding-left {
        padding-left: 5px;
    }

    .xblog-comment-form .mdui-btn {
        margin-top: 15px;
    }

    .xblog-comment-new {
        border: 1px solid silver;
        padding: 15px 20px;
        margin: 0px;
        border-radius: 7px;
    }

    .xblog-comment-form h2 {
        margin-top: 0.1em;
        margin-bottom: 0.2em;
        margin-left: 0px;
        font-family: inherit;
        font-weight: 400;
        line-height: 1.35;
        color: inherit;
        font-size: 1.5em;
    }

    .xblog-comment .mdui-card {
        margin-bottom: 15px;
    }
</style>
<div class="mdui-container-fluid xblog-comment" id="comment">
    <div class="mdui-card">
        <form id="comment_form" class="xblog-comment-form mdui-row mdui-card-content">
            <h2>新评论</h2>
            <div class="xblog-padding-right mdui-textfield mdui-textfield-floating-label mdui-col-xs-12 mdui-col-sm-6 mdui-col-md-6 mdui-col-lg-6 mdui-col-xs-6">
                <label class="mdui-textfield-label">昵称</label>
                <input maxlength="25" class="mdui-textfield-input" name="name" type="text" />
            </div>
            <div class="xblog-padding-left mdui-textfield mdui-textfield-floating-label mdui-col-xs-12 mdui-col-sm-6 mdui-col-md-6 mdui-col-lg-6 mdui-col-xs-6">
                <label class="mdui-textfield-label">邮箱</label>
                <input maxlength="110" class="mdui-textfield-input" name="email" type="email" />
            </div>
            <div class="mdui-textfield mdui-textfield-floating-label mdui-col-xs-12 mdui-col-sm-12 mdui-col-md-12 mdui-col-lg-12 mdui-col-xs-12">
                <label class="mdui-textfield-label">评论内容……</label>
                <textarea maxlength="350" name="content" class="mdui-textfield-input"></textarea>
            </div>
            <input type="submit" id="comment_submit_button" class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-theme" value="新评论">
        </form>
    </div>
    <div id="comment_container"></div>
</div>
<script type="text/plain" id="comment_template">
    <div class="mdui-card">
        <div class="mdui-card-header">
            <img class="mdui-card-header-avatar" src="${avatar}" />
            <div class="mdui-card-header-title">${author}</div>
            <div class="mdui-card-header-subtitle">${email}, ${time}</div>
        </div>
        <div class="mdui-card-content">${content}</div>
        <div class="mdui-card-actions">
            <button class="mdui-btn mdui-btn-icon" onclick="window.location.hash='comment';"><i class="mdui-icon material-icons">reply</i></button>
        </div>
    </div>
</script>
<script>
    var $$ = mdui.JQ;
    $$('#comment_form').on('submit', () => {
        let form_obj = document.getElementById('comment_form');
        let name = form_obj.name.value;
        let email = form_obj.email.value;
        let content = form_obj.content.innerHTML;
        let time = Date.now();
        $$('#comment_submit_button').attr('disabled', 'true');
        commentWorker.create(name, email, content).then((data) => {
            $$('#comment_submit_button').removeAttr('disabled');
            form_obj.name.value = "";
            form_obj.email.value = "";
            form_obj.content.innerHTML = "";
            window.appendComment({
                author: name,
                email: email,
                content: content,
                time: time
            })
            mdui.alert("评论添加成功！编号：" + data.id.toString(), "提示");
        });
        return false;
    });
    window.parseTimeStamp = (time) => {
        var dat = new Date(time);
        var yr = dat.getFullYear().toString();
        var mo = "" + (dat.getMonth() + 1 < 10 ? "0" : "") + (dat.getMonth() + 1);
        var dy = "" + (dat.getDate() < 10 ? "0" : "") + dat.getDate();
        var hr = "" + (dat.getHours() < 10 ? "0" : "") + dat.getHours();
        var mi = "" + (dat.getMinutes() < 10 ? "0" : "") + dat.getMinutes();
        var se = "" + (dat.getSeconds() < 10 ? "0" : "") + dat.getSeconds();
        return yr + "-" + mo + "-" + dy + " " + hr + ":" + mi + ":" + se;
    }
    window.appendComment = (v) => {
        var elemStr = document.getElementById('comment_template').innerHTML;
        elemStr = elemStr.replace('${author}', v.author);
        elemStr = elemStr.replace('${email}', v.email);
        elemStr = elemStr.replace('${content}', v.content);
        elemStr = elemStr.replace('${time}', window.parseTimeStamp(v.time));
        elemStr = elemStr.replace('${avatar}', "https://avatar.dawnlab.me/gravatar/" + md5(v.email));
        var elem = $$(elemStr);
        $$("#comment_container").prepend(elem);
    };
    commentWorker.get().then((data) => {
        for (var k in data) {
            if (data[k] === null) continue;
            var v = data[k];
            window.appendComment(v);
        }
    });
</script>