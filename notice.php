<?php
if (!defined('ABSPATH')) { exit; }
// Existing dismiss metadata is retained; no recurring upgrade notices are added.
function tinyjpfont_notice_dismiss_url($id) { return wp_nonce_url(add_query_arg($id,'true',remove_query_arg($id)), 'tinyjpfont_dismiss_'.$id); }
add_action('admin_init', function () {
    if (!is_user_logged_in()) { return; }
    $ids=array('tinyjpfont-fix430-notice-dismissed'=>'tinyjpfont_fix430_notice_dismissed','tinyjpfont-gutenberg-notice-dismissed'=>'tinyjpfont_gutenberg_notice_dismissed','tinyjpfont-install-notice-dismissed'=>'tinyjpfont_install_notice_dismissed','tinyjpfont-advanced-warning-dismissed'=>'tinyjpfont_advanced_warning_dismissed');
    foreach ($ids as $query=>$meta) { if (isset($_GET[$query]) && check_admin_referer('tinyjpfont_dismiss_'.$query)) { update_user_meta(get_current_user_id(),$meta,'true'); } }
});
