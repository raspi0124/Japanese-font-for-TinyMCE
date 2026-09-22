<?php
if (!defined('ABSPATH')) { exit; }
add_action('admin_menu', 'tinyjpfont_add_pages');
add_action('admin_init', 'tinyjpfont_save_settings');
function tinyjpfont_add_pages() {
    add_menu_page('Japanese Font for WordPressの設定', 'Japanese Font for WordPressの設定', 'manage_options', 'tinyjpfont', 'tinyjpfont_options_page', plugins_url('icon.png', __FILE__));
}
function tinyjpfont_save_settings() {
    if (!isset($_GET['page']) || $_GET['page'] !== 'tinyjpfont' || !isset($_POST['tinyjpfont_select'])) { return; }
    if (!current_user_can('manage_options')) { wp_die(esc_html__('You are not allowed to change these settings.', 'japanese-font-for-tinymce'), '', array('response'=>403)); }
    check_admin_referer('tinyjpfont_settings_action');
    foreach (array('tinyjpfont_select','tinyjpfont_head') as $key) {
        update_option($key, isset($_POST[$key]) && $_POST[$key] === '1' ? '1' : '0');
    }
    foreach (array('tinyjpfont_check_cdn','tinyjpfont_gutenberg') as $key) { update_option($key, isset($_POST[$key]) && $_POST[$key] === '1' ? '1' : '0'); }
    foreach (array('tinyjpfont_default_font'=>'Noto Sans Japanese','tinyjpfont_whole_font'=>'noselect') as $key=>$default) {
        $value = isset($_POST[$key]) && is_string($_POST[$key]) ? wp_unslash($_POST[$key]) : '';
        $font = tinyjpfont_font($value);
        update_option($key, $font ? $font['family'] : $default);
    }
    wp_safe_redirect(add_query_arg(array('page'=>'tinyjpfont','settings-updated'=>'true'), admin_url('admin.php')));
    exit;
}
function tinyjpfont_options_page() {
    if (!current_user_can('manage_options')) { return; }
    ?>
    <div class="wrap tinyjpfont-settings">
    <h1>Japanese Font for WordPress</h1>
    <?php if (isset($_GET['settings-updated'])) { ?><div class="notice notice-success"><p><?php esc_html_e('設定を保存しました。','japanese-font-for-tinymce'); ?></p></div><?php } ?>
    <?php tinyjpfont_settings_news(); ?>
    <form method="post">
    <?php wp_nonce_field('tinyjpfont_settings_action'); ?>
    <table class="form-table" role="presentation"><tbody>
    <tr><th><label for="tinyjpfont_select"><?php esc_html_e('フォントロードモード','japanese-font-for-tinymce'); ?></label></th><td>
    <select id="tinyjpfont_select" name="tinyjpfont_select"><option value="0" <?php selected(get_option('tinyjpfont_select','0'),'0'); ?>>Normal</option><option value="1" <?php selected(get_option('tinyjpfont_select','0'),'1'); ?>>Lite</option></select>
    <p class="description"><?php esc_html_e('Liteの基本候補はふい字とNoto Sans Japaneseです。既存記事や全体設定で使用中の書体は引き続き表示します。フォントファイルは使用時に読み込みます。','japanese-font-for-tinymce'); ?></p></td></tr>
    <tr><th><?php esc_html_e('CDNモード','japanese-font-for-tinymce'); ?></th><td><label><input id="tinyjpfont_check_cdn" name="tinyjpfont_check_cdn" value="1" type="checkbox" <?php checked(get_option('tinyjpfont_check_cdn','0'),'1'); ?>><?php esc_html_e('CSSをCDNから読み込む','japanese-font-for-tinymce'); ?></label><p class="description"><?php esc_html_e('オフの場合はCSSをこのサイトから配信します。Font Libraryでインストールした書体はこのサイトから、それ以外はfonts.raspi0124.devから配信されます。','japanese-font-for-tinymce'); ?></p></td></tr>
    <tr><th><label for="tinyjpfont_head"><?php esc_html_e('読み込み場所指定モード','japanese-font-for-tinymce'); ?></label></th><td><select id="tinyjpfont_head" name="tinyjpfont_head"><option value="0" <?php selected(get_option('tinyjpfont_head','0'),'0'); ?>><?php esc_html_e('ヘッダーで読み込む','japanese-font-for-tinymce'); ?></option><option value="1" <?php selected(get_option('tinyjpfont_head','0'),'1'); ?>><?php esc_html_e('フッターで読み込む','japanese-font-for-tinymce'); ?></option></select></td></tr>
    <tr><th><?php esc_html_e('ブロックエディタ(Gutenberg)対応機能の有効化','japanese-font-for-tinymce'); ?></th><td><label><input id="tinyjpfont_gutenberg" name="tinyjpfont_gutenberg" value="1" type="checkbox" <?php checked(get_option('tinyjpfont_gutenberg','1'),'1'); ?>><?php esc_html_e('ブロックエディタへの対応を有効化する','japanese-font-for-tinymce'); ?></label><p class="description"><?php esc_html_e('未設定の場合は有効です。保存済みの無効設定は維持します。従来のブロック・書式ボタンも使えます。無効にしても既存記事のフォント表示は維持します。','japanese-font-for-tinymce'); ?></p></td></tr>
    <?php foreach (array('tinyjpfont_default_font'=>__('デフォルトフォント (TinyMCEエディタ)','japanese-font-for-tinymce'),'tinyjpfont_whole_font'=>__('ウェブサイト全体適用フォント','japanese-font-for-tinymce')) as $key=>$title) { ?>
    <tr><th><label for="<?php echo esc_attr($key); ?>"><?php echo esc_html($title); ?></label></th><td><select id="<?php echo esc_attr($key); ?>" name="<?php echo esc_attr($key); ?>">
    <?php if ($key === 'tinyjpfont_whole_font') { ?><option value="noselect" <?php selected(get_option($key,'noselect'),'noselect'); ?>><?php esc_html_e('選択しない','japanese-font-for-tinymce'); ?></option><?php } ?>
    <?php foreach (tinyjpfont_fonts() as $font) { ?><option value="<?php echo esc_attr($font['family']); ?>" <?php selected(get_option($key,$key==='tinyjpfont_default_font'?'Noto Sans Japanese':'noselect'),$font['family']); ?>><?php echo esc_html($font['label']); ?></option><?php } ?>
    </select></td></tr><?php } ?>
    </tbody></table><?php submit_button(); ?></form>
    <?php if (is_plugin_active('tinymce-advanced/tinymce-advanced.php')) { ?><div class="notice notice-info inline"><p><?php esc_html_e('Advanced Editor Toolsを併用中です。フォント・サイズの選択欄がない場合は「設定 → Advanced Editor Tools」でFont FamilyとFont Sizesをツールバーへ追加してください。配置済みのボタンは変更しません。','japanese-font-for-tinymce'); ?></p></div><?php } ?>
    <?php if (function_exists('wp_register_font_collection')) { ?>
    <h2><?php esc_html_e('標準フォントとFont Library','japanese-font-for-tinymce'); ?></h2>
    <p><?php esc_html_e('ブロックエディタ対応を有効にすると、対応ブロックのタイポグラフィ設定から日本語書体を選べます。WordPressのフォント管理にある「日本語フォント」では、必要な書体だけをこのサイトへ保存できます。従来の選択欄と配信はそのまま利用できます。','japanese-font-for-tinymce'); ?></p>
    <?php $maximum=0;foreach(tinyjpfont_fonts() as $font){$maximum=max($maximum,$font['bytes']);}
    if (wp_max_upload_size() < $maximum) { ?>
    <div class="notice notice-info inline"><p><?php printf(esc_html__('このサイトのアップロード上限は %1$s です。完全版の日本語フォントには最大 %2$s のファイルがあるため、Font Libraryで保存する場合はサーバーのアップロード上限を16MB以上にしてください。R2配信での利用にはこの制限はありません。','japanese-font-for-tinymce'),esc_html(size_format(wp_max_upload_size(),1)),esc_html(size_format($maximum,1))); ?></p></div>
    <?php } } ?>
    <h2><?php esc_html_e('フォント配信の確認','japanese-font-for-tinymce'); ?></h2><p><?php esc_html_e('書体を選んで確認すると、その書体だけを読み込みます。','japanese-font-for-tinymce'); ?></p>
    <select id="tinyjpfont-diagnostic-font" aria-label="<?php esc_attr_e('確認する書体','japanese-font-for-tinymce'); ?>"><?php foreach(tinyjpfont_fonts() as $font) { ?><option value="<?php echo esc_attr($font['family']); ?>"><?php echo esc_html($font['label']); ?></option><?php } ?></select>
    <button type="button" class="button" id="tinyjpfont-diagnostic-run"><?php esc_html_e('配信を確認','japanese-font-for-tinymce'); ?></button><p id="tinyjpfont-diagnostic-result" role="status" aria-live="polite"></p>
    </div>
    <?php
}
add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook !== 'toplevel_page_tinyjpfont') { return; }
    wp_enqueue_style('tinyjpfont-admin', plugins_url('admin.css',__FILE__), array(), JapaneseFontTinyMCE::VERSION);
    wp_enqueue_style('tinyjpfont-diagnostic-fonts', plugins_url('addfont.css',__FILE__), array(), JapaneseFontTinyMCE::VERSION);
    wp_enqueue_script('tinyjpfont-diagnostic', plugins_url('assets/diagnostic.js',__FILE__), array(), JapaneseFontTinyMCE::VERSION, true);
    wp_localize_script('tinyjpfont-diagnostic', 'tinyjpfontDiagnostic', array('loading'=>__('読み込み中…','japanese-font-for-tinymce'),'ok'=>__('読み込みに成功しました。日本語の表示を確認してください。','japanese-font-for-tinymce'),'error'=>__('読み込みに失敗しました。ネットワークまたは配信先を確認してください。','japanese-font-for-tinymce')));
});
