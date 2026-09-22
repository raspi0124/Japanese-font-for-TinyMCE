<?php
if (!defined('ABSPATH')) { exit; }
function tinyjpfont_whole_css() {
    $font=tinyjpfont_font(get_option('tinyjpfont_whole_font','noselect'));
    if (!$font) { return ''; }
    $family='"'.$font['family'].'",sans-serif';
    // Individual inline styles/preset classes still win. Inheritance preserves group-level choices.
    return 'body{font-family:'.$family.';}body .entry-content,body .wp-site-blocks{font-family:'.$family.';}body .entry-content :where(p,h1,h2,h3,h4,h5,h6,li,button,a):not(:where(.noto,.huiji,.honokamaru,.tinyjpfont_noto,.tinyjpfont_huiji,.wp-block-tinyjpfont-noto,.wp-block-tinyjpfont-huiji,[class*="-font-family"])),body .wp-site-blocks :where(p,h1,h2,h3,h4,h5,h6,li,button,a):not(:where(.noto,.huiji,.honokamaru,.tinyjpfont_noto,.tinyjpfont_huiji,.wp-block-tinyjpfont-noto,.wp-block-tinyjpfont-huiji,[class*="-font-family"])){font-family:inherit;}';
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
