<?php
if (!defined('ABSPATH')) { exit; }
function tinyjpfont_gutenberg_editor_assets() {
    global $wp_version;
    $directory = dirname(__DIR__) . '/dist/';
    if (!file_exists($directory . 'blocks.asset.php')) { return; }
    $asset = require $directory . 'blocks.asset.php';
    $dependencies = $asset['dependencies'];
    $dependencies[] = 'wp-i18n';
    $dependencies[] = version_compare($wp_version, '5.2', '>=') ? 'wp-block-editor' : 'wp-editor';
    wp_enqueue_script('tinyjpfont_gutenberg_block-js', plugins_url('dist/blocks.js', dirname(__FILE__)), array_unique($dependencies), $asset['version'], true);
    wp_localize_script('tinyjpfont_gutenberg_block-js', 'tinyjpfontEditor', array(
        'apiVersion'=>version_compare($wp_version, '6.3', '>=') ? 3 : 1,
        'hasFontPresets'=>version_compare($wp_version, '6.1', '>=')
    ));
}
add_action('enqueue_block_editor_assets', 'tinyjpfont_gutenberg_editor_assets');
