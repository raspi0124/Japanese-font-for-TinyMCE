<?php
require '/var/www/html/wp-load.php';
require_once ABSPATH.'wp-admin/includes/plugin.php';
$options=array();foreach(array('tinyjpfont_check_cdn','tinyjpfont_select','tinyjpfont_gutenberg','tinyjpfont_head','tinyjpfont_default_font','tinyjpfont_whole_font') as $key){$options[$key]=get_option($key,null);}
$posts=array();foreach(get_posts(array('post_type'=>array('post','page'),'post_status'=>'any','posts_per_page'=>-1)) as $post){$posts[$post->ID]=hash('sha256',$post->post_content);}
echo json_encode(array('version'=>get_plugin_data(WP_PLUGIN_DIR.'/japanese-font-for-tinymce/japanese-tinymce.php')['Version'],'options'=>$options,'posts'=>$posts));
