<?php
/*
Plugin Name: CheckBot
Plugin URI: http://deadblog.ru/checkbot/
Description: Simple anti-spam(CAPTCHA) plugin for humans.
Author: Constantine Anikin.
Version: 1.04
Author URI: http://deadblog.ru/
*/

/*  Copyright 2014 Constantine Anikin (email: lifeiscoming@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

add_action('admin_menu',    'cbt_add_menu');       // adding settings at admin panel.
add_action('comment_post',  'comment_post');       // set hook for comment.

add_option('cbt_imgPack',   'Default', '', 'yes'); // add option for change image pack's.
add_option('cbt_language',  'en',      '', 'yes'); // add option for change language.
add_option('cbt_hookup',    'auto',    '', 'yes'); // add option for change hookup method.
add_option('cbt_copyright', 'link',    '', 'yes'); // add option for change copyright show method.
add_option('cbt_version',   '1.03',    '', 'yes'); // version.

// Auto-insert captcha in comment form.
if (get_option('cbt_hookup') == 'auto')
	add_action('comment_form', 'checkbot_show');

if (get_option('cbt_version') != '1.05') 
{
	update_option( 'cbt_version', '1.05' );
	update_option( 'cbt_copyright', 'link' );
}

$language = array(
	'en' => array(
		'lang'                   => 'English',
		'set_update'             => 'Settings updated.',
		'set_img_pack'           => 'Images pack: ',
		'set_language'           => 'Language: ',
		'set_thanks'             => 'Thank you for using my plugin :)',
		'set_hookup'             => 'The method of connecting plugin: ',
		'set_hookup_auto'        => 'Auto',
		'set_hookup_manual'      => 'Manual',
		'set_hookup_manual_info' => 'In the manual method, you need to insert the following line in the template file comments.php:',
		'set_copyright'          => 'The method of copyright out: ',
		'set_copyright_link'     => 'Link',
		'set_copyright_text'     => 'Text',
		'set_copyright_none'     => 'Off',
		'set_save'               => 'Update Options',
		'set_secret'             => 'Secret Key:',
		'err_msg'                => 'Please, if you\'re not a bot, go back and confirm it.',
		'err_comment'            => 'Your comment: ',
		'err_back'               => 'Go back'
	),
	'ru' => array(
		'lang'                   => 'Русский',
		'set_update'             => 'Настройки обновлены.',
		'set_img_pack'           => 'Набор картинок: ',
		'set_language'           => 'Язык: ',
		'set_thanks'             => 'Спасибо за то, что пользуетесь моим плагином :)',
		'set_hookup'             => 'Метод подключения плагина: ',
		'set_hookup_auto'        => 'Автоматически',
		'set_hookup_manual'      => 'Вручную',
		'set_hookup_manual_info' => 'В ручном методе, вам нужно вставить следующую строчку в файл шаблона comments.php:',
		'set_copyright'          => 'Метод вывода копирайта: ',
		'set_copyright_link'     => 'Ссылка',
		'set_copyright_text'     => 'Текст',
		'set_copyright_none'     => 'Отключить',
		'set_save'               => 'Сохранить Настройки',
		'set_secret'             => 'Секретный ключ:',
		'err_msg'                => 'Пожалуйста, если вы не бот, вернитесь и подтвердите это.',
		'err_comment'            => 'Ваш комментарий: ',
		'err_back'               => 'Вернуться'
	)
);

// Settings page.
function cbt_options_page()
{
	global $language, $secret;
	$lng = $language[get_option('cbt_language')]; // language initialize.

	// update settings.
	if ($_POST['cbt_imgPack'])
	{
		update_option('cbt_imgPack',   $_POST['cbt_imgPack']);
		update_option('cbt_language',  $_POST['cbt_language']);
		update_option('cbt_hookup',    $_POST['cbt_hookup']);
		update_option('cbt_copyright', $_POST['cbt_copyright']);

		$lng = $language[get_option('cbt_language')];
		echo '<div class="updated"><p>'.$lng['set_update'].'</p></div>';
	} ?>

<!-- show settings. -->
<div class="wrap">
	<h2>CheckBot Plugin Options</h2>

	<div style="width:824px;">
		<div style="float:left;background-color:white;padding:10px 10px 10px 10px;margin-right:15px; margin-bottom:15px; border:1px solid #ddd;">
			<div style="width:350px;height:80px;">
				<h3>CheckBot v1.05</h3>
				<a href="http://wordpress.org/extend/plugins/checkbot/" target="_blank">Wordpress Plugin Page</a>

				<p><?php echo $lng['set_thanks']; ?></p>
			</div>
		</div>
	</div>
	<br/><br/>

	<form action="" method="post">
		<table border="0" width="100%" class="form-table">
			<tr><!-- language change. -->
				<td width="17%" align="right" valign="top">
					<?php echo $lng['set_language']; ?>
				</td>
				<td width="83%" align="left" valign="top">
					<select name="cbt_language" style="width:150px;">
						<?php foreach ($language as $key => $value) { ?>
						<option value="<?php echo $key; ?>" <? if ($key == get_option('cbt_language')) {
							echo ' selected ';
						} ?>><?php echo $value['lang']; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr><!-- image pack's change. -->
				<td width="17%" align="right" valign="top">
					<?php echo $lng['set_img_pack']; ?>
				</td>
				<td width="83%" align="left" valign="top">
					<?php echo '<select name="cbt_imgPack" style="width:150px;">';
					$url = '../wp-content/plugins/checkbot/images';
					if (is_dir($url)) 
					{
						if ($dir = opendir($url)) 
						{
							while (FALSE !== ($file = readdir($dir))) 
							{
								if ($file != "." && $file != "..") 
								{?>
									<option value="<?php echo $file ?>" <?php if ($file == get_option('cbt_imgPack')) echo 'selected'; ?>><?php echo $file . '</option>';
								}
							}
							closedir($dir);
						}
					} ?>
					</select>
				</td>
			</tr>
			<tr><!-- hookup method change. -->
				<td width="17%" align="right" valign="top">
					<?php echo $lng['set_hookup']; ?>
				</td>
				<td width="83%" align="left" valign="top">
					<?php $cbt_hookup = get_option( 'cbt_hookup' ); ?>
					<input type="radio" name="cbt_hookup"
						   value="auto" <?php if ($cbt_hookup == 'auto') echo ' checked '; ?> > <?php echo $lng['set_hookup_auto']; ?>
					<br/>
					<input type="radio" name="cbt_hookup"
						   value="manual" <?php if ($cbt_hookup == 'manual') echo ' checked '; ?> > <?php echo $lng['set_hookup_manual']; ?>
					<br/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<small><?php echo $lng['set_hookup_manual_info']; ?><br/>
						&nbsp;&nbsp;&nbsp;&nbsp;<code><?php echo htmlspecialchars("<?php if(function_exists(checkbot_show)) { checkbot_show(); } ?>" ); ?></code>
					</small>
				</td>
			</tr>
			<tr><!-- hookup method change. -->
				<td width="17%" align="right" valign="top">
					<?php echo $lng['set_copyright']; ?>
				</td>
				<td width="83%" align="left" valign="top">
					<?php $cbt_copyright = get_option('cbt_copyright'); ?>
					<input type="radio" name="cbt_copyright"
						   value="link" <?php if ($cbt_copyright == 'link') echo ' checked '; ?> > <?php echo $lng['set_copyright_link']; ?>
					(captcha by <a href="http://deadblog.ru/" target="_blank">deadblog.ru</a>)<br/>
					<input type="radio" name="cbt_copyright"
						   value="text" <?php if ($cbt_copyright == 'text') echo ' checked '; ?> > <?php echo $lng['set_copyright_text']; ?>
					(captcha by deadblog.ru)<br/>
					<input type="radio" name="cbt_copyright"
						   value="none" <?php if ($cbt_copyright == 'none') echo ' checked '; ?> > <?php echo $lng['set_copyright_none']; ?>
					<br/>
				</td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php echo $lng['set_save']; ?> &raquo;"/>
		</p>
	</form>
</div>
<?php
}

// add link into admin panel.
function cbt_add_menu()
{ 
	add_options_page('CheckBot', 'CheckBot', 8, 'CheckBot.php', 'cbt_options_page');
}

// function for generation random session names.
function rnd ()
{
	$length   = 10;
	$chars    = 'qwertyuiopasdfghjklzxcvbnm';
	$numChars = strlen($chars);
	$string   = '';

	for ($i = 0; $i < $length; $i++) 
	{
		$string .= substr($chars, rand(1, $numChars) - 1, 1);
	}

	return $string;
}

session_start();

// generate random names for curent session.
if (!isset($_SESSION['cbt_session']) or !isset($_SESSION['selected'])) 
{ 
	$_SESSION['cbt_session'] = rnd();
	$_SESSION['first']       = rnd();
	$_SESSION['second']      = rnd();
	$_SESSION['third']       = rnd();
	$_SESSION['selected']    = rnd();
}

// posting comment.
function comment_post($id)
{ 
	global $user_ID;
	global $language;
	$lng = $language[get_option('cbt_language')];

	if ($user_ID)
		return $id;

	if ($_POST[$_SESSION['selected']] != $_SESSION['true'])
	{
		wp_delete_comment($id, true);
		echo '<br /><center>' . $lng['err_msg'] . ' <br />';
		echo '<br /><u>' . $lng['err_comment'] . '</u><br />' . $_POST['comment'];
		echo '<br /><br /><a href="javascript:history.back()">' . $lng['err_back'] . '</a></center>';
		exit;
	}
}

// show CheckBot.
function checkbot_show()
{
	$cbt_imgPack   = get_option('cbt_imgPack');
	$cbt_hookup    = get_option('cbt_hookup');
	$lng           = get_option('cbt_language');
	$cbt_copyright = get_option('cbt_copyright');
	$cbt_version   = get_option('cbt_version');

	// images path.
	$url_1 = '/wp-content/plugins/checkbot/images/' . $cbt_imgPack . '/1.jpg';
	$url_2 = '/wp-content/plugins/checkbot/images/' . $cbt_imgPack . '/2.jpg';
	$url_3 = '/wp-content/plugins/checkbot/images/' . $cbt_imgPack . '/3.jpg';

	// add images in array and shuffle.
	$array = array($url_1, $url_2, $url_3);
	shuffle($array);

	$img_1 = $array[0];
	$img_2 = $array[1];
	$img_3 = $array[2];

	// find true image in array.
	$i = 0;

	while ( $array[$i] != $url_1 ) {
		$i++;
	}

	$_SESSION['true'] = $i + 1; // true image
	?>
<style type="text/css">
		<?php include( ABSPATH . '/wp-content/plugins/checkbot/images/' . $cbt_imgPack . '/style.css' ); ?>
</style>

<script>
		<?php include( ABSPATH . '/wp-content/plugins/checkbot/script.js' ); ?>
</script>

<div id="CheckBot">
	<p id="text"><?php include( ABSPATH . '/wp-content/plugins/checkbot/images/' . $cbt_imgPack . '/text.' . $lng . '.txt' ); ?></p>
	<img src="<?php echo $img_1; ?>" class="border_n" onclick="javascript: reselect(1)" border="0"
		 id="<?php echo $_SESSION['first']; ?>"/>
	<img src="<?php echo $img_2; ?>" class="border_n" onClick="javascript: reselect(2)" border="0"
		 id="<?php echo $_SESSION['second']; ?>"/>
	<img src="<?php echo $img_3; ?>" class="border_n" onClick="javascript: reselect(3)" border="0"
		 id="<?php echo $_SESSION['third']; ?>"/>
	<?php
	if ($cbt_copyright == 'text') echo '<div id="copyright"><abbr title="' . $cbt_version . '">captcha</abbr> by deadblog.ru</div>';
	if ($cbt_copyright == 'link') echo '<div id="copyright"><abbr title="' . $cbt_version . '">captcha</abbr> by <a href="http://deadblog.ru/" target="_blank">deadblog.ru</a></div>';
	?>
</div>

<input type="hidden" value="<?php echo $_SESSION['cbt_session']; ?>" name="<?php echo $_SESSION['cbt_session']; ?>"/>
<input type="hidden" value="" name="<?php echo $_SESSION['selected']; ?>" id="<?php echo $_SESSION['selected']; ?>"/>

<?php
	if ($cbt_hookup == 'auto') 
	{
		echo '<script>';
		echo 'var commentField = document.getElementById("url");';
		echo 'var submitp = commentField.parentNode;';
		echo 'var answerDiv = document.getElementById("CheckBot");';
		echo 'submitp.appendChild(answerDiv, commentField);';
		echo '</script>';
	}
}

?>