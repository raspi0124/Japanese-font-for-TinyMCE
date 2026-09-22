<?php
require '/var/www/html/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/upgrade.php';
if (!is_blog_installed()) { wp_install('Japanese Font Compatibility', 'audit-admin', 'audit@example.test', true, '', getenv('AUDIT_PASSWORD')); }
update_option('home', getenv('AUDIT_URL'));update_option('siteurl', getenv('AUDIT_URL'));
update_option('active_plugins', array('japanese-font-for-tinymce/japanese-tinymce.php'));
foreach (array('tinyjpfont_head'=>'0','tinyjpfont_select'=>'0','tinyjpfont_gutenberg'=>'1','tinyjpfont_check_cdn'=>'0','tinyjpfont_whole_font'=>'noselect') as $k=>$v) { update_option($k,$v); }
wp_set_current_user(1);
if (!get_page_by_title('Japanese font specimen', OBJECT, 'post')) {
    $content = '<!-- wp:paragraph --><p>日本語の文章。ひらがな カタカナ 漢字 ABC 123。</p><!-- /wp:paragraph -->';
    foreach (array('noto','huiji') as $f) { $content .= '<!-- wp:tinyjpfont/'.$f.' --><p class="wp-block-tinyjpfont-'.$f.'">既存の記事 <strong>太字</strong> <a href="https://example.com/">リンク</a><br>改行を保持</p><!-- /wp:tinyjpfont/'.$f.' -->'; }
    $content .= '<!-- wp:paragraph --><p><tinyjpfontNoto class="wp-block-tinyjpfont-noto">旧インライン書式</tinyjpfontNoto></p><!-- /wp:paragraph -->';
    $id=wp_insert_post(array('post_title'=>'Japanese font specimen','post_status'=>'publish','post_author'=>1,'post_content'=>$content));
    update_option('tinyjpfont_test_post',$id);
}
if (!username_exists('audit-author')) { $id=wp_create_user('audit-author',getenv('AUDIT_PASSWORD'),'author@example.test');$u=new WP_User($id);$u->set_role('author'); }
echo json_encode(array('wordpress'=>$wp_version,'php'=>PHP_VERSION,'post'=>get_option('tinyjpfont_test_post')));
