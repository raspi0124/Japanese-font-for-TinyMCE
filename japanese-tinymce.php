<?php
/**
 * Plugin Name: Japanese font for WordPress (Previously: Japanese Font for TinyMCE)
 * Description: Adds Japanese fonts to Gutenberg and TinyMCE while preserving existing content.
 * Version: 5.00-dev.7
 * Requires at least: 5.1
 * Requires PHP: 5.6
 * Author: raspi0124
 * Author URI: https://raspi0124.dev/
 * License: GPLv2 or later
 * Text Domain: japanese-font-for-tinymce
 */
if (!defined('ABSPATH')) { exit; }
require_once __DIR__ . '/includes/fonts.php';
require_once __DIR__ . '/settings.php';
require_once __DIR__ . '/notice.php';
require_once __DIR__ . '/includes/whole-font.php';
require_once __DIR__ . '/includes/modern.php';
require_once __DIR__ . '/includes/font-library.php';
class JapaneseFontTinyMCE {
    const VERSION = '5.00-dev.7';
    const OPT_CDN_ENABLED = 'tinyjpfont_check_cdn';
    const OPT_FONT_MODE = 'tinyjpfont_select';
    const OPT_GUTENBERG_ENABLED = 'tinyjpfont_gutenberg';
    const OPT_LOAD_IN_FOOTER = 'tinyjpfont_head';
    const OPT_DEFAULT_FONT = 'tinyjpfont_default_font';
    const OPT_WHOLE_FONT = 'tinyjpfont_whole_font';
    const DEFAULT_FONTS = 'Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings';
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'frontend'));
        add_action('wp_footer', array($this, 'footer'), 5);
        add_action('enqueue_block_assets', array($this, 'block_assets'));
        add_action('admin_enqueue_scripts', array($this, 'admin_assets'));
        add_filter('tiny_mce_before_init', array($this, 'load_custom_fonts'));
        add_filter('tiny_mce_before_init', array($this, 'customize_font_sizes'));
        add_filter('tiny_mce_before_init', array($this, 'custom_tiny_mce_style_formats'));
        add_filter('mce_buttons', array($this, 'buttons'), 20);
        if ((string) get_option(self::OPT_GUTENBERG_ENABLED, '1') === '1') {
            require_once __DIR__ . '/gutenjpfont/gutenjpfont.php';
        }
    }
    public function init() {
        load_plugin_textdomain('japanese-font-for-tinymce', false, dirname(plugin_basename(__FILE__)) . '/languages');
        if (is_admin()) { $this->add_default_font(); }
    }
    private function style_url() {
        $name = (string) get_option(self::OPT_FONT_MODE, '0') === '1' ? 'addfont_lite.css' : 'addfont.css';
        if ((string) get_option(self::OPT_CDN_ENABLED, '0') === '1') {
            return 'https://fonts.raspi0124.dev/v1/css/3/' . $name;
        }
        return plugins_url($name, __FILE__);
    }
    public function register_and_enqueue_style() {
        wp_enqueue_style('tinyjpfont-styles', $this->style_url(), array(), self::VERSION);
        if (defined('TINYJPFONT_ASSET_BASE_URL')) { wp_add_inline_style('tinyjpfont-styles', tinyjpfont_face_css()); }
        if (function_exists('tinyjpfont_local_face_css')) { wp_add_inline_style('tinyjpfont-styles',tinyjpfont_local_face_css()); }
    }
    public function frontend() {
        if ((string) get_option(self::OPT_LOAD_IN_FOOTER, '0') === '0') { $this->register_and_enqueue_style(); }
    }
    public function footer() {
        if ((string) get_option(self::OPT_LOAD_IN_FOOTER, '0') === '1') {
            $this->register_and_enqueue_style();
            wp_print_styles('tinyjpfont-styles');
        }
    }
    public function block_assets() {
        // Frontend is handled by head/footer preference. Editor canvases need the same definitions.
        if (is_admin()) { $this->register_and_enqueue_style(); }
    }
    public function admin_assets($hook) {
        if ($hook === 'post.php' || $hook === 'post-new.php' || $hook === 'site-editor.php') {
            $this->register_and_enqueue_style();
            wp_enqueue_script('tinyjpfont-quicktags', plugins_url('assets/quicktags.js', __FILE__), array('quicktags'), self::VERSION, true);
        }
    }
    public function get_custom_fonts() {
        $items = array();
        foreach (tinyjpfont_choices((string) get_option(self::OPT_FONT_MODE, '0') === '1') as $font) { $items[] = $font['label'] . '=' . $font['family']; }
        return ';' . implode(';', $items);
    }
    public function load_custom_fonts($init) {
        $extended=isset($init['extended_valid_elements']) && is_string($init['extended_valid_elements']) ? $init['extended_valid_elements'] : '';
        $init['extended_valid_elements']=trim($extended . ',tinyjpfontnoto[class]', ',');
        $custom=isset($init['custom_elements']) && is_string($init['custom_elements']) ? $init['custom_elements'] : '';
        $init['custom_elements']=trim($custom . ',~tinyjpfontnoto', ',');
        $urls = empty($init['content_css']) ? array() : explode(',', $init['content_css']);
        $urls[] = $this->style_url();
        if (function_exists('tinyjpfont_local_face_css')) {
            // WordPress _WP_Editors::_parse_init wraps this in a JS string without escaping it.
            $local=wp_json_encode(tinyjpfont_local_face_css(),JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT);
            $init['content_style']=(isset($init['content_style'])?$init['content_style']:'').substr($local,1,-1);
        }
        $init['content_css'] = implode(',', array_unique($urls));
        $existing = isset($init['font_formats']) ? $init['font_formats'] : self::DEFAULT_FONTS;
        $init['font_formats'] = implode(';', array_unique(array_filter(explode(';', $existing . $this->get_custom_fonts()))));
        return $init;
    }
    public function customize_font_sizes($init) {
        if (empty($init['fontsize_formats'])) { $init['fontsize_formats'] = '10px 12px 14px 16px 18px 20px 24px 28px 32px 36px 42px 48px'; }
        return $init;
    }
    public function custom_tiny_mce_style_formats($init) {
        $formats = isset($init['style_formats']) ? json_decode($init['style_formats'], true) : array();
        if (!is_array($formats)) { return $init; }
        foreach (array('Noto Sans Japanese'=>'noto','Huifont'=>'huiji') as $title=>$class) {
            $found = false;
            foreach ($formats as $format) { if (isset($format['classes']) && $format['classes'] === $class) { $found = true; } }
            if (!$found) { $formats[] = array('title'=>$title,'block'=>'div','classes'=>$class,'wrapper'=>true); }
        }
        $init['style_formats'] = wp_json_encode($formats);
        return $init;
    }
    public function buttons($buttons) {
        // Advanced Editor Tools intentionally owns its toolbar layout.
        if (class_exists('Tinymce_Advanced')) { return $buttons; }
        return array_values(array_unique(array_merge($buttons, array('fontselect','fontsizeselect'))));
    }
    public function add_default_font() {
        $font = tinyjpfont_font(get_option(self::OPT_DEFAULT_FONT, 'Noto Sans Japanese'));
        if (!$font) { $font = tinyjpfont_font('noto'); }
        add_editor_style(add_query_arg('fn', rawurlencode($font['family']), plugins_url('default-font-css.php', __FILE__)));
    }
}
new JapaneseFontTinyMCE();
