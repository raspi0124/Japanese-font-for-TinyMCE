<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Gutenberg assets built via @wordpress/scripts and block.json.
 *
 * Assets are built into /build by CI; if the build does not exist we bail
 * without affecting TinyMCE legacy.
 */
function tinyjpfont_register_gutenberg_assets() { // phpcs:ignore
	$build_dir  = plugin_dir_path( __FILE__ ) . 'build/';
	$asset_path = $build_dir . 'index.asset.php';

	if ( ! file_exists( $asset_path ) ) {
		return;
	}

	register_block_type_from_metadata( __DIR__ );

	$asset_meta = include $asset_path;

	// Make translations available for the editor script.
	if ( isset( $asset_meta['dependencies'], $asset_meta['version'] ) ) {
		wp_set_script_translations(
			'tinyjpfont-font-kit-editor-script',
			'japanese-font-for-tinymce'
		);
	}
}
add_action( 'init', 'tinyjpfont_register_gutenberg_assets' );

/**
 * Surface font families to the block editor typography controls.
 */
function tinyjpfont_register_font_families( $settings ) {
	$fonts = array(
		array(
			'slug'  => 'tinyjpfont-noto',
			'name'  => 'Noto Sans Japanese',
			'fontFamily' => '"Noto Sans Japanese", sans-serif',
		),
		array(
			'slug'  => 'tinyjpfont-huiji',
			'name'  => 'ふい字',
			'fontFamily' => '"Huifont", "Noto Sans Japanese", sans-serif',
		),
	);

	if ( ! isset( $settings['typography'] ) || ! is_array( $settings['typography'] ) ) {
		$settings['typography'] = array();
	}

	if ( ! isset( $settings['typography']['fontFamilies'] ) || ! is_array( $settings['typography']['fontFamilies'] ) ) {
		$settings['typography']['fontFamilies'] = array();
	}

	$settings['typography']['fontFamilies'] = array_merge(
		$settings['typography']['fontFamilies'],
		$fonts
	);

	return $settings;
}
add_filter( 'block_editor_settings_all', 'tinyjpfont_register_font_families' );
