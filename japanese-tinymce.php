<?php
/*
Plugin Name: Japanese font for WordPress (priviously: Japanese Font for TinyMCE)
Description: Add Japanese font to both Gutenberg and TinyMCE Advanced plugin's font family selections.
Version: 4.22
Author: raspi0124
Author URI: https://raspi0124.dev/
License: GPLv2
*/

/*  Copyright 2017-2019 raspi0124 (email : raspi0124@gmail.com)

				This program is free software; you can redistribute it and/or modify
				it under the terms of the GNU General Public License, version 2, as
				published by the Free Software Foundation.

				This program is distributed in the hope that it will be useful,
				but WITHOUT ANY WARRANTY; without even the implied warranty of
				MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
				GNU General Public License for more details.

				You should have received a copy of the GNU General Public License
				along with this program; if not, write to the Free Software
				Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
Copyright  2017  raspi0124

このプログラムはフリーソフトウェアです。あなたはこれを、フリーソフトウェ
ア財団によって発行された GNU 一般公衆利用許諾契約書(バージョン2か、希
望によってはそれ以降のバージョンのうちどれか)の定める条件の下で再頒布
または改変することができます。

このプログラムは有用であることを願って頒布されますが、*全くの無保証*
です。商業可能性の保証や特定の目的への適合性は、言外に示されたものも含
め全く存在しません。詳しくはGNU 一般公衆利用許諾契約書をご覧ください。

あなたはこのプログラムと共に、GNU 一般公衆利用許諾契約書の複製物を一部
受け取ったはずです。もし受け取っていなければ、フリーソフトウェア財団ま
で請求してください(宛先は the Free Software Foundation, Inc., 59
Temple Place, Suite 330, Boston, MA 02111-1307 USA)。

For futrher information about licence, please read LICENCE.txt.
Tinymce版作成において参考にさせていただいた記事:
http://www.de2p.co.jp/tech/wordpress/admin-notices/
http://learn.wpeditpro.com/adding-new-wordpress-tinymce-fonts/
https://nelog.jp/add-quicktags-to-wordpress-text-editor
https://wpdocs.osdn.jp/Settings_API
https://nelog.jp/wordpress-visual-editor-font-size
Gutenberg版で参考になった記事についてははgutenjpfont/gutenjpfont.phpをご覧ください
*/
// define $
$version = "4.22";
//1 is enable, 0 is disable unless written.
// config 1 is CDN
//conbfig 2 is font load mode
//config 3 is enable/disable gutenberg setting
//config 4 is load by header or footer. 0=header, 1=footer
$config1 = get_option('tinyjpfont_check_cdn');
$config2 = get_option('tinyjpfont_select');
$config3 = get_option('tinyjpfont_gutenberg');
$config4 = get_option('tinyjpfont_head');
$config5 = get_option('tinyjpfont_default_font');
$defaultvalue = "0";
$isknown = "";
//Notice



function tinyjpfont_fix422_notice() {
    $user_id = get_current_user_id();
    if ( !get_user_meta( $user_id, 'tinyjpfont_defaultfont_notice_dismissed', 'dismissed' ) )
        echo '<div class="notice notice-info" style="padding:1%;"><strong>Japanese Font for WordPressからのお知らせです!</strong><br>
				Gutenbergでこのプラグインが正常に機能しないバグを修正し、Gutenbergにおけるフォントのロード元をRawGitからjsdelivrに変更しました。<br>
				この場をお借りしてバグを報告してくださった @tomoko_misaki さんにお礼申し上げます。<br>
				このプラグインのバグを発見されましたら@raspi0124(Twitter) または raspi0124@gmail.com までご一報いただけますと幸いです。<br>
				今回はご迷惑をおかけしすみませんでした。
				<br><a href="?tinyjpfont-fix422-notice-dismissed=true">Dismiss(この通知を消す)</a></div>';
}
add_action( 'admin_notices', 'tinyjpfont_fix422_notice' );

function tinyjpfont_fix422_notice_dismissed() {
    $user_id = get_current_user_id();
    if ( isset( $_GET['tinyjpfont-fix422-notice-dismissed'] ) )
        add_user_meta( $user_id, 'tinyjpfont_fix422_notice_dismissed', 'true', true );
}
add_action( 'admin_init', 'tinyjpfont_fix422_notice_dismissed' );




// setting <Version 3.5-beta3>

