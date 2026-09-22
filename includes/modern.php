<?php
if (!defined('ABSPATH')) { exit; }
function tinyjpfont_presets($data) {
    if ((string)get_option('tinyjpfont_gutenberg','1') !== '1') { return $data; }
    $theme=$data->get_data();
    if (!isset($theme['settings']['typography']['fontFamilies'])) { $theme['settings']['typography']['fontFamilies']=array(); }
    $families=$theme['settings']['typography']['fontFamilies'];
    if (isset($families['theme'])) { $families=$families['theme']; }
    $existing=array();foreach($families as $family){if(isset($family['slug'])){$existing[]=$family['slug'];}}
    foreach(tinyjpfont_choices((string)get_option('tinyjpfont_select','0')==='1') as $font) {
        $slug='tinyjpfont-'.$font['id'];if(in_array($slug,$existing,true)){continue;}
        $families[]=array('name'=>$font['label'],'slug'=>$slug,'fontFamily'=>'"'.$font['family'].'", sans-serif');
    }
    $theme=array('version'=>2,'settings'=>array('typography'=>array('fontFamilies'=>$families)));
    $font=tinyjpfont_font(get_option('tinyjpfont_whole_font','noselect'));
    if($font){$theme['styles']['typography']['fontFamily']='"'.$font['family'].'", sans-serif';}
    return $data->update_with($theme);
}
add_filter('wp_theme_json_data_theme','tinyjpfont_presets');

function tinyjpfont_editor_whole_font() {
    if (!is_admin()) { return; }
    $font=tinyjpfont_font(get_option('tinyjpfont_whole_font','noselect'));
    if (!$font) { return; }
    $explicit='.noto,.huiji,.honokamaru,.tinyjpfont_noto,.tinyjpfont_huiji,.wp-block-tinyjpfont-noto,.wp-block-tinyjpfont-huiji,[class*="-font-family"],[style*="font-family"]';
    $css='.editor-styles-wrapper{font-family:"'.$font['family'].'",sans-serif !important;}';
    $css.='.editor-styles-wrapper :where(p,h1,h2,h3,h4,h5,h6,li,button,a):not(:where('.$explicit.')){font-family:inherit !important;}';
    wp_register_style('tinyjpfont-editor-whole',false,array(),JapaneseFontTinyMCE::VERSION);
    wp_enqueue_style('tinyjpfont-editor-whole');wp_add_inline_style('tinyjpfont-editor-whole',$css);
}
add_action('enqueue_block_assets','tinyjpfont_editor_whole_font',100);

/** Saved Global Styles may replace the theme's entire font list. Retain new choices. */
function tinyjpfont_user_presets($data) {
    if ((string)get_option('tinyjpfont_gutenberg','1') !== '1') { return $data; }
    $raw=$data->get_data();
    if (empty($raw['settings']['typography']['fontFamilies'])) { return $data; }
    $origins=$raw['settings']['typography']['fontFamilies'];
    $custom=isset($origins['custom']) ? $origins['custom'] : array();
    $existing=array();
    foreach ($origins as $families) {
        foreach ((array)$families as $family) {
            if (is_array($family) && isset($family['slug'])) { $existing[]=$family['slug']; }
        }
    }
    foreach (tinyjpfont_choices((string)get_option('tinyjpfont_select','0')==='1') as $font) {
        $slug='tinyjpfont-'.$font['id'];
        if (!in_array($slug,$existing,true)) {
            $custom[]=array('name'=>$font['label'],'slug'=>$slug,'fontFamily'=>'"'.$font['family'].'", sans-serif');
        }
    }
    return $data->update_with(array('version'=>2,'settings'=>array('typography'=>array('fontFamilies'=>$custom))));
}
add_filter('wp_theme_json_data_user','tinyjpfont_user_presets');
