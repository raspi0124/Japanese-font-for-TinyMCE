<?php
header('Content-type: text/css; charset=UTF-8');

$fontname = 'Noto Sans Japanese';

if (isset($_GET['fn'])) {
	$requested_font = strtolower(trim((string) $_GET['fn']));
	$requested_font = preg_replace('/[^a-z\s]/', '', $requested_font);
	$font_map = [
		'noto' => 'Noto Sans Japanese',
		'noto sans japanese' => 'Noto Sans Japanese',
		'huifont' => 'Huifont',
		'kokorom' => 'kokorom',
	];
	if (isset($font_map[$requested_font])) {
		$fontname = $font_map[$requested_font];
	}
}
?>
body#tinymce.wp-editor {
font-family: <?php echo htmlspecialchars($fontname, ENT_QUOTES, 'UTF-8'); ?> !important;
}
