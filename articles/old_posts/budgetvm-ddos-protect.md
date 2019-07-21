# Budgetvm 的 DDoS 防护 - 不得不看

% xtlsoft, 2017-03-26 11:18:02

<p>本博客又经过一次迁移，到了budgetvm上面。</p><p>budgetvm 十分便宜，而且提供免费的DDoS防护。</p><p>为了测试，昨天我自己打了5Gbps/120s的DDoS到博客上，没半点问题，但是打10Gbps/300s的时候，触发了budgetvm的DDoS Filter。</p><p>DDoS Filter会自动在80端口启用一个速度较慢的CC防火墙，把你在443端口的ssl干掉，然后不断屏蔽ip。这个特别对国内流量做了&ldquo;优化&rdquo;，国内的基本上访问个两三次，频率高一点就会屏蔽，然后自动禁Ping。我相信谁都不想这样。</p><p>客服说明了，在流量不高的时候，不做处理；流量中等，启动DDoS Filter；流量太高，直接nullroute（断网）。</p><p>我关闭DDoS攻击后，等了好长时间，不见DDoS Filter关闭，就发了ticket给客服。</p><p>问题是，一连20几个工单reply，回复基本都是&ldquo;It will fall off automatically(他会自动关闭)&rdquo;，还有&quot;If there's no DDoS Filter, your server will be nullrouted&quot;这类的。</p><p>没办法，只好等。2017/3/25 8:34AM 结束攻击，一直到2017/3/26中午（我人不在，不知道具体什么时候）才恢复了。</p><p>而且客服说，买的DDoS防护越高，越会触发DDoS Filter。</p><p>所以，建议各位使用budgetvm的小伙伴，事先开启8443、8080做备用端口（这两个不受DDoS Filter影响），然后千万不要购买更高的DDoS防护（烧钱还不好），事先用cloudflare dns，一旦有攻击，一键走cloudflare。</p>
