# PHP 输出流重定向到文件等地方（Output Buffer）

% xtlsoft, 2017-04-23 07:58:54

<div><font style="background-color: rgb(255, 255, 255);">在某些情况，我们要将PHP CLI的输出流重定向到某个地方。</font></div><div>&nbsp;</div><div><font style="background-color: rgb(255, 255, 255);">我们可以使用Output Buffer实现。</font></div><div>&nbsp;</div><div>&nbsp;</div><pre>&lt;?php<br />@ob_start();<br />//正常代码><br />file_put_contents(&quot;要写出的文件名.log&quot;,ob_get_clean());<br />?&gt;</pre>
