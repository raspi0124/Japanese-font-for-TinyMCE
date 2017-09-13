<?php
/*
Plugin Name: Japanese font for TinyMCE
Description: Add Japanese font to TinyMCE Advanced plugin's font family selections..
Version: 2.20
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
// define $
$config1 = 0;

//add font to tiny mce
function tinyjpfont_load_custom_fonts($init) {
    $stylesheet_url = plugin_dir_url( __FILE__ ) . 'addfont.css';
    if(empty($init['content_css'])) {
        $init['content_css'] = $stylesheet_url;
    } else {
        $init['content_css'] = $init['content_css'].','.$stylesheet_url;
    }
    $font_formats = isset($init['font_formats']) ? $init['font_formats'] : 'Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats';
    $custom_fonts = ';'.'ふい字=Huifont;Noto Sans Japanese=Noto Sans Japanese;太字なNoto Sans Japanese=Noto Sans Japanese-900;細字なNoto Sans Japanese=Noto Sans Japanese-100;エセナパJ=esenapaj;ほのか丸ゴシック=honokamaru;';
    $init['font_formats'] = $font_formats . $custom_fonts;
    return $init;
}
add_filter('tiny_mce_before_init', 'tinyjpfont_load_custom_fonts');


// setting <Version alpha>

if( $config1 == 1 ){

// enque CSS at CDN
function tinyjpfont_style() {
    wp_register_style( 'tinyjpfont-styles', 'https://cdn.rawgit.com/raspi0124/Japanese-font-for-TinyMCE/stable/addfont.css' );
    wp_enqueue_style( 'tinyjpfont-styles' );
}
add_action( 'wp_enqueue_scripts', 'tinyjpfont_style' );
add_action( 'admin_enqueue_scripts', 'tinyjpfont_style' );
}

else{

function tinyjpfont_style() {
    wp_register_style( 'tinyjpfont-styles',  plugin_dir_url( __FILE__ ) . 'addfont.css' );
    wp_enqueue_style( 'tinyjpfont-styles' );
}
add_action( 'wp_enqueue_scripts', 'tinyjpfont_style' );
add_action( 'admin_enqueue_scripts', 'tinyjpfont_style' );
}

//add 説明書

add_action( 'admin_menu', 'tinyjpfont_create_menu' );
function tinyjpfont_create_menu() {
  add_menu_page( 'Japanese Font for TinyMCEの説明書', 'Japanese Font for TinyMCEの説明書',
    'manage_options', 'tinyjpfont_main_menu', 'tinyjpfont_settings_page' );
}


function tinyjpfont_settings_page() {
?>
  <div class="wrap">


<!-- コンテナ開始 -->
<div id="tinyjpfont_container">
<!-- ヘッダ開始 -->
<div id="tinyjpfont_header">
<h2>Japanese Font for TinyMCE 説明書</h2>
<style>/* --- 全体の背景・テキスト --- */
body {
margin: 0;
padding: 0;
background-color: #ffffff; /* ページの背景色 */
color: #000000; /* 全体の文字色 */
font-size: 100%; /* 全体の文字サイズ */
}
/* --- 全体のリンクテキスト --- */
a:link { color: #0000ff; }
a:visited { color: #800080; }
a:hover { color: #ff0000; }
a:active { color: #ff0000; }
/* --- コンテナ --- */
#tinyjpfont_container {
width: 780px; /* ページの幅 */
margin: 0 auto; /* センタリング */
background: url(sidebar_200.gif) repeat-y right; /* サイドバーの背景画像 */
background-color: #ffffff; /* メインカラムの背景色 */
border-left: 1px #c0c0c0 solid; /* 左の境界線 */
border-right: 1px #c0c0c0 solid; /* 右の境界線 */
}
/* --- ナビゲーション --- */
#tinyjpfont_nav {
float: right;
width: 200px; /* サイドバーの幅 */
}
/* --- メインカラム --- */
#tinyjpfont_content {
float: right;
width: 580px; /* メインカラムの幅 */
}
/* --- フッタ --- */
#tinyjpfont_footer {
clear: right; /* フロートのクリア */
width: 100%;
background-color: #ffe080; /* フッタの背景色 */
}
blockquote{
background-color:#ddd;
padding:3em 1em;
position:relative;
}
blockquote:before{
content:"“";
font-size:600%;
line-height:1em;
color:#999;
position:absolute;
left:0;
top:0;
}
blockquote:after{
content:"”";
font-size:600%;
line-height:0em;
color:#999;
position:absolute;
right:0;
bottom:0;
}
</style>
</div>
<!-- ヘッダ終了 -->

