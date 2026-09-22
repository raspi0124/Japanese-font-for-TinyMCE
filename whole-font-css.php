<?php
header('Content-Type: text/css; charset=UTF-8');
header('X-Content-Type-Options: nosniff');
require_once __DIR__ . '/includes/fonts.php';
$font = tinyjpfont_font(isset($_GET['fn']) ? $_GET['fn'] : '');
if ($font) { echo 'body{font-family:"' . $font['family'] . '",sans-serif;}'; }
