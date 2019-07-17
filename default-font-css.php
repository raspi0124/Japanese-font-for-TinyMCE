<?php
	header("Content-type: text/css; charset: UTF-8");
	$fontname = $argv[1];
	if(isset($_GET['fn']) && $_GET['fn'] != ""){
    $fontname = $_GET['fn'];
	}else{
		$fontname = "Noto Sans Japanese";
	}
?>
body#tinymce.wp-editor {
    font-family: <?php echo $fontname; ?> !important;
}