//もしCDNがtrueでフォントロードモードもNormalだったら
if ($config1 == "1" and $config2 == "0") {
		// enque CSS at CDN
		function tinyjpfont_style()
		{
				wp_register_style('tinyjpfont-styles', 'https://cdn.jsdelivr.net/gh/raspi0124/Japanese-font-for-TinyMCE@stable/addfont.css');
				wp_enqueue_style('tinyjpfont-styles');
		}
		//もしheader読み込みだったら
		if ($config4 ==  "0") {
				add_action('wp_enqueue_scripts', 'tinyjpfont_style');
				add_action('admin_enqueue_scripts', 'tinyjpfont_style');
		} else {
				add_action('get_footer', 'tinyjpfont_style');
				add_action('admin_enqueue_scripts', 'tinyjpfont_style');
		}
}
//もしCDNがtrueでフォントロードモードがLiteだったら
if ($config1 == "1" and $config2 == "1") {
		// enque Lite version of CSS at CDNs
		function tinyjpfont_style()
		{
				wp_register_style('tinyjpfont-styles', 'https://cdn.jsdelivr.net/gh/raspi0124/Japanese-font-for-TinyMCE@stable/addfont_lite.css');
				wp_enqueue_style('tinyjpfont-styles');
		}
		if ($config4 ==  "0") {
				add_action('wp_enqueue_scripts', 'tinyjpfont_style');
				add_action('admin_enqueue_scripts', 'tinyjpfont_style');
		} else {
				add_action('get_footer', 'tinyjpfont_style');
				add_action('admin_enqueue_scripts', 'tinyjpfont_style');
		}
}
//もしCDNがfalseでフォントロードモードがLiteだったら
if ($config1 == "0" and $config2 == "1") {
		function tinyjpfont_style()
		{
				wp_register_style('tinyjpfont-styles', plugin_dir_url(__FILE__) . 'addfont_lite.css');
				wp_enqueue_style('tinyjpfont-styles');
		}
		if ($config4 ==  "0") {
				add_action('wp_enqueue_scripts', 'tinyjpfont_style');
				add_action('admin_enqueue_scripts', 'tinyjpfont_style');
		} else {
				add_action('get_footer', 'tinyjpfont_style');
				add_action('admin_enqueue_scripts', 'tinyjpfont_style');
		}
}
if ($config1 == "0" and $config2 == "0") {
		//もしCDNがFalseでロードモードがNormalだったら
		function tinyjpfont_style()
		{
				wp_register_style('tinyjpfont-styles', plugin_dir_url(__FILE__) . 'addfont.css');
				wp_enqueue_style('tinyjpfont-styles');
		}
		if ($config4 ==  "0") {
				add_action('wp_enqueue_scripts', 'tinyjpfont_style');
				add_action('admin_enqueue_scripts', 'tinyjpfont_style');
		} else {
				add_action('get_footer', 'tinyjpfont_style');
				add_action('admin_enqueue_scripts', 'tinyjpfont_style');
		}
}

//add gutenberg support.
if ($config3 == "1") {
		include(plugin_dir_path(__FILE__) . 'gutenjpfont/gutenjpfont.php');
} else {
}


function tinyjpfont_get_custom_fonts()
{
	$config2 = get_option('tinyjpfont_select');
		if (!isset($config2)) {
				$config2 = "0";
		}
		//add font to tiny mce
		if ($config2 == "0") {
				$custom_fonts = ';'.'ふい字=Huifont;Noto Sans Japanese=Noto Sans Japanese;太字なNoto Sans Japanese=Noto Sans Japanese-900;細字なNoto Sans Japanese=Noto Sans Japanese-100;エセナパJ=esenapaj;ほのか丸ゴシック=honokamaru;こころ明朝体=kokorom;青柳衡山フォントT=aoyanagiT;たぬき油性マジック=tanukiM';
		} else {
				$custom_fonts = ';'.'ふい字=Huifont;Noto Sans Japanese=Noto Sans Japanese;';
		}
		return $custom_fonts;
}

$seted_custom_fonts = tinyjpfont_get_custom_fonts();

function tinyjpfont_load_custom_fonts($init)
{
		$stylesheet_url = plugin_dir_url(__FILE__) . 'addfont.css';
		if (empty($init['content_css'])) {
				$init['content_css'] = $stylesheet_url;
		} else {
				$init['content_css'] = $init['content_css'].','.$stylesheet_url;
		}
		global $seted_custom_fonts;
		$custom_fonts = $seted_custom_fonts;

		if (!isset($custom_fonts)) {
			$custom_fonts = ';'.'ふい字=Huifont;Noto Sans Japanese=Noto Sans Japanese';
		}
		$font_formats = isset($init['font_formats']) ? $init['font_formats'] : 'Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings';
		$init['font_formats'] = $font_formats . $custom_fonts;
		return $init;

		add_filter('tiny_mce_before_init', 'tinyjpfont_load_custom_fonts');
}
add_action('tiny_mce_before_init', 'tinyjpfont_load_custom_fonts');


add_filter('tiny_mce_before_init', function ($settings) {
		//フォントサイズの指定
		$settings['fontsize_formats'] =
						'10px 12px 14px 16px 18px 20px 24px 28px 32px 36px 42px 48px';

		return $settings;
});

