/**
 * xtlsoft.blog
 * 
 * @author Tianle Xu <xtl@xtlsoft.top>
 * @license MIT
 */

window.$$ = mdui.JQ;
window.commentWorker = {
    basePath: "/comment-worker/",
    calculatePath: () => {
        if (window.commentPath) return window.commentWorker.basePath + window.commentPath;
        if (window.location.pathname.substr(0, 6) !== "/read/") throw new Error("URL Error");
        if (window.location.pathname.substr(-5) !== ".html") throw new Error("URL Error");
        return window.commentWorker.basePath +
            window.location.pathname.substr(6, window.location.pathname.length - 11);
    },
    create: (author, email, content) => {
        let time = Date.now();
        let req = {
            author: author,
            email: email,
            content: content,
            time: time
        };
        return new Promise((resolve, reject) => {
            $$.ajax({
                method: 'POST',
                url: window.commentWorker.calculatePath(),
                data: JSON.stringify(req),
                success: data => {
                    resolve(JSON.parse(data));
                },
                error: data => {
                    reject(data)
                }
            });
        });
    },
    get: () => {
        return new Promise((resolve, reject) => {
            $$.ajax({
                method: 'GET',
                url: window.commentWorker.calculatePath(),
                success: data => {
                    resolve(JSON.parse(data));
                },
                error: data => {
                    reject(data)
                }
            });
        });
    },
    delete: (key, id) => {
        return new Promise((resolve, reject) => {
            $$.ajax({
                method: 'DELETE',
                url: window.commentWorker.calculatePath(),
                data: key + " " + id.toString(),
                success: data => {
                    resolve(JSON.parse(data))
                },
                error: data => {
                    reject(data)
                }
            });
        });
    }
};

window.commentPresenter = {
    template: "<div class=\"mdui-card\" id=\"comment_id_${id}\">\n        <div class=\"mdui-card-header\">\n            <img class=\"mdui-card-header-avatar\" src=\"${avatar}\" \/>\n            <div class=\"mdui-card-header-title\">${author}<\/div>\n            <div class=\"mdui-card-header-subtitle\">${email}, ${time}<\/div>\n        <\/div>\n        <div class=\"mdui-card-content\">\n            <div class=\"mdui-typo\">${content}<\/div>\n        <\/div>\n        <div class=\"mdui-card-actions\">\n            <button class=\"mdui-btn mdui-btn-icon\" onclick=\"window.commentUI.replyTo(${id});\">\n                <i class=\"mdui-icon material-icons\">reply<\/i>\n            <\/button>\n            <button class=\"mdui-btn mdui-btn-icon\" onclick=\"window.commentUI.share(${id});\">\n                <i class=\"mdui-icon material-icons\">share<\/i>\n            <\/button>\n            <button class=\"mdui-btn mdui-btn-icon mdui-float-right\" onclick=\"window.commentUI.delete(${id});\">\n                <i class=\"mdui-icon material-icons\">delete_forever<\/i>\n            <\/button>\n        <\/div>\n    <\/div>",
    basic_template: "<div class=\"mdui-container-fluid xblog-comment\" id=\"comment\">\n    <div class=\"mdui-card\">\n        <form id=\"comment_form\" class=\"xblog-comment-form mdui-row mdui-card-content\">\n            <h2>\u65b0\u8bc4\u8bba<\/h2>\n            <div class=\"xblog-padding-right xblog-xs-no-padding mdui-textfield mdui-textfield-floating-label mdui-col-xs-12 mdui-col-sm-6 mdui-col-md-6 mdui-col-lg-6 mdui-col-xs-6\">\n                <label class=\"mdui-textfield-label\">\u6635\u79f0<\/label>\n                <input maxlength=\"25\" class=\"mdui-textfield-input\" name=\"name\" type=\"text\" \/>\n            <\/div>\n            <div class=\"xblog-padding-left xblog-xs-no-padding mdui-textfield mdui-textfield-floating-label mdui-col-xs-12 mdui-col-sm-6 mdui-col-md-6 mdui-col-lg-6 mdui-col-xs-6\">\n                <label class=\"mdui-textfield-label\">\u90ae\u7bb1<\/label>\n                <input maxlength=\"110\" class=\"mdui-textfield-input\" name=\"email\" type=\"email\" \/>\n            <\/div>\n            <div class=\"mdui-textfield mdui-textfield-floating-label mdui-col-xs-12 mdui-col-sm-12 mdui-col-md-12 mdui-col-lg-12 mdui-col-xs-12\">\n                <label class=\"mdui-textfield-label\">\u8bc4\u8bba\u5185\u5bb9<\/label>\n                <textarea maxlength=\"350\" name=\"content\" class=\"mdui-textfield-input\"><\/textarea>\n            <\/div>\n            <input type=\"submit\" id=\"comment_submit_button\" class=\"mdui-btn mdui-btn-raised mdui-ripple mdui-color-theme\" value=\"\u65b0\u8bc4\u8bba\">\n            <button type=\"button\" id=\"comment_delete_all_button\" class=\"mdui-btn mdui-float-right mdui-ripple\">\u5220\u9664\u6240\u6709\u8bc4\u8bba<\/button>\n        <\/form>\n    <\/div>\n    <div id=\"comment_container\"><\/div>\n<\/div>"
};

