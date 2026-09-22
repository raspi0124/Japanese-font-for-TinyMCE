<?php
if (!defined('ABSPATH')) { exit; }
function tinyjpfont_whole_css() {
    $font=tinyjpfont_font(get_option('tinyjpfont_whole_font','noselect'));
    if (!$font) { return ''; }
    $family='"'.$font['family'].'",sans-serif';
    // Keep explicit content choices and icon/admin-toolbar fonts intact.
    $explicit='.noto,.huiji,.honokamaru,.tinyjpfont_noto,.tinyjpfont_huiji,.wp-block-tinyjpfont-noto,.wp-block-tinyjpfont-huiji,[class*="-font-family"],[style*="font-family"]';
    $text='p,h1,h2,h3,h4,h5,h6,li,button,a,blockquote,figcaption,td,th,input,select,textarea,.site-title,.site-description';
    return 'body{font-family:'.$family.';}body .entry-content:not(:where('.$explicit.')),body .wp-site-blocks:not(:where('.$explicit.')){font-family:'.$family.';}body :where('.$text.'):not(:where('.$explicit.',#wpadminbar *)){font-family:inherit !important;}';
}
function tinyjpfont_whole_assets() {
    $css=tinyjpfont_whole_css();
    if ($css==='') { return; }
    wp_register_style('tinyjpfont-whole-font',false,array(),JapaneseFontTinyMCE::VERSION);
    wp_enqueue_style('tinyjpfont-whole-font');wp_add_inline_style('tinyjpfont-whole-font',$css);
}
add_action('wp_enqueue_scripts','tinyjpfont_whole_assets',100);
add_filter('wp_kses_allowed_html',function($allowed,$context){
    if($context==='post') { $allowed['tinyjpfontnoto']=array('class'=>true); }
    return $allowed;
},10,2);
