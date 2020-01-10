# 2019 年终总结

% xtlsoft, 2020-1-5 10:52:04

## 重构

博客在暑假经历了一次比较大的重构，总体上来说就是迁移到 GitHub Pages 上，（并没有使用 Hexo）使用自己糊的单文件（极其简陋）实现的静态博客系统（不过 build 效率倒是挺高）。使用 Cloudflare 全家桶，界面使用 mdui 糊了一个，评论的话基于 Cloudflare Workers 做了一个很简单的。

迁移时顺便删除了很多没意义的文章，不过倒是把所有原来的评论都弄丢了。

顺便还搞了一个能看的个人主页，终于这个域名的根域也有用处了。

## 访问量

由于疼讯统计的某些问题，以及 Cloudflare 内置了一个 Analysis ，我倒是没有加其它的统计。

于是 Cloudflare Analysis 会将所有 Spider 的流量都当作访客流量……

于是我只能拿 `comment-worker` 的调用量数据了。

![comment-worker](/usr/uploads/2020/01/1.jpg)

（所以好像更加凄惨了）

## 展望

这几年大概是不会有很 Active 的活动了。

主要大概是会尽力实现之前的几个构想。