<!-- ナビゲーション開始 -->
<div id="tinyjpfont_nav">
作者のTwitterをフォロー<br>
<a href="https://twitter.com/raspi0124" class="twitter-follow-button" data-show-count="false" data-size="large" data-dnt="true">Follow @raspi0124</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script><br><br>
<br>このプラグインをフォロワーさんにおすすめしてくださると嬉しいです。<br>
<a href="https://twitter.com/share" class="twitter-share-button" data-url="https://rasdi.cf/2qGvgdl" data-text="Japanese Font for TinyMCEを使ってます。このプラグインおすすめします。" data-via="raspi0124" data-size="large" data-dnt="true">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script><br>

よろしければ寄付をおねがいします。<br>
Monacoin address : MG2vzkSguWscQp3haGJ4kkhJFePvkSgKsU <br>
<img src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&l=9&chld=M|1&chl=monacoin:MG2vzkSguWscQp3haGJ4kkhJFePvkSgKsU"><br>

</div>
<!-- ナビゲーション終了 -->

<!-- メインカラム開始 -->
<div id="tinyjpfont_content">
<h3>このプラグインについて</h3>
&nbsp;
&nbsp;

このプラグインは，日本語フォントをTinyMCE Advanced プラグインのフォント選択画面に追加するプラグインです。
フォントは現在 <span style="font-family: Noto Sans Japanese-100;">Noto Sans Japaneseの細字バージョン <span style="font-family: Noto Sans Japanese;">Noto Sans Japanese <span style="font-family: Noto Sans Japanese-900;">太字なNoto Sans Japanese <span style="font-family: Huifont;">ふい字 <span style="font-family: esenapaj;">エセナパJ <span style="font-family: honokamaru;"> ほのか丸ゴシック
</span></span></span></span></span></span>

をご用意しています。

なお、このプラグインについての詳しい説明は <a href="https://raspi-diary.com/wordpress%e3%81%ae%e3%82%a8%e3%83%87%e3%82%a3%e3%82%bf%e3%81%ab%e6%97%a5%e6%9c%ac%e8%aa%9e%e3%83%95%e3%82%a9%e3%83%b3%e3%83%88%e3%82%92%e8%bf%bd%e5%8a%a0%e3%81%99%e3%82%8b%e3%83%97%e3%83%a9%e3%82%b0/">こちら</a> をご覧ください。

(ちなみにこのページ，本当は設定画面にしようと思ったのですが，設定することも思いつかなかったので説明書になりましたｗ)
<h3>フォントの説明</h3>
&nbsp;
<h4><a href="https://www.google.com/get/noto/"><span style="font-family: Noto Sans Japanese;">Noto Sans Japanese</span></a></h4>
<span style="font-family: Noto Sans Japanese;">このフォントは， Google社より提供されているフォントです。日本語フォントの作者は西塚 涼子さんだそうです。 </span>
<span style="font-family: Noto Sans Japanese;">このフォントは，GoogleのCDNより読みこまれています。</span>

&nbsp;
<h4><a href="http://hp.vector.co.jp/authors/VA039499/"><span style="font-family: Huifont;">ふい字</span></a></h4>
<span style="font-family: Huifont;">このフォントは，ふい さんが作成された手書きフォントです。</span>

<span style="font-family: Huifont;">このフォントは，rawgit というCDNのサービスで読み込まれています。</span>
<h4><span style="font-family: esenapaj;">エセナパJ</span></h4>
<span style="font-family: esenapaj;">このフォントは，ドッキリなどでよく使えそうな，中華系フォントです。ぶっちゃけ記事に書くにはめっちゃ読みにくいです。。</span>
<blockquote><span style="font-family: esenapaj;">日本語漢字を中国語の漢字字形で表示したり、かな文字をあえて誤字で表示したりしますので、真面目な目的での使用はご遠慮ください。</span></blockquote>
&nbsp;

