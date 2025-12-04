<?php
/*
Plugin Name: Japanese font for WordPress (Previously: Japanese Font for TinyMCE)
Description: Adds Japanese fonts to Gutenberg and TinyMCE.
Version: 4.30
Author: raspi0124
Author URI: https://raspi0124.dev/
License: GPLv2
*/

/*  Copyright 2017-2024 raspi0124 (email : raspi0124@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main plugin class for Japanese Font TinyMCE
 */
class JapaneseFontTinyMCE {
    // Plugin version
    const VERSION = '4.30';

    // Option keys
    const OPT_CDN_ENABLED = 'tinyjpfont_check_cdn';
    const OPT_FONT_MODE = 'tinyjpfont_select';
    const OPT_GUTENBERG_ENABLED = 'tinyjpfont_gutenberg';
    const OPT_LOAD_IN_FOOTER = 'tinyjpfont_head';
    const OPT_DEFAULT_FONT = 'tinyjpfont_default_font';
    const OPT_WHOLE_FONT = 'tinyjpfont_whole_font';

    // Font configurations
    const LITE_MODE_FONTS = ';ふい字=Huifont;Noto Sans Japanese=Noto Sans Japanese;';
    const FULL_MODE_FONTS = ';ふい字=Huifont;Noto Sans Japanese=Noto Sans Japanese;太字なNoto Sans Japanese=Noto Sans Japanese-900;細字なNoto Sans Japanese=Noto Sans Japanese-100;エセナパJ=esenapaj;ほのか丸ゴシック=honokamaru;こころ明朝体=kokorom;青柳衡山フォントT=aoyanagiT;たぬき油性マジック=tanukiM';
    const DEFAULT_FONTS = 'Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings';

    /** @var array Plugin configuration options */
    private $config = [];

    /** @var string|null Stored style URL */
    private $style_url = null;

    /**
     * Initialize the plugin
     */
    public function __construct() {
        $this->load_dependencies();
        $this->load_config();
        $this->init_hooks();
    }

    private function load_dependencies() {
        include_once plugin_dir_path(__FILE__) . 'settings.php';
        include_once plugin_dir_path(__FILE__) . 'notice.php';
    }

    private function load_config() {
        $this->config = [
            'cdn_enabled' => get_option(self::OPT_CDN_ENABLED),
            'font_mode' => get_option(self::OPT_FONT_MODE),
            'gutenberg_enabled' => get_option(self::OPT_GUTENBERG_ENABLED),
            'load_in_footer' => get_option(self::OPT_LOAD_IN_FOOTER),
            'default_font' => get_option(self::OPT_DEFAULT_FONT),
            'whole_font' => get_option(self::OPT_WHOLE_FONT),
        ];
    }

    /**
     * Initialize WordPress hooks and filters
     */
    private function init_hooks() {
        // Font styles and general configuration
        $this->init_font_styles();
        add_action('init', [$this, 'add_default_font']);
        add_action('init', [$this, 'add_whole_font']);

        // Editor customizations
        $this->init_editor_hooks();

        // Gutenberg support
        if ($this->config['gutenberg_enabled'] === '1') {
            include_once plugin_dir_path(__FILE__) . 'gutenjpfont/gutenjpfont.php';
        }
    }

    /**
     * Initialize editor-specific hooks
     */
    private function init_editor_hooks() {
        // TinyMCE font customizations
        add_filter('tiny_mce_before_init', [$this, 'load_custom_fonts']);
        add_filter('tiny_mce_before_init', [$this, 'customize_font_sizes']);
        add_filter('tiny_mce_before_init', [$this, 'custom_tiny_mce_style_formats']);

        // Editor buttons
        add_filter('mce_buttons', [$this, 'add_font_size_selector']);
        add_filter('mce_buttons', [$this, 'add_original_styles_button']);

        // Quicktags
        add_action('admin_print_footer_scripts', [$this, 'add_quicktags']);
    }

    /**
     * Initialize font styles by registering appropriate WordPress hooks
     */
    private function init_font_styles() {
        $style_url = $this->get_style_url();
        if (!$style_url) {
            return;
        }

        // Always enqueue in admin
        add_action('admin_enqueue_scripts', [$this, 'register_and_enqueue_style']);

        // Front-end enqueuing based on configuration
        $hook = $this->config['load_in_footer'] === '0' ? 'wp_enqueue_scripts' : 'get_footer';
        add_action($hook, [$this, 'register_and_enqueue_style']);

        // Store URL for later use
        $this->style_url = $style_url;
    }

