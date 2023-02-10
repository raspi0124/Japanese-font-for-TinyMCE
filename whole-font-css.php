<?php
header("Content-type: text/css; charset: UTF-8");
if (!isset($_GET["fn"])) {
	//なぜかデフォルトフォントが設定されてないのにここに迷い込んできた場合は""
	$fontname = "";
}
$fnis = $_GET["fn"];
if ($fnis == "Noto" or $fnis == "noto") {
	$fontname = "Noto Sans Japanese";
}
if ($fnis == "Huifont") {
	$fontname = "Huifont";
}
if ($fnis == "kokorom") {
	$fontname = "kokorom";
} else {
	$fontname = "";
}
?>
body {
font-family: <?php echo $fontname; ?>;
}