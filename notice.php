<?php
if (!defined('ABSPATH')) { exit; }
// Existing dismiss metadata is retained; no recurring upgrade notices are added.
function tinyjpfont_notice_dismiss_url($id) { return wp_nonce_url(add_query_arg($id,'true',remove_query_arg($id)), 'tinyjpfont_dismiss_'.$id); }
add_action('admin_init', function () {
    if (!is_user_logged_in()) { return; }
    $ids=array('tinyjpfont-fix430-notice-dismissed'=>'tinyjpfont_fix430_notice_dismissed','tinyjpfont-gutenberg-notice-dismissed'=>'tinyjpfont_gutenberg_notice_dismissed','tinyjpfont-install-notice-dismissed'=>'tinyjpfont_install_notice_dismissed','tinyjpfont-advanced-warning-dismissed'=>'tinyjpfont_advanced_warning_dismissed');
    foreach ($ids as $query=>$meta) { if (isset($_GET[$query]) && check_admin_referer('tinyjpfont_dismiss_'.$query)) { update_user_meta(get_current_user_id(),$meta,'true'); } }
});

/** Keep release guidance on the plugin settings page, without recurring dashboard notices. */
function tinyjpfont_settings_news() {
    if (!current_user_can('manage_options')) { return; }
    ?>
    <div class="notice notice-info inline tinyjpfont-news">
    <p><strong><?php esc_html_e('ブロックエディタですぐに日本語フォントを選べるようになりました','japanese-font-for-tinymce'); ?></strong></p>
    <p><?php esc_html_e('5.00-dev.5から、ブロックエディタ対応の初期設定を「有効」に変更しました。新規インストール時や、この設定をまだ保存していないサイトが対象です。','japanese-font-for-tinymce'); ?></p>
    <p><?php esc_html_e('すでに設定を保存しているサイトは、有効・無効のどちらもそのまま引き継ぎます。既存の記事の書体や内容は変更しません。','japanese-font-for-tinymce'); ?></p>
    <p><?php esc_html_e('使い方：段落などのブロックを選択し、右側の「タイポグラフィ → フォント」で書体を選びます。項目が隠れている場合はタイポグラフィのオプションメニューから表示してください。標準のフォント選択がない古いWordPressでは、従来の独自ブロック・書式ボタンを利用できます。','japanese-font-for-tinymce'); ?></p>
    <p><a href="<?php echo esc_url(plugins_url('readme.txt', __FILE__)); ?>"><?php esc_html_e('変更履歴を読む（readme.txt）','japanese-font-for-tinymce'); ?></a></p>
    </div>
    <?php
}
