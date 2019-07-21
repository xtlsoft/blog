# Cmder+Chocolatey - 这才是 Windows

% xtlsoft, 2017-08-16 08:42:19

## Windows V.S. Linux

我可能就是那种认为如果没有浏览器就不需要图形界面的人吧。
我在命令行下的效率比在图形界面高得多。
这是我为什么热衷 linux。
Windows 的 Cmd 和 Powershell 简直看上去垃圾，而 cygwin 又没有命令行的包管理，而且不是完美方案。

## Cmder - A Wonderful CLI Interface

想必大家用 Windows 自带的 cmd 都厌倦了，而且经常会有打`ls`出来 Windows 找不到该命令然后懵逼半天的经历。
Cmder——不仅仅是好看。我从来没有推荐过任何一款 linux 下的终端软件，因为即使是`XTerm`，即使它不支持中文，我也觉得足够了。而在 windows 下面，可以和他们媲美的，可能就只有 cmder 了吧。
而且，cmder 自带了一个 cygwin 和一个 alias，也就是说，你打`ls`再也不会出现找不到该命令了。如果想要纯真的 linux，直接输入`bash`就可以启用 cygwin。
并且，原生支持多窗口！这个必须顶，界面还是透明的，极客风十足！最重要的是，还是绿色、开源的！

## Chocolatey - Package Manager for Windows

用完 pacman，yum，apt，他们各有好坏，但都很好用，一条命令一个软件。
但是，在 Windows 下面，安装软件就繁多了，还要点鼠标，都厌烦了。
这时，Chocolatey 简直是救星。
例如，需要安装`wget`，只要：
`cinst wget`
而且，如果软件有依赖包（例如 php 依赖 vcredist），他也会自动安装。
太方便了有没有！！！

> 整理时补充：真不错，我选择 WSL2 + Windows Terminal （逃）。