//also add some font size selecting function for non-tinymce-advanced user.
//https://nelog.jp/wordpress-visual-editor-font-size
add_filter('mce_buttons', function ($buttons) {
		array_push($buttons, 'fontsizeselect');
		return $buttons;
});
//finish


//add font selection to quicktag also<alpha>
//http://webtukuru.com/web/wordpress-quicktag/
//https://wpdocs.osdn.jp/%E3%82%AF%E3%82%A4%E3%83%83%E3%82%AF%E3%82%BF%E3%82%B0API
function tinyjpfont_quicktag()
{
		//スクリプトキューにquicktagsが保存されているかチェック
		if (wp_script_is('quicktags')) {?>
		<script>
			QTags.addButton('tinyjpfont-noto','Noto Sans Japanese','<span style="font-family: Noto Sans Japanese;">','</span>');
			QTags.addButton('tinyjpfont-huiji','ふい字','<span style="font-family: Huifont;">','</span>');
		</script>
	<?php
		}
}
add_action('admin_print_footer_scripts', 'tinyjpfont_quicktag');



//add font selector to TinyMCE also. no more TinyMCE Advanced plugin

add_filter('tiny_mce_before_init', 'tinyjpfont_custom_tiny_mce_style_formats');
function tinyjpfont_custom_tiny_mce_style_formats($settings)
{
		$style_formats = array(
				array(
						'title' => 'Noto Sans Japanese',
						'block' => 'div',
						'classes' => 'noto',
						'wrapper' => true,
				),
				array(
						'title' => 'Huifont',
						'block' => 'div',
						'classes' => 'huiji',
						'wrapper' => true,
				),
		);
		$settings[ 'style_formats' ] = json_encode($style_formats);
		return $settings;
}

add_filter('mce_buttons', 'tinyjpfont_add_original_styles_button');
function tinyjpfont_add_original_styles_button($buttons)
{
		array_splice($buttons, 1, 0, 'fontselect');
		return $buttons;
}

//DEFAULT FONT
function tinyjpfont_getdefaultfonturl(){
	$config5 = get_option('tinyjpfont_default_font');
	$fontname = $config5;
	if (!isset($config5) || $config5 != "") {
		$defaultfont_url = plugin_dir_url(__FILE__) . "default-font-css.php?fn=" . $fontname;
		return $defaultfont_url;
	}else{
	$defaultfont_url = plugin_dir_url(__FILE__) . "default-font-css.php?fn=" . $fontname;
	return $defaultfont_url;
}
}
function tinyjpfont_add_default_font() {
	$defaultfont_url = tinyjpfont_getdefaultfonturl();
	add_editor_style( $defaultfont_url );
	wp_register_style('tinyjpfont-default-font', $defaultfont_url);
	wp_enqueue_style('tinyjpfont-default-font');
}
add_action( 'init', 'tinyjpfont_add_default_font' );


//ADD OPTION


// 管理メニューにフックを登録
add_action('admin_menu', 'tinyjpfont_add_pages');

// メニューを追加する
function tinyjpfont_add_pages()
{
        $tinyjpfont_plugin_slug = "tinyjpfont";

		// トップレベルにオリジナルのメニューを追加
		add_menu_page(
				'Japanese Font for WordPressの設定',
				'Japanese Font for WordPressの設定',
				'manage_options',
				$tinyjpfont_plugin_slug,
				'tinyjpfont_options_page',
				plugins_url('icon.png', __FILE__)
				);
}