&nbsp;

&lt;このフォントは，ドッキリなどでよく使えそうな，中華系フォントです。ぶっちゃけ記事に書くにはめっちゃ読みにくいです。。&gt;
<blockquote>日本語漢字を中国語の漢字字形で表示したり、かな文字をあえて誤字で表示したりしますので、真面目な目的での使用はご遠慮ください。</blockquote>
&nbsp;

<span style="font-family: esenapaj;">このフォントは，</span>
<p class="ProfileHeaderCard-name"><a class="ProfileHeaderCard-nameLink u-textInheritColor js-nav" href="https://twitter.com/tanukizamurai"><span style="font-family: esenapaj;">たぬき侍</span> </a><span style="font-family: esenapaj;">さんによって作成されました。</span></p>
&lt;このフォントは，

たぬき侍 さんによって作成されました。&gt;

<span style="font-family: esenapaj;">このフォントは，rawgit というCDNサービスを使用して，読み込んでいます。</span>

&lt;このフォントは，rawgit というCDNサービスを使用して，読み込んでいます。&gt;
<h4><span style="font-family: honokamaru;">ほのか丸ゴシック</span></h4>
<span style="font-family: honokamaru;">このフォントは、Honoka Project さんが作成したフォントです。ちょっと古めな見た目ですね。</span>

<span style="font-family: honokamaru;">このフォントについて詳しくは<a href="http://font.gloomy.jp/honoka-maru-gothic-dl.html">こちら</a>をおねがいします</span>

&nbsp;

