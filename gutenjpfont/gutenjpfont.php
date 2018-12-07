<?php
//This code is moved from plugin "Japanese font for Gutenberg." Both Japanese font for Gutenberg and Japanese Font for TinyMCE is made by myself(raspi0124)

if (function_exists('register_block_type')) {
#CSSとかJSのロード
function tinyjpfont_load_gutenberg() {
	wp_register_script(
			'tinyjpfont_noto_js',
			plugins_url( 'noto.js', __FILE__ ),
			array( 'wp-blocks', 'wp-element' )
	);
	wp_register_script(
			'tinyjpfont_huiji_js',
			plugins_url( 'huiji.js', __FILE__ ),
			array( 'wp-blocks', 'wp-element' )
	);

	wp_register_style(
			'tinyjpfont_css',
			plugins_url( 'addfont.css', __FILE__ ),
			array( 'wp-edit-blocks' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'addfont.css' )
	);

	wp_register_style(
			'tinyjpfont_front_css',
			plugins_url( 'addfont.css', __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . 'addfont.css' )
	);

}
#Noto Sans Japaneseのロード
function tinyjpfont_noto() {
    register_block_type( 'tinyjpfont/noto', array(
        'editor_script' => 'tinyjpfont_noto_js',
        'editor_style'  => 'tinyjpfont_css',
	'style' => 'tinyjpfont_front_css',
    ) );
}
#ふい字のロード
function tinyjpfont_huiji() {
		register_block_type( 'tinyjpfont/huiji', array(
        'editor_script' => 'tinyjpfont_huiji_js',
        'editor_style'  => 'tinyjpfont_css',
	'style' => 'tinyjpfont_front_css',
    ) );
}
add_action( 'init', 'tinyjpfont_load_gutenberg');
add_action( 'init', 'tinyjpfont_noto' );
add_action( 'init', 'tinyjpfont_huiji' );
}
