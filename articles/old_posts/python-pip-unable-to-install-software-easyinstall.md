# Python PIP 无法安装应用 - easyinstall 的锅

% xtlsoft, 2017-04-23 08:00:03

<div>MARKER_EXPR = originalTextFor(MARKER_EXPR())(&quot;marker&quot;)<br  />TypeError: __call__() takes exactly 2 arguments (1 given)</div><div>&nbsp;</div><div>在CentOS7下，一旦使用pip安装程序，就会出现这个错误。</div><div>非常郁闷，找了好多地方，最终确定是这个问题。</div><div>&nbsp;</div><div>python -m pip install --upgrade --force pip &amp;&amp;&nbsp;pip install setuptools==33.1.1</div><div>执行以后就正常了。</div><div>&nbsp;</div><div>参考文献：https://m.th7.cn/show/48/201704/211248.html</div>