&nbsp;
<h3><span style="font-family: Noto Sans Japanese;">変更ログ</span></h3>
<span style="font-family: Noto Sans Japanese;">== Changelog ==</span>
<span style="font-family: Noto Sans Japanese;"> Version beta-1 ;最初のリリース</span><br>
<span style="font-family: Noto Sans Japanese;"> Version beta-2 ;Noto Sans Japaneseの細字、太字を追加br&gt;</span><br>
<span style="font-family: Noto Sans Japanese;"> Version beta-3 ;ふい字を軽量化(1.5MB から 400KB.)</span><br>
<span style="font-family: Noto Sans Japanese;"> Version beta-4 ;バグを直しました。 (追記；この修正、実はただバグを増やしただけでしたとさ。おしまいｗ)</span><br>
<span style="font-family: Noto Sans Japanese;"> Version beta-5 ;バグ修正</span><br>
<span style="font-family: Noto Sans Japanese;"> Version beta-6 ;重要なバグを修正</span><br>
<span style="font-family: Noto Sans Japanese;"> Version beta-7 ;バグ修正(応急処置)</span><br>
<span style="font-family: Noto Sans Japanese;"> Version beta-8 ;エセナパJ (EsenapaJ) フォントを追加</span><br>
<span style="font-family: Noto Sans Japanese;"> Version beta-9 ;コードをバージョンbeta-8からバージョンbeta-7へと戻しました。</span>
<span style="font-family: Noto Sans Japanese;"> Version beta-10 ;エセナパJ (EsenapaJ) フォントをようやく追加することに成功しました！ 楽しんでね！</span><br>
<span style="font-family: Noto Sans Japanese;"> Version 0.1 ;やっとバージョンを安定化バージョンに持ってこれました。マルチサイトサポート、バージョン4.7,4.8,4.9のサポート確認。</span><br>
<span style="font-family: Noto Sans Japanese;"> Version 0.2;また応急処置でバグ直しました。</span><br>
<span style="font-family: Noto Sans Japanese;"> Version 0.3;バグ修正して、ロゴを追加しました</span><br>
<span style="font-family: Noto Sans Japanese;"> Version 0.4;重要なバグを修正しました。</span><br>
<span style="font-family: Noto Sans Japanese;"> Version 0.5;バグまたまた修正中。。とりあえず応急処置バージョンをリリースしました。</span><br>
<span style="font-family: Noto Sans Japanese;"> Version 0.6; バグの一部を修正</span><br>
<span style="font-family: Noto Sans Japanese;"> Version 0.7; たぶんこれで今わかっているすべてのバグを修正しました！次のバージョンではフォント追加するよ！</span><br>
<span style="font-family: Noto Sans Japanese;"> Version 0.8;バグ修正しました。。フォントは追加できませんでした。</span><br>
<span style="font-family: Noto Sans Japanese;"> Version 0.9;バグ修正</span><br>
<span style="font-family: Noto Sans Japanese;"> Version 1.0; 源ノ角ゴシックフォントを追加しました！そしてコピーライトを入力しました！やっとバージョン1.xです！それでは、楽しんでください</span><br>
<span style="font-family: Noto Sans Japanese;"> Version 1.1:ライセンスへのリンクを忘れてました。。修正しました。</span><br>
<span style="font-family: Noto Sans Japanese;"> Version 1.2:バグ修正できたと思いたいです。</span><br>
<span style="font-family: Noto Sans Japanese;"> Version 1.3:バグ修正しました</span><br>
<span style="font-family: Noto Sans Japanese;"> Version 1.4:またまたバグ修正。。もうバグないといいなー</span><br>
<span style="font-family: Noto Sans Japanese;"> Version 1.5: 説明書を編集</span><br>
<span style="font-family: Noto Sans Japanese;"> Version 1.6:説明書を修正</span><br>
<span style="font-family: Noto Sans Japanese;"> Version 1.7;.バグくん、僕ね、バグを見たくないの。どっか他のところにいってくれると嬉しいなというわけでそれなりにひどいバグを修正</span><br>
<span style="font-family: Noto Sans Japanese;"> Version1.8 説明書を修正</span><br>
<span style="font-family: Noto Sans Japanese;"> Version 1.9 フォント追加！バージョン2.0への準備完了！ けど、もう限界なので寝ます。もしバグがあったら朝直します。おやすみ！そして楽しんでね！</span><br>
<span style="font-family: Noto Sans Japanese;"> Version 2.0;Bug check complete. and added woff2 font for Huifont. that's it. done. and I might stop making update for while because of school......( I hate school now....) prety sadly. if there is any important bug, just post a comment to <a href="https://raspi-diary.com/wordpress%e3%81%ae%e3%82%a8%e3%83%87%e3%82%a3%e3%82%bf%e3%81%ab%e6%97%a5%e6%9c%ac%e8%aa%9e%e3%83%95%e3%82%a9%e3%83%b3%e3%83%88%e3%82%92%e8%bf%bd%e5%8a%a0%e3%81%99%e3%82%8b%e3%83%97%e3%83%a9%e3%82%b0/" target="_blank" rel="noopener">here</a> and I will fix (if I can...) them as fast as possible... If there is no responce from me for 1-2week, that's mean I can't responce.....but enjoy and I hope see you again soon!(really)</span><br>
<span style="font-family: Noto Sans Japanese;"> 「日本語訳」</span><br>
<span style="font-family: Noto Sans Japanese;"> バグのチェック完了。そして新たにふい字はwoff2形式のサポートを開始しました。そして説明書に変更ログを書き始めました。</span><br>
<span style="font-family: Noto Sans Japanese;"> Version 2.05:重要なバグ修正。まだフォントの変更はしてません。</span><br>
<span style="font-family: Noto Sans Japanese;"> Version 2.1:いよいよ、源ノ角ゴシックとのお別れです。。今まで短い間でしたがありがとうございました。なお、源ノ角ゴシックフォントを使用している文章はすべてNoto Sans Japanese によって置き換えられます。</span><br>
<span style="font-family: Noto Sans Japanese;"> Version 2.15:バグ修正</span><br>
<span style="font-family: Noto Sans Japanese;"> Version 2.16:バグ修正</span><br>
<span style="font-family: Noto Sans Japanese;"> Version 2.17:バグ修正</span><br>
</div>

</div>
<!-- メインカラム終了 -->




</div>
<!-- コンテナ終了 -->

<?php
}







