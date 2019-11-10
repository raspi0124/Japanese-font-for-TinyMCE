<?php
	header("Content-type: text/css; charset: UTF-8");
	$fontname = $_GET["fn"];
  if($fontname == "Noto" or $fontname == "noto"){
		$fontname = "Noto Sans Japanese";
  }
	if (!isset($_GET["fn"])) {
		//なぜかデフォルトフォントが設定されてないのにここに迷い込んできた場合はとりあえずNotoを割り当て
	$fontname = "Noto Sans Japanese";
	}else{
	}
?>
body#tinymce.wp-editor {
    font-family: <?php echo $fontname; ?> !important;
}
