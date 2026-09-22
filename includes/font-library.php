<?php
if (!defined('ABSPATH')) { exit; }
function tinyjpfont_register_collection() {
    if(!function_exists('wp_register_font_collection')){return;}
    $families=array();
    foreach(tinyjpfont_fonts() as $font){
        $families[]=array('font_family_settings'=>array('name'=>$font['label'],'slug'=>'tinyjpfont-'.$font['id'],'fontFamily'=>$font['family'],'fontFace'=>array(array('fontFamily'=>$font['family'],'fontStyle'=>'normal','fontWeight'=>(string)$font['weight'],'src'=>tinyjpfont_font_url($font)))),'categories'=>array('japanese'));
    }
    wp_register_font_collection('tinyjpfont',array('name'=>__('日本語フォント','japanese-font-for-tinymce'),'description'=>__('Japanese Font for WordPressで使える書体。必要な書体をこのサイトにインストールできます。','japanese-font-for-tinymce'),'font_families'=>$families,'categories'=>array(array('name'=>__('日本語','japanese-font-for-tinymce'),'slug'=>'japanese'))));
}
add_action('init','tinyjpfont_register_collection');

/** Prefer explicitly installed Font Library files for the legacy family names too. */
function tinyjpfont_local_face_css() {
    if (!post_type_exists('wp_font_face') || !function_exists('wp_get_font_dir')) { return ''; }
    static $css=null;
    if ($css!==null) { return $css; }
    $css='';$directory=wp_get_font_dir();
    foreach(get_posts(array('post_type'=>'wp_font_face','posts_per_page'=>-1,'post_status'=>'publish','no_found_rows'=>true)) as $post) {
        $face=json_decode($post->post_content,true);
        if (!is_array($face) || empty($face['fontFamily']) || empty($face['src'])) { continue; }
        $font=tinyjpfont_font(trim($face['fontFamily'],'"\''));
        if (!$font) { continue; }
        foreach((array)$face['src'] as $src) {
            if (!is_string($src) || strpos($src,trailingslashit($directory['url']))!==0) { continue; }
            $weight=isset($face['fontWeight']) && preg_match('/^[0-9 ]+$/',$face['fontWeight']) ? $face['fontWeight'] : $font['weight'];
            $style=isset($face['fontStyle']) && $face['fontStyle']==='italic' ? 'italic' : 'normal';
            $css.='@font-face{font-family:"'.$font['family'].'";font-style:'.$style.';font-weight:'.$weight.';font-display:swap;src:url("'.esc_url_raw($src).'");}';
            break;
        }
    }
    return $css;
}