    /**
     * Get the appropriate stylesheet URL based on configuration
     * 
     * @return string|null URL to the stylesheet, or null if invalid configuration
     */
    private function get_style_url() {
        $cdn_enabled = $this->config['cdn_enabled'] === '1';
        $is_lite_mode = $this->config['font_mode'] === '1';

        if ($cdn_enabled) {
            return 'https://cdn.jsdelivr.net/gh/raspi0124/Japanese-font-for-TinyMCE@stable/' . 
                   ($is_lite_mode ? 'addfont_lite.css' : 'addfont.css');
        }

        return plugin_dir_url(__FILE__) . ($is_lite_mode ? 'addfont_lite.css' : 'addfont.css');
    }


    /**
     * Register and enqueue the font stylesheet
     */
    public function register_and_enqueue_style() {
        if (!$this->style_url) {
            return;
        }
        wp_register_style('tinyjpfont-styles', $this->style_url);
        wp_enqueue_style('tinyjpfont-styles');
    }

    /**
     * Get the list of custom fonts based on the current mode
     * 
     * @return string Semicolon-separated list of fonts
     */
    public function get_custom_fonts() {
        return $this->config['font_mode'] === '1' ? self::LITE_MODE_FONTS : self::FULL_MODE_FONTS;
    }

    /**
     * Load custom fonts into TinyMCE
     * 
     * @param array $init TinyMCE init configuration
     * @return array Modified init configuration
     */
    public function load_custom_fonts($init) {
        // Add stylesheet
        $stylesheet_url = plugin_dir_url(__FILE__) . 'addfont.css';
        $init['content_css'] = empty($init['content_css']) 
            ? $stylesheet_url 
            : $init['content_css'] . ',' . $stylesheet_url;

        // Add font formats
        $init['font_formats'] = self::DEFAULT_FONTS . $this->get_custom_fonts();
        return $init;
    }

    /**
     * Customize available font sizes in TinyMCE
     * 
     * @param array $settings TinyMCE settings
     * @return array Modified settings
     */
    public function customize_font_sizes($settings) {
        $settings['fontsize_formats'] = '10px 12px 14px 16px 18px 20px 24px 28px 32px 36px 42px 48px';
        return $settings;
    }

    /**
     * Add font size selector button to TinyMCE
     * 
     * @param array $buttons Array of TinyMCE buttons
     * @return array Modified array of buttons
     */
    public function add_font_size_selector($buttons) {
        array_push($buttons, 'fontsizeselect');
        return $buttons;
    }

    /**
     * Add quicktag buttons for Japanese fonts in text editor
     */
    public function add_quicktags() {
        if (!wp_script_is('quicktags')) {
            return;
        }
        ?>
        <script>
            QTags.addButton('tinyjpfont-noto', 'Noto Sans Japanese', '<span style="font-family: Noto Sans Japanese;">', '</span>');
            QTags.addButton('tinyjpfont-huiji', 'ふい字', '<span style="font-family: Huifont;">', '</span>');
        </script>
        <?php
    }

    /**
     * Add custom style formats to TinyMCE
     * 
     * @param array $settings TinyMCE settings
     * @return array Modified settings
     */
    public function custom_tiny_mce_style_formats($settings) {
        $style_formats = [
            [
                'title' => 'Noto Sans Japanese',
                'block' => 'div',
                'classes' => 'noto',
                'wrapper' => true,
            ],
            [
                'title' => 'Huifont',
                'block' => 'div',
                'classes' => 'huiji',
                'wrapper' => true,
            ],
        ];
        $settings['style_formats'] = json_encode($style_formats);
        return $settings;
    }

    /**
     * Add font selector button to TinyMCE toolbar
     * 
     * @param array $buttons Array of toolbar buttons
     * @return array Modified array of buttons
     */
    public function add_original_styles_button($buttons) {
        array_splice($buttons, 1, 0, 'fontselect');
        return $buttons;
    }

    /**
     * Add default font styles to the editor
     */
    public function add_default_font() {
        if (!is_admin()) {
            return;
        }

		$font_name = $this->config['default_font'];
		$font_to_load = !empty($font_name) ? $font_name : 'Noto Sans Japanese';
		$default_font_url = add_query_arg(
			['fn' => $font_to_load],
			plugin_dir_url(__FILE__) . 'default-font-css.php'
		);

		add_editor_style($default_font_url);
		wp_register_style('tinyjpfont-default-font', $default_font_url);
		wp_enqueue_style('tinyjpfont-default-font');
	}

    /**
     * Add font styles to the entire site
     */
    public function add_whole_font() {
        $font_name = $this->config['whole_font'];
        if (empty($font_name) || $font_name === 'noselect') {
            return;
        }

		$whole_font_url = add_query_arg(
			['fn' => $font_name],
			plugin_dir_url(__FILE__) . 'whole-font-css.php'
		);
		wp_register_style('tinyjpfont-whole-font', $whole_font_url);
		wp_enqueue_style('tinyjpfont-whole-font');
	}
}

// Initialize the plugin
new JapaneseFontTinyMCE();
