window.commentWorker = {
    get: () => {
        return new Promise((resolve, reject) => {
            resolve([{
                    author: "xtlsoft",
                    email: "xtl@xtlsoft.top",
                    content: "Hello World",
                    time: Date.now()
                },
                {
                    author: "xtlsoft",
                    email: "xtl@xtlsoft.top",
                    content: "Comment 2",
                    time: Date.now()
                },
                {
                    author: "comment-bot",
                    email: "bot@comment.dev.local",
                    content: "test comment",
                    time: Date.now()
                }
            ]);
        });
    },
    create: (name, mail, content) => {
        return new Promise((resolve, reject) => {
            resolve({
                status: "success",
                id: 3
            });
        });
    },
    delete: (key, id) => {
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                if (key !== "123") {
                    resolve({
                        status: "error",
                        error: "Wrong Key"
                    });
                } else {
                    resolve({
                        status: "success"
                    });
                }
            }, 100);
        });
    }
};