// メニューで表示されるページの内容を返す関数
function tinyjpfont_options_page()
{
		// POSTデータがあれば設定を更新
		if (isset($_POST['tinyjpfont_select'])) {
				update_option('tinyjpfont_select', $_POST['tinyjpfont_select']);
				update_option('tinyjpfont_head', $_POST['tinyjpfont_head']);
				update_option('tinyjpfont_default_font', $_POST['tinyjpfont_default_font']);
				// チェックボックスはチェックされないとキーも受け取れないので、ない時は0にする
				$tinyjpfont_check_cdn = isset($_POST['tinyjpfont_check_cdn']) ? 1 : 0;
				update_option('tinyjpfont_check_cdn', $tinyjpfont_check_cdn);

				$tinyjpfont_check_noto = isset($_POST['tinyjpfont_check_noto']) ? 1 : 0;
				update_option('tinyjpfont_check_noto', $tinyjpfont_check_noto);

				$tinyjpfont_gutenberg = isset($_POST['tinyjpfont_gutenberg']) ? 1 : 0;
				update_option('tinyjpfont_gutenberg', $tinyjpfont_gutenberg);
		} ?>
</head>
<body>

<div id="wrap">

<div id="nav">
Japanese Font for WordPressの情報についてはTwitterにて#tinyjpfontのハッシュタグでたまーにツイートしています。<br>
あとよろしければ <a href="https://twitter.com/raspi0124">作者のTwitter</a>もフォローお願いします!<br><br>
なお、このプラグインの次を決める <a href="https://docs.google.com/forms/d/e/1FAIpQLSd_PLkuRGr-NcXQ1Jq36xru73WvvbmyCm0QjFH92pJ14yQQjQ/viewform?usp=send_form">アンケートフォーム</a>も公開中！よろしければ要望等どうぞ！<br>
バグ等発見されましたらraspi0124<@>gmail.comかTwitter(@raspi0124)までお願いいたします。

</div>
	<h2>Japanese Font for WordPress</h2>
	<h3>Japanese Font for WordPressからのお知らせ:<br>Japanese Font for WordPressは今までCSSや一部のフォントの配信に使用していたRawgitのサービス終了に伴いjsdelivrからの配信に切り替えたためこれをお知らせします。<br>詳しくは<a href="https://raspi-diary.com/post-4241/">こちら</a>をご覧ください。</h3>
	 <link rel="stylesheet" href= "https://cdn.jsdelivr.net/gh/raspi0124/Japanese-font-for-TinyMCE@stable/admin.css">
<div id="content">
	<?php
						// 更新完了を通知
						if (isset($_POST['tinyjpfont_select'])) {
								echo '<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible">
							<p><strong>設定を保存しました。</strong></p></div>';
						} ?>
	<form method="post" action="">
			<tr>
					<th scope="row"><h3><label for="tinyjpfont_select">フォントロードモード</label></h3></th><br>
					<td>
							<select name="tinyjpfont_select" id="tinyjpfont_select">
									<option value="0" <?php selected(0, get_option('tinyjpfont_select')); ?> >フォントロードNormal</option>
									<option value="1" <?php selected(1, get_option('tinyjpfont_select')); ?> >フォントロードLite</option>
							</select>
					</td>
			</tr><br>
			<strong>
			フォントロードNormalは指定したフォントを読み込みます。Liteを指定した場合最低限のフォント(ふい字、Noto Sans Japanese)のみ読み込まれるようになります。
		</strong>
			<tr>
					<th scope="row"><label for="tinyjpfont_check_cdn"><h3>CDNモード (CSSもCDNから読み込むようになります)</h3></label></th><br>
					<td><label><input name="tinyjpfont_check_cdn" type="checkbox" id="tinyjpfont_check_cdn" value="1" <?php checked(1, get_option('tinyjpfont_check_cdn')); ?> /> CSSをCDNから読み込む</label></td><br>
			</tr>
			<strong>CDNはjsdelivrという無料サービスを使用しています。日本国内でのロード速度に自信があるようでしたらチェックボックスはオフにしましょう</strong>
			<tr>
				<th scope="row">
					<label for="tinyjpfont_head"><h3>読み込み場所指定モード</h3></label></th><br>
					<td>
							<select name="tinyjpfont_head" id="tinyjpfont_head">
									<option value="0" <?php selected(0, get_option('tinyjpfont_head')); ?> >ヘッダーで読み込む</option>
									<option value="1" <?php selected(1, get_option('tinyjpfont_head')); ?> >フッターで読み込む</option>
							</select>
					</td>
				</th>
				<br><strong>テーマの仕様により対応していない場合もあります。</strong>
			</tr><br>
			<tr>
				<th scope="row"><label for="tinyjpfont_gutenberg"><h3>Gutenberg対応モード(beta)</h3></label></th><br>
					<td><label><input name="tinyjpfont_gutenberg" type="checkbox" id="tinyjpfont_gutenberg" value="1" <?php checked(1, get_option('tinyjpfont_gutenberg')); ?> /> Gutenbergに対応させる(beta)</label></td><br>
		</tr><br>
			<strong>
			Gutenberg対応機能はNoto Sans Japaneseとふい字フォントのみ現在サポートしています。
		</strong>
		<tr>
			<th scope="row"><label for="tinyjpfont_default_font"><h3>デフォルトフォント(beta)</h3></label></th><br>
				<td>
					<select name="tinyjpfont_default_font" id="tinyjpfont_default_font">
							<option value="Noto Sans Japanese" <?php selected("noto", get_option('tinyjpfont_default_font')); ?> >Noto Sans Japanese</option>
							<option value="Huifont" <?php selected("Huifont", get_option('tinyjpfont_default_font')); ?> >ふい字</option>
							<option value="kokorom" <?php selected("kokorom", get_option('tinyjpfont_default_font')); ?> >こころ明朝体</option>
					</select>
				</td>
			</th>
		</tr>
	<br>
	</table>
	<?php submit_button(); ?>
	</form>

</div>



</div>

</body>
</html>

<?php
}
