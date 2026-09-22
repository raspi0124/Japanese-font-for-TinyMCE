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
    <p><strong><?php esc_html_e('5.00系の主な変更（4.30から）','japanese-font-for-tinymce'); ?></strong></p>
    <p><?php esc_html_e('フォント配信を復旧し、WordPress標準のフォント選択とFont Libraryに対応しました。対応ブロックの「タイポグラフィ → フォント」で日本語書体を選べます。Font Libraryでは必要な書体だけをサイトに保存できます。','japanese-font-for-tinymce'); ?></p>
    <p><?php esc_html_e('ブロックエディタ・Classic Editorの編集や保存、Lite／CDN／ヘッダー・フッター設定の不具合を修正しました。ブロックテーマや他プラグインとの連携を改善し、配信診断を追加しています。','japanese-font-for-tinymce'); ?></p>
    <p><?php esc_html_e('従来の設定画面・独自ブロック・書式ボタンは引き続き使えます。既存記事と保存済み設定を引き継ぎ、ブロックエディタ対応は設定未保存の場合のみ初期状態で有効になります。','japanese-font-for-tinymce'); ?></p>
    <p><?php esc_html_e('入力検証と依存関係を見直し、旧バージョンを含む5環境で動作を確認しました。詳しい変更内容は変更履歴をご覧ください。','japanese-font-for-tinymce'); ?></p>
    <p><a href="<?php echo esc_url(plugins_url('readme.txt', __FILE__)); ?>"><?php esc_html_e('変更履歴を読む（readme.txt）','japanese-font-for-tinymce'); ?></a></p>
    </div>
    <?php
}
