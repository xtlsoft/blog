# Ubuntu Server 安装出错 busybox-initramfs 解决

% xtlsoft, 2017-04-29 15:31:04

<p><font style="background-color: rgb(255, 255, 255);">今天在安装Ubuntu Server 16.04 LTS时，突发busybox-initramfs安装出错。</font></p><p><font style="background-color: rgb(255, 255, 255);">这个情况很奇葩，查找大量资料，有说把quiet改成all_.._..的，尝试了很长时间，也没用。</font></p><p><font style="background-color: rgb(255, 255, 255);">还有用while true来重复删除安装的lock的，也没用。</font></p><p><font style="background-color: rgb(255, 255, 255);">最后，终于在askubuntu下找到了蛛丝马迹。</font></p><p><font style="background-color: rgb(255, 255, 255);">真是奇葩，在grub引导那里，语言必须选英文。进了安装，才可以选中文。</font></p><p><font style="background-color: rgb(255, 255, 255);">折腾终于完毕。</font></p>
