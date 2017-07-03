<?php
/*
Plugin Name: Japanese font for TinyMCE
Description: Add Japanese font to TinyMCE Advanced plugin's font family selections..
Version: 1.0
Author: raspi0124
Author URI: https://raspi-diary.com/
License: GPL2
*/

/*  Copyright 2017 raspi0124 (email : admin@raspi-diary.com)

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
*/

//add font to tiny mce
function tinyjpfont_load_custom_fonts($init) {
    $stylesheet_url = plugin_dir_url( __FILE__ ) . 'addfont.css';
    if(empty($init['content_css'])) {
        $init['content_css'] = $stylesheet_url;
    } else {
        $init['content_css'] = $init['content_css'].','.$stylesheet_url;
    }
    $font_formats = isset($init['font_formats']) ? $init['font_formats'] : 'Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats';
    $custom_fonts = ';'.'ふい字=Huifont;Noto Sans Japanese=Noto Sans Japanese;太字なNoto Sans Japanese=Noto Sans Japanese-900;細字なNoto Sans Japanese=Noto Sans Japanese-100;エセナパJ=esenapaj;細字な源ノ角ゴシック=light-gen;源ノ角ゴシック=normal-gen;';
    $init['font_formats'] = $font_formats . $custom_fonts;
    return $init;
}
add_filter('tiny_mce_before_init', 'tinyjpfont_load_custom_fonts');
/**
 * Include CSS file for Plugin.
 */
function tinyjpfont_style() {
    wp_register_style( 'tinyjpfont-styles',  plugin_dir_url( __FILE__ ) . 'addfont.css' );
    wp_enqueue_style( 'tinyjpfont-styles' );
}
add_action( 'wp_enqueue_scripts', 'tinyjpfont_style' );
add_action( 'admin_enqueue_scripts', 'tinyjpfont_style' );

//add 説明書

add_action( 'admin_menu', 'tinyjpfont_create_menu' );
function tinyjpfont_create_menu() {
  add_menu_page( 'Japanese Font for TinyMCEの説明書', 'Japanese Font for TinyMCEの説明書',
    'manage_options', 'tinyjpfont_main_menu', 'tinyjpfont_settings_page' );
  add_action( 'admin_init', 'tinyjpfont_register_settings' );
}


function tinyjpfont_settings_page() {
?>
//ここからhtml表記開始
  <div class="wrap">
   <h2>Japanese Font for TinyMCE 説明書</h2>
<h3>このプラグインについて</h3>
&nbsp;

このプラグインは，日本語フォントをTinyMCE Advanced プラグインのフォント選択画面に追加するプラグインです。
フォントは現在 <span style="font-family: Noto Sans Japanese-100;">Noto Sans Japaneseの細字バージョン <span style="font-family: Noto Sans Japanese;">Noto Sans Japanese <span style="font-family: Noto Sans Japanese-900;">太字なNoto Sans Japanese <span style="font-family: Huifont;">ふい字 <span style="font-family: esenapaj;">エセナパJ <span style="font-family: light-gen;">細字な源ノ角ゴシック <span style="font-family: normal-gen;">源ノ角ゴシック</span></span>
</span></span></span></span></span>をご用意しています。

(ちなみにこのページ，本当は設定画面にしようと思ったのですが，設定することも思いつかなかったので説明書になりましたｗ)
<h3>フォントの説明</h3>
&nbsp;
<h4><span style="font-family: Noto Sans Japanese;">Noto Sans Japanese</span></h4>
<span style="font-family: Noto Sans Japanese;">このフォントは， Google社より提供されているフォントです。日本語フォントの作者は西塚 涼子さんだそうです。 </span>
<span style="font-family: Noto Sans Japanese;">このフォントは，GoogleのCDNより読みこまれています。</span>

&nbsp;
<h4><span style="font-family: Huifont;">ふい字</span></h4>
<span style="font-family: Huifont;">このフォントは，ふい さんが作成された手書きフォントです。</span>

<span style="font-family: Huifont;">このフォントは，rawgit というCDNのサービスで読み込まれています。</span>
<h4><span style="font-family: esenapaj;">エセナパJ</span></h4>
<span style="font-family: esenapaj;">このフォントは，ドッキリなどでよく使えそうな，中華系フォントです。ぶっちゃけ記事に書くにはめっちゃ読みにくいです。。</span>
<blockquote><span style="font-family: esenapaj;">日本語漢字を中国語の漢字字形で表示したり、かな文字をあえて誤字で表示したりしますので、真面目な目的での使用はご遠慮ください。</span></blockquote>
とのことです。

&lt;

&nbsp;

このフォントは，ドッキリなどでよく使えそうな，中華系フォントです。ぶっちゃけ記事に書くにはめっちゃ読みにくいです。。
<blockquote>日本語漢字を中国語の漢字字形で表示したり、かな文字をあえて誤字で表示したりしますので、真面目な目的での使用はご遠慮ください。</blockquote>
とのことです。

&gt;

<span style="font-family: esenapaj;">このフォントは，</span>
<p class="ProfileHeaderCard-name"><a class="ProfileHeaderCard-nameLink u-textInheritColor js-nav" href="https://twitter.com/tanukizamurai"><span style="font-family: esenapaj;">たぬき侍</span> </a><span style="font-family: esenapaj;">さんによって作成されました。</span></p>
&lt;

このフォントは，
<p class="ProfileHeaderCard-name"><a class="ProfileHeaderCard-nameLink u-textInheritColor js-nav" href="https://twitter.com/tanukizamurai">たぬき侍 </a>さんによって作成されました。</p>
&gt;

<span style="font-family: esenapaj;">このフォントは，rawgit というCDNサービスを使用して，読み込んでいます。</span>

&lt;このフォントは，rawgit というCDNサービスを使用して，読み込んでいます。&gt;
<h4><span style="font-family: normal-gen;">源ノ角ゴシック</span></h4>
<span style="font-family: normal-gen;">このフォントは，Adobe社によって作成されたフォントです。</span>

<span style="font-family: normal-gen;">基本的にはNoto Sans Japanese と変わらないらしいですが，自分的にはなんか違う気がします。</span>

<span style="font-family: normal-gen;">このフォントも，rawgit というCDNサービスを使用して読み込んでいます。</span>

&nbsp;
<br>

その他詳しくは<a href="https://raspi-diary.com/wordpress%e3%81%ae%e3%82%a8%e3%83%87%e3%82%a3%e3%82%bf%e3%81%ab%e6%97%a5%e6%9c%ac%e8%aa%9e%e3%83%95%e3%82%a9%e3%83%b3%e3%83%88%e3%82%92%e8%bf%bd%e5%8a%a0%e3%81%99%e3%82%8b%e3%83%97%e3%83%a9%e3%82%b0/">こちら</a>をどうぞ

&nbsp;

&nbsp;

&nbsp;
</div>
<?php
}


