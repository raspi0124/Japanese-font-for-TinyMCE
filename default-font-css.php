<?php
header('Content-Type: text/css; charset=UTF-8');
header('X-Content-Type-Options: nosniff');
require_once __DIR__ . '/includes/fonts.php';
$font = tinyjpfont_font(isset($_GET['fn']) ? $_GET['fn'] : 'noto');
if (!$font) { $font = tinyjpfont_font('noto'); }
echo 'body#tinymce.wp-editor{font-family:"' . $font['family'] . '",sans-serif !important;}';
