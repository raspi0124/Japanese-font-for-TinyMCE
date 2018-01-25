<?php
/*
Plugin Name: Japanese font for TinyMCE
Description: Add Japanese font to TinyMCE Advanced plugin's font family selections..
Version: 3.5
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

    This software includes the work that is distributed in the Apache License 2.0

*/
// define $

 
 //--CONFIG START--

$config1 = 0;
$config2 = 0;




//add font to tiny mce
// setting <Version alpha>
if ( $config2 == 0) {
    function tinyjpfont_load_custom_fonts($init) {
    $stylesheet_url = plugin_dir_url( __FILE__ ) . 'addfont.css';
    if(empty($init['content_css'])) {
        $init['content_css'] = $stylesheet_url;
    } else {
        $init['content_css'] = $init['content_css'].','.$stylesheet_url;
    }
    $font_formats = isset($init['font_formats']) ? $init['font_formats'] : 'Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats';
    $custom_fonts = ';'.'ふい字=Huifont;Noto Sans Japanese=Noto Sans Japanese;太字なNoto Sans Japanese=Noto Sans Japanese-900;細字なNoto Sans Japanese=Noto Sans Japanese-100;エセナパJ=esenapaj;ほのか丸ゴシック=honokamaru;こころ明朝体=kokorom;青柳衡山フォントT=aoyanagiT;たぬき油性マジック=tanukiM';
    $init['font_formats'] = $font_formats . $custom_fonts;
    return $init;
}
add_filter('tiny_mce_before_init', 'tinyjpfont_load_custom_fonts');
}
else {
    function tinyjpfont_load_custom_fonts($init) {
    $stylesheet_url = plugin_dir_url( __FILE__ ) . 'addfont.css';
    if(empty($init['content_css'])) {
        $init['content_css'] = $stylesheet_url;
    } else {
        $init['content_css'] = $init['content_css'].','.$stylesheet_url;
    }
    $font_formats = isset($init['font_formats']) ? $init['font_formats'] : 'Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats';
    $custom_fonts = ';'.'ふい字=Huifont;Noto Sans Japanese=Noto Sans Japanese;';
    $init['font_formats'] = $font_formats . $custom_fonts;
    return $init;
}
add_filter('tiny_mce_before_init', 'tinyjpfont_load_custom_fonts');
}


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



//add font selection to quicktag also<alpha>
//http://webtukuru.com/web/wordpress-quicktag/
//https://wpdocs.osdn.jp/%E3%82%AF%E3%82%A4%E3%83%83%E3%82%AF%E3%82%BF%E3%82%B0API
function tinyjpfont_quicktag() {
  //スクリプトキューにquicktagsが保存されているかチェック
  if (wp_script_is('quicktags')){?>
    <script>
      QTags.addButton('tinyjpfont-noto','Noto Sans Japanese','<span style="font-family: Noto Sans Japanese;">','</span>');
      QTags.addButton('tinyjpfont-huiji','ふい字','<span style="font-family: Huifont;">','</span>');
    </script>
  <?php
  }
}
add_action( 'admin_print_footer_scripts', 'tinyjpfont_quicktag' );



//add font selector to TinyMCE also. no more TinyMCE Advanced plugin

add_filter( 'tiny_mce_before_init', 'tinyjpfont_custom_tiny_mce_style_formats' );
function tinyjpfont_custom_tiny_mce_style_formats( $settings ) {
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
  $settings[ 'style_formats' ] = json_encode( $style_formats );
  return $settings;
}

add_filter( 'mce_buttons', 'tinyjpfont_add_original_styles_button' );
function tinyjpfont_add_original_styles_button( $buttons ) {
  array_splice( $buttons, 1, 0, 'styleselect' );
  return $buttons;
}


// メニューで表示されるページの内容を返す関数
function tinyjpfont_options_page() {
    // POSTデータがあれば設定を更新
    if (isset($_POST['tinyjpfont_text'])) {
        // POSTデータの'"などがエスケープされるのでwp_unslashで戻して保存
        update_option('tinyjpfont_text', wp_unslash($_POST['tinyjpfont_text']));
        update_option('tinyjpfont_textarea', wp_unslash($_POST['tinyjpfont_textarea']));
        update_option('tinyjpfont_radio', $_POST['tinyjpfont_radio']);
        update_option('tinyjpfont_select', $_POST['tinyjpfont_select']);
        // チェックボックスはチェックされないとキーも受け取れないので、ない時は0にする
        $tinyjpfont_checkbox = isset($_POST['tinyjpfont_checkbox']) ? 1 : 0;
        update_option('tinyjpfont_checkbox', $tinyjpfont_checkbox);
    }
?>
<div class="wrap">
<h2>設定サンプル</h2>
<?php
    // 更新完了を通知
    if (isset($_POST['tinyjpfont_text'])) {
        echo '<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible">
            <p><strong>設定を保存しました。</strong></p></div>';
    }
?>

<form method="post" action="">
<table class="form-table">
    <tr>
        <th scope="row"><label for="tinyjpfont_text">マイテキスト</label></th>
        <td><input name="tinyjpfont_text" type="text" id="tinyjpfont_text" value="<?php form_option('tinyjpfont_text'); ?>" class="regular-text" /></td>
    </tr>
    <tr>
        <th scope="row"><label for="tinyjpfont_textarea">マイテキストボックス</label></th>
        <td><textarea name="tinyjpfont_textarea" id="tinyjpfont_textarea" class="large-text code" rows="5"><?php echo esc_textarea(get_option('tinyjpfont_textarea')); ?></textarea></td>
    </tr>
    <tr>
        <th scope="row"><label for="tinyjpfont_checkbox">マイチェックボックス</label></th>
        <td><label><input name="tinyjpfont_checkbox" type="checkbox" id="tinyjpfont_checkbox" value="1" <?php checked( 1, get_option('tinyjpfont_checkbox')); ?> /> チェック</label></td>
    </tr>
    <tr>
        <th scope="row">マイラジオ</th>
        <td><p><label><input name="tinyjpfont_radio" type="radio" value="0" <?php checked( 0, get_option( 'tinyjpfont_radio' ) ); ?>  />ラジオ0</label><br />
                <label><input name="tinyjpfont_radio" type="radio" value="1" <?php checked( 1, get_option( 'tinyjpfont_radio' ) ); ?> />ラジオ1</label></p>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="tinyjpfont_select">マイセレクト</label></th>
        <td>
            <select name="tinyjpfont_select" id="tinyjpfont_select">
                <option value="0" <?php selected( 0, get_option( 'tinyjpfont_select' ) ); ?> >セレクト0</option>
                <option value="1" <?php selected( 1, get_option( 'tinyjpfont_select' ) ); ?> >セレクト1</option>
            </select>
        </td>
    </tr>
</table>
<?php submit_button(); ?>
</form>
</div>
<?php
}