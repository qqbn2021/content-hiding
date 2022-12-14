=== 隐藏内容 ===
Contributors: wyzda2021
Donate link: https://www.ggdoc.cn
Tags: 隐藏内容, 关注可见, 微信引流, 输入密码, 扫码, 微信, 关注, 回复, 关键字, 百家号
Requires at least: 5.0
Requires PHP: 5.3
Tested up to: 6.0
Stable tag: 0.0.2
License: GNU General Public License v2.0 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

支持隐藏文章内容的一部分，用户需要关注微信公众号或百家号才可以查看。

== Description ==

支持隐藏文章内容的一部分，用户需要关注微信公众号或百家号才可以查看。

1、支持多个自媒体平台（不仅仅是微信公众号，百家号也是可以使用的），只要平台支持关键词回复，就可以使用。

2、支持设置二维码图片、自定义关键词、验证码、验证码有效期等。

3、使用js加载技术，静态缓存网站也可以使用。

4、支持登录、回复可见。（商业版）

使用方法：

只需要将隐藏的内容放在[hide]与[/hide]简码之内，就可以隐藏内容。

例如：

[hide]这里放隐藏的内容[/hide]

如果您使用的是经典编辑器，可以直接通过隐藏内容按钮添加要隐藏的内容。


== Installation ==

1. 进入WordPress网站后台，找到“插件-安装插件”菜单；
2. 点击界面左上方的“上传插件”按钮，选择本地提前下载好的插件压缩包文件（zip格式），点击“立即安装”；
3. 安装完成后，启用 “隐藏内容” 插件；
4. 通过“设置”链接进入插件设置界面；
5. 完成设置后，插件就安装完毕了。


== Frequently Asked Questions ==

= 使用本插件有什么作用？ =
主要有2个作用，一是隐藏文章中的部分重要内容，二是给自己的自媒体平台引流。

= 支持哪些自媒体平台？ =
目前已确定支持微信公众号、百家号。其它自媒体平台只要支持关键词自动回复，就可以使用。

= 二维码图片从哪里获取？ =
这个二维码图片就是您的微信公众号的二维码图片，通过扫描二维码，可以直接关注您的微信公众号。

= 为什么要设置关键词回复？ =
这个设置主要是告诉用户，关注微信公众号后，通过发送这个关键词获取验证码，之后将验证码填入就可以查看隐藏的内容。

= 插件中的验证码是什么？ =
这个验证码需要与您的微信公众号发送给用户的验证码一致，才可以验证成功。

= 验证码能否改成密码？ =
可以，只要您愿意，您还可以改成卡密、密钥等之类的词语。

= 商业版有哪些功能？ =
商业版新增了登录、评论可见，免费版只支持关注可见。

= 联系作者 =
如果插件使用出现了问题，或者想要定制功能，可以加QQ：1445023846。

== Screenshots ==

1. 插件设置页面
2. 经典编辑器使用按钮隐藏内容
3. 默认编辑器隐藏内容
4. 默认编辑器使用简码隐藏内容
5. 文章页面显示扫码关注页面
6. 未输入验证码页面
7. 验证码输入不正确页面
8. 验证码输入正确页面

== Upgrade Notice ==

= 0.0.2 =
* 解决了js加载控制台报错问题

= 0.0.1 =
参考Changelog说明

== Changelog ==

= 0.0.2 =
* 改用js加载，静态缓存网站也可以使用

= 0.0.1 =
* 新增隐藏内容简码
* 新增自媒体引流关注功能
* 支持多个自媒体平台