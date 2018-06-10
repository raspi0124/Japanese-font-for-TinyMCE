<?php
//This code is moved from plugin "Japanese font for Gutenberg." Both Japanese font for Gutenberg and Japanese Font for TinyMCE is made by myself(raspi0124)


function gutenjpfont_noto() {
    wp_register_script(
        'gutenjpfont_js',
        plugins_url( 'addfont.js', __FILE__ ),
        array( 'wp-blocks', 'wp-element' )
    );
    wp_register_style(
        'gutenjpfont_css',
        plugins_url( 'addfont.css', __FILE__ ),
        array( 'wp-edit-blocks' ),
        filemtime( plugin_dir_path( __FILE__ ) . 'addfont.css' )
    );

    wp_register_style(
        'gutenjpfont_front_css',
        plugins_url( 'addfont.css', __FILE__ ),
        array(),
        filemtime( plugin_dir_path( __FILE__ ) . 'addfont.css' )
    );

    register_block_type( 'gutenjpfont/noto', array(
        'editor_script' => 'gutenjpfont_js',
        'editor_style'  => 'gutenjpfont_css',
	'style' => 'gutenjpfont_front_css',
    ) );
}
add_action( 'init', 'gutenjpfont_noto' );
