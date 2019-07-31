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