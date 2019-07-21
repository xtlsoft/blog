# lxd 中 exec 命令的 mode 选项

% xtlsoft, 2017-06-04 17:21:36

<p>在lxd中的exec下，有一个mode选项不起眼，但是它很重要。</p><p>特别在开发lxdpanel过程中，lxdConsole用了它不止一次。</p><p>用法：lxd exec ${Hostname} --mode=${mode} ${Command}</p><p>${mode} 可以是interactive（交互式）或 non-interactive（非交互式）。</p><p>平常终端下，默认是interactive，否则non-interactive。</p><p>在interactive下，是用标准输入输出流执行命令，否则没有输入流。</p><p>例如在PHP下用system时，只能用non-interactive。</p><p>注：lxd官方文档还是少啊！好多都要自己研究啊！</p>

> 整理时补充：与 `docker exec` 的 `-ti` 选项差不多。
