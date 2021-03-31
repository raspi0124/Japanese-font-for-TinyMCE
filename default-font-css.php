<?php
	header("Content-type: text/css; charset: UTF-8");
	$fnis = _GET["fn"];
  if($fnis == "Noto" or $fnis == "noto"){
		$fontname = "Noto Sans Japanese";
  }
  if ($fnis == "Huifont") {
	  $fontname = "Huifont";

  }if ($fnis == "kokorom") {
	  $fontname = "kokorom";
  }
	if (!isset(_GET["fn"])) {
		//なぜかデフォルトフォントが設定されてないのにここに迷い込んできた場合はとりあえずNotoを割り当て
	$fontname = "Noto Sans Japanese";
	}else{
	}
?>
body#tinymce.wp-editor {
    font-family: <?php echo $fontname; ?> !important;
}