window.commentPresenter.init = function (elem_id) {
    var $$ = mdui.JQ;
    $$(elem_id).html(window.commentPresenter.basic_template);
    $$('#comment_form').on('submit', () => {
        let form_obj = document.getElementById('comment_form');
        let name = form_obj.name.value;
        let email = form_obj.email.value;
        let content = form_obj.content.value;
        if (!name || !email || !content) {
            mdui.alert('请将表单填写完整！', '提示');
            return false;
        }
        let time = Date.now();
        $$('#comment_submit_button').attr('disabled', 'true');
        commentWorker.create(name, email, content).then((data) => {
            $$('#comment_submit_button').removeAttr('disabled');
            form_obj.content.innerHTML = "";
            form_obj.content.value = "";
            window.appendComment({
                author: name,
                email: email,
                content: content,
                time: time,
                id: data.id
            })
            mdui.alert("评论添加成功！编号：" + data.id.toString(), "提示");
        });
        return false;
    });
    $$('#comment_delete_all_button').on('click', () => {
        window.commentUI.delete(-1);
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
    window.stripTags = (v) => {
        return v.replace('<', '&lt;').replace('>', '&gt;');
    };
    window.appendComment = (v) => {
        var elemStr = window.commentPresenter.template;
        v.author = window.stripTags(v.author);
        elemStr = elemStr.replace(/\${author}/g, v.author);
        v.email = window.stripTags(v.email);
        elemStr = elemStr.replace(/\${email}/g, v.email);
        v.content = window.stripTags(v.content);
        elemStr = elemStr.replace(/\${content}/g, v.content);
        elemStr = elemStr.replace(/\${time}/g, window.parseTimeStamp(v.time));
        elemStr = elemStr.replace(/\${avatar}/g, "https://avatar.dawnlab.me/gravatar/" + md5(v.email));
        elemStr = elemStr.replace(/\${id}/g, v.id.toString());
        var elem = $$(elemStr);
        $$("#comment_container").prepend(elem);
    };
    window.commentUI = {
        replyTo: (id) => {
            window.location.hash = "comment";
            $$('#comment_form textarea').prepend('#' + id.toString() + ' ')[0].click();
        },
        share: (id) => {
            var url = location.protocol + "//" + location.host + location.pathname +
                '#comment_id_' + id.toString();
            mdui.alert("本评论链接：<textarea rows=1>" + url + "</textarea>", "分享");
        },
        delete: (id) => {
            mdui.prompt(
                '请输入 Delete Key 以删除 #' + id.toString() + ': ',
                (val) => {
                    var progress_dialog = mdui.dialog({
                        content: '<div class="mdui-progress"><div class="mdui-progress-indeterminate"></div></div>',
                        modal: true
                    });
                    commentWorker.delete(val, id).then((rslt) => {
                        if (rslt.status === 'error') {
                            setTimeout(() => {
                                progress_dialog.close();
                            }, 1000);
                            mdui.alert('删除评论错误：' + rslt.error);
                        } else {
                            window.location.reload();
                        }
                    });
                },
                (val) => {
                    return false;
                }, {
                    type: 'text',
                    confirmText: '确定',
                    cancelText: '取消',
                    modal: true
                }
            );
        }
    };
    commentWorker.get().then((data) => {
        for (var k in data) {
            if (data[k] === null) continue;
            var v = data[k];
            v.id = k;
            window.appendComment(v);
        }
    });
    $$(window).on('load resize', () => {
        if (window.innerWidth <= 600) {
            $$('.xblog-xs-no-padding').addClass('xblog-no-padding');
        } else {
            $$('.xblog-xs-no-padding').removeClass('xblog-no-padding')
        }
    });
};