=== CheckBot ===
Contributors: ref
Donate link: http://deadblog.ru/
Tags: comment, spam, captcha, antispam, anti-spam, match captcha, capcha, catcha, captha
Requires at least: 2.7.2
Tested up to: 4.0
Stable tag: 1.05
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This antispam plugin provides very simple(one-click) and powerful captcha for your blog.

== Description ==

CheckBot — simple one-click captcha-plugin created for people. It is very easy to use, and look nice, but at the same time, fully customizable, and quite seriously protect your blog from spam.


= Features =

* Antispam: The plugin uses javascript and randomly generates identifiers and field names, which allows the plugin to automatically filter out fine sent spam.
* Support languages: The plugin supports Russian and English languages.
* Fully customizable appearance.
* The plugin can be used immediately after installation, without additional configuration.
* You can configure the output plugin yourself anywhere on the page comments.
* If the user is not the correct picture, or even forgot to select it, the plugin will tell him about it, and, just in case it will write a comment.


== Installation ==

Follow the steps below to install the plugin.

= Easy install: =
1. Extract the plugin in wp-content/plugins/checkbot.
2. Activate the plugin in the admin panel page "Plugins".

= Advanced install: =
1. Extract the plugin in wp-content/plugins/checkbot.
2. Activate the plugin in the admin panel page "Plugins".
3. Go to the admin panel settings page CheckBot'a.
4. Set on "The method of connecting plugin:" - "Manual".
5. Edit the template file "comments.php", in a place where we want to bring plug-insert the line:
   `<?php if (function_exists (checkbot_show)) { checkbot_show (); } ?>`

== Help ==

You may ask questions at the plugin page - <a href="http://deadblog.ru/checkbot">http://deadblog.ru/checkbot/</a>, or write me e-mail - lifeiscoming@gmail.com.

== Screenshots ==

1. CheckBot.
2. CheckBot in work :)
3. Settings.

== Upgrade Notice ==

Download and replace old files.

== Frequently Asked Questions ==

= How i can create image pack ? =

It's easy. Create a folder in the directory 'images'. Put into 3 files with images in the format .jpg, whose names are 1, 2, 3 (like 1.jpg, 2.jpg, 3.jpg). The picture with the correct answer must be named 1.jpg. Create a style sheet style.css file and issue text.xx.txt, where xx - language (en - English, ru - Russian).

== Changelog ==

= 1.05 =
* Upg: update compatible to 4.0.

= 1.04 =
* Fix: now if selected image wrong, comment delete from trash;
* Fix: minor code fix.

= 1.03 =
* Add: now plugin support Wordpress 3.5
* Fix: minor code fix.
* Fix: bug with upper case plugin file name.

= 1.02 =

* Add: link to Wordpress Plugin Page.
* Add: new minor capabilities for further development.
* Fix: minor code fix.
* Fix: fix bug when a person is mistaken for a bot.

= 1.01 =

* Fix: error with not the right path to script.
* Fix: minor code fix.
* Improve: to improve the security, all data transmitted hidden (when viewing the source code for the page is no longer the right answer).

= 1.0 =

* First official version.