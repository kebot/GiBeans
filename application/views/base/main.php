<?php defined('SYSPATH') or die('No direct script access.');?>
<!DOCTYPE html> 
<html lang="en"> 
	<head> 
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
		<title><?php echo $title; ?> &bull; 精弘阅读</title> 
                <?php echo HTML::style('css/screen.css'); ?>
		<style> 
body {
	padding: 100px 0 0 0;
	background: #dfe1e3;
	text-shadow: 0 1px 2px #fdfdfd;
	font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, sans-serif;
	color: #363a4f;
}
h1 {
	font-family: "HelveticaNeue-UltraLight", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, sans-serif;
}
.icon{
	width:256px;
	height:256px;
}
		</style> 
	</head>
	<body class="container">
		<?php echo $header; ?>
            <hr>
                    <?php echo $pagination;?>
            <hr>
                <?php echo $content;?>
            <hr>
                    <?php echo $pagination;?>
            <hr>
                <?php echo $footer;?>
	</body> 
</html>
