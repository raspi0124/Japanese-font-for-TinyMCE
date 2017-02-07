<?php
/*
Plugin Name: Japanese font for TinyMCE
Description: Add Japanese font to TinyMCE Advanced plugin's font family selections..
Version: beta-1
Author: raspi0124
License: GPL2
*/

//add font to tiny mce
function load_custom_fonts($init) {

    $stylesheet_url = 'https://cdn.rawgit.com/raspi0124/my-sites-files/9e60dc98/addfont.css';

    if(empty($init['content_css'])) {
        $init['content_css'] = $stylesheet_url;
    } else {
        $init['content_css'] = $init['content_css'].','.$stylesheet_url;
    }

    $font_formats = isset($init['font_formats']) ? $init['font_formats'] : 'Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats';

    $custom_fonts = ';'.'ふい字=Huifont;Noto Sans Japanese=Noto Sans Japanese;自家製 Rounded M+=Homemade Rounded M+';

    $init['font_formats'] = $font_formats . $custom_fonts;

    return $init;
}
add_filter('tiny_mce_before_init', 'load_custom_fonts');

function load_custom_fonts_frontend() {

    echo '<link type="text/css" rel="stylesheet" href="https://cdn.rawgit.com/raspi0124/my-sites-files/9e60dc98/addfont.css">';
}
add_action('wp_head', 'load_custom_fonts_frontend');
add_action('admin_head', 'load_custom_fonts_frontend');

wp_enqueue_style(
    'add_jpfont_tinymce_style',
    plugins_url() . '/css/addfont.css',
    array( 'add_jpfont_tinymce_style' ),
    null, // example of no version number...
    // ...and no CSS media type
);
