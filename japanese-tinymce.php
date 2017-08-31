<?php
/*
Plugin Name: Japanese font for TinyMCE
Description: Add Japanese font to TinyMCE Advanced plugin's font family selections..
Version: 2.0
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
    $custom_fonts = ';'.'ふい字=Huifont;Noto Sans Japanese=Noto Sans Japanese;太字なNoto Sans Japanese=Noto Sans Japanese-900;細字なNoto Sans Japanese=Noto Sans Japanese-100;エセナパJ=esenapaj;ほのか丸ゴシック=honokamaru;細字な源ノ角ゴシック=light-gen;源ノ角ゴシック=normal-gen;';
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

function tinyjpfont_setumeisyostyle() {
    wp_register_style( 'tinyjpfont-setumeisyostyles',  plugin_dir_url( __FILE__ ) . 'setumeisyo.css' );
    wp_enqueue_style( 'tinyjpfont-setumeisyostyles' );
}
add_action( 'admin_enqueue_scripts', 'tinyjpfont_setumeisyostyle' );
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
<div id="container">
<!-- ヘッダ開始 -->
<div id="header">
<h2>Japanese Font for TinyMCE 説明書</h2>
</div>
<!-- ヘッダ終了 -->

<!-- ナビゲーション開始 -->
<div id="nav">
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
<div id="content">
<h3>このプラグインについて</h3>
&nbsp;

If you are non-Japanese speaker, please translate thease sentense using <a href="https://translate.google.com/#ja/en/Japanese Font for TinyMCE 説明書%0Aこのプラグインについて%0A%0A%0AIf you are non-Japanese speaker%2C could you translate this sentense with google translate or bing translate%3F%0A%0A%0Aこのプラグインは，日本語フォントをTinyMCE Advanced プラグインのフォント選択画面に追加するプラグインです。%0Aフォントは現在 Noto Sans Japaneseの細字バージョン Noto Sans Japanese 太字なNoto Sans Japanese ふい字 エセナパJ 細字な源ノ角ゴシック 源ノ角ゴシック%0Aをご用意しています。%0A%0A(ちなみにこのページ，本当は設定画面にしようと思ったのですが，設定することも思いつかなかったので説明書になりましたｗ)%0Aフォントの説明%0A%0A%0ANoto Sans Japanese%0A%0Aこのフォントは， Google社より提供されているフォントです。日本語フォントの作者は西塚 涼子さんだそうです。%0Aこのフォントは，GoogleのCDNより読みこまれています。%0A%0A%0Aふい字%0A%0Aこのフォントは，ふい さんが作成された手書きフォントです。%0A%0Aこのフォントは，rawgit というCDNのサービスで読み込まれています。%0AエセナパJ%0A%0Aこのフォントは，ドッキリなどでよく使えそうな，中華系フォントです。ぶっちゃけ記事に書くにはめっちゃ読みにくいです。。%0A%0A 日本語漢字を中国語の漢字字形で表示したり、かな文字をあえて誤字で表示したりしますので、真面目な目的での使用はご遠慮ください。%0A%0Aとのことです。%0A%0A&lt;%0A%0A%0Aこのフォントは，ドッキリなどでよく使えそうな，中華系フォントです。ぶっちゃけ記事に書くにはめっちゃ読みにくいです。。%0A%0A 日本語漢字を中国語の漢字字形で表示したり、かな文字をあえて誤字で表示したりしますので、真面目な目的での使用はご遠慮ください。%0A%0Aとのことです。%0A%0A&gt;%0A%0Aこのフォントは，%0A%0Aたぬき侍 さんによって作成されました。%0A%0A&lt;%0A%0Aこのフォントは，%0A%0Aたぬき侍 さんによって作成されました。%0A%0A&gt;%0A%0Aこのフォントは，rawgit というCDNサービスを使用して，読み込んでいます。%0A%0A&lt;このフォントは，rawgit というCDNサービスを使用して，読み込んでいます。&gt;%0A源ノ角ゴシック%0A%0Aこのフォントは，Adobe社によって作成されたフォントです。%0A%0A基本的にはNoto Sans Japanese と変わらないらしいですが，自分的にはなんか違う気がします。%0A%0Aこのフォントも，rawgit というCDNサービスを使用して読み込んでいます。%0A%0A %0A%0Aその他詳しくはこちらをどうぞ">google translate</a> or bing translate.

&nbsp;

このプラグインは，日本語フォントをTinyMCE Advanced プラグインのフォント選択画面に追加するプラグインです。
フォントは現在 <span style="font-family: Noto Sans Japanese-100;">Noto Sans Japaneseの細字バージョン <span style="font-family: Noto Sans Japanese;">Noto Sans Japanese <span style="font-family: Noto Sans Japanese-900;">太字なNoto Sans Japanese <span style="font-family: Huifont;">ふい字 <span style="font-family: esenapaj;">エセナパJ <span style="font-family: light-gen;">細字な源ノ角ゴシック <span style="font-family: normal-gen;">源ノ角ゴシック</span></span>

</span></span></span></span></span>をご用意しています。

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
<p href="https://twitter.com/tanukizamurai">たぬき侍 </a>さんによって作成されました。</p>
&gt;

<span style="font-family: esenapaj;">このフォントは，rawgit というCDNサービスを使用して，読み込んでいます。</span>

&lt;このフォントは，rawgit というCDNサービスを使用して，読み込んでいます。&gt;
<h4><a href="https://github.com/adobe-fonts/source-han-sans/tree/release/SubsetOTF"><span style="font-family: normal-gen;">源ノ角ゴシック</span></a></h4>
<span style="font-family: normal-gen;">このフォントは，Adobe社によって作成されたフォントです。</span>

<span style="font-family: normal-gen;">基本的にはNoto Sans Japanese と変わらないらしいですが，自分的にはなんか違う気がします。</span>

<span style="font-family: normal-gen;">このフォントも，rawgit というCDNサービスを使用して読み込んでいます。</span>

&nbsp;

その他詳しくは<a href="https://raspi-diary.com/wordpress%e3%81%ae%e3%82%a8%e3%83%87%e3%82%a3%e3%82%bf%e3%81%ab%e6%97%a5%e6%9c%ac%e8%aa%9e%e3%83%95%e3%82%a9%e3%83%b3%e3%83%88%e3%82%92%e8%bf%bd%e5%8a%a0%e3%81%99%e3%82%8b%e3%83%97%e3%83%a9%e3%82%b0/">こちら</a>をどうぞ

<br><h3>変更ログ</h3><br>
== Changelog ==
Version beta-1 ;最初のリリース<br>
Version beta-2 ;Noto Sans Japaneseの細字、太字を追加br>
Version beta-3 ;ふい字を軽量化(1.5MB から 400KB.)<br>
Version beta-4 ;バグを直しました。 (追記；この修正、実はただバグを増やしただけでしたとさ。おしまいｗ)<br>
Version beta-5 ;バグ修正<br>
Version beta-6 ;重要なバグを修正<br>
Version beta-7 ;バグ修正(応急処置)<br>
Version beta-8 ;エセナパJ (EsenapaJ) フォントを追加<br>
Version beta-9 ;コードをバージョンbeta-8からバージョンbeta-7へと戻しました。<br>
Version beta-10 ;エセナパJ (EsenapaJ) フォントをようやく追加することに成功しました！ 楽しんでね！<br>
Version 0.1 ;やっとバージョンを安定化バージョンに持ってこれました。マルチサイトサポート、バージョン4.7,4.8,4.9のサポート確認。<br>
Version 0.2;また応急処置でバグ直しました。<br>
Version 0.3;バグ修正して、ロゴを追加しました！br>
Version 0.4;重要なバグを修正しました。<br>
Version 0.5;バグまたまた修正中。。とりあえず応急処置バージョンをリリースしました。<br>
Version 0.6; バグの一部を修正<br>
Version 0.7; たぶんこれで今わかっているすべてのバグを修正しました！次のバージョンではフォント追加するよ！<br>
Version 0.8;バグ修正しました。。フォントは追加できませんでした。<br>
Version 0.9;バグ修正<br>
Version 1.0; 源ノ角ゴシックフォントを追加しました！そしてコピーライトを入力しました！やっとバージョン1.xです！それでは、楽しんでください <br>
Version 1.1:ライセンスへのリンクを忘れてました。。修正しました。<br>
Version 1.2:バグ修正できたと思いたいです。<br>
Version 1.3:バグ修正しました<br>
Version 1.4:またまたバグ修正。。もうバグないといいなー<br>
Version 1.5: 説明書を編集<br>
Version 1.6:説明書を修正<br>
Version 1.7;.バグくん、僕ね、バグを見たくないの。どっか他のところにいってくれると嬉しいなというわけでそれなりにひどいバグを修正<br>
Version1.8 説明書を修正<br>
Version 1.9 フォント追加！バージョン2.0への準備完了！ けど、もう限界なので寝ます。もしバグがあったら朝直します。おやすみ！そして楽しんでね！<br>
Version 2.0;Bug check complete. and added woff2 font for Huifont. that's it. done. and I might stop making update for while because of school......( I hate school now....) prety sadly. if there is any important bug, just post a comment to <a href="https://raspi-diary.com/wordpress%e3%81%ae%e3%82%a8%e3%83%87%e3%82%a3%e3%82%bf%e3%81%ab%e6%97%a5%e6%9c%ac%e8%aa%9e%e3%83%95%e3%82%a9%e3%83%b3%e3%83%88%e3%82%92%e8%bf%bd%e5%8a%a0%e3%81%99%e3%82%8b%e3%83%97%e3%83%a9%e3%82%b0/" target="_blank">here</a> and I will fix (if I can...) them as fast as possible... If there is no responce from me for 1-2week, that's mean I can't responce.....but enjoy and I hope see you again soon!(really)<br>
「日本語訳」<br>
バグのチェック完了。そして新たにふい字はwoff2形式のサポートを開始しました。そして説明書に変更ログを書き始めました。あと、学校とかの理由でひょっとしたらしばらくこのプラグインの更新を止めるかもです。（すぐに戻ってくるかもです。 あー。。。学校嫌になってきた）もし重大なバグがあったら、<a href="https://raspi-diary.com/wordpress%e3%81%ae%e3%82%a8%e3%83%87%e3%82%a3%e3%82%bf%e3%81%ab%e6%97%a5%e6%9c%ac%e8%aa%9e%e3%83%95%e3%82%a9%e3%83%b3%e3%83%88%e3%82%92%e8%bf%bd%e5%8a%a0%e3%81%99%e3%82%8b%e3%83%97%e3%83%a9%e3%82%b0/" target="_blank">ここ</a>に書いてくれればできるだけ早く直します。もし、1−2週間ほどたっても返信がなければ、ちょっとこのプラグインに手がつけれない事態になっているということです。まあ、バージョン2.x からも引き続き楽しんで使ってくださると嬉しいです。それでは、またいつか！できるだけ早くまた会えることを祈っています！
&nbsp;

&nbsp;

&nbsp;
</div>

</div>
<!-- メインカラム終了 -->




</div>
<!-- コンテナ終了 -->

<?php
}







