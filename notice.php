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
    <ul class="tinyjpfont-news-list">
    <li><?php esc_html_e('フォント配信を復旧し、Lite／CDN／ヘッダー・フッター設定の不具合を修正。','japanese-font-for-tinymce'); ?></li>
    <li><?php esc_html_e('WordPress標準のフォント選択とFont Libraryに対応。必要な書体をサイトに保存して配信できます。','japanese-font-for-tinymce'); ?></li>
    <li><?php esc_html_e('ブロックエディタ・Classic Editorの編集・保存を修正し、ブロックテーマや他プラグインとの連携を改善。','japanese-font-for-tinymce'); ?></li>
    <li><?php esc_html_e('配信診断を追加し、入力検証と依存関係を見直し。旧バージョンを含む5環境で動作を確認。','japanese-font-for-tinymce'); ?></li>
    <li><?php esc_html_e('既存記事と保存済み設定を引き継ぎ、従来の設定画面・独自ブロック・書式ボタンも維持。','japanese-font-for-tinymce'); ?></li>
    <li><?php esc_html_e('ブロックエディタ対応は、設定未保存の場合のみ初期状態で有効化。','japanese-font-for-tinymce'); ?></li>
    </ul>
    <p><a href="<?php echo esc_url(plugins_url('readme.txt', __FILE__)); ?>"><?php esc_html_e('変更履歴を読む（readme.txt）','japanese-font-for-tinymce'); ?></a></p>
    </div>
    <?php
}
