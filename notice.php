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
    <p><strong><?php printf(esc_html__('バージョン %s のお知らせ','japanese-font-for-tinymce'), esc_html(JapaneseFontTinyMCE::VERSION)); ?></strong></p>
    <p><?php esc_html_e('ブロックエディタ対応は未設定の場合に有効になります。以前に無効として保存した設定は変更しません。対応ブロックを選び、右側の「タイポグラフィ → フォント」から書体を選択できます。項目が見つからない場合はタイポグラフィのオプションメニューを確認してください。従来の独自ブロック・書式ボタンも引き続き使えます。','japanese-font-for-tinymce'); ?></p>
    <p><?php esc_html_e('フォント配信をfonts.raspi0124.devへ移転しました。対応するWordPressではFont Libraryの「日本語フォント」から必要な書体だけをサイトへ保存できます。インストールは任意で、自動ダウンロードは行いません。','japanese-font-for-tinymce'); ?></p>
    </div>
    <?php
}
