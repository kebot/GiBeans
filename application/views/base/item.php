<?php defined('SYSPATH') or die('No direct script access.');?>
<!DOCTYPE html> 
<html lang="en"> 
	<head> 
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
		<title>精弘阅读 &bull; <?php echo $i->title; ?></title> 
                <?php echo HTML::style('css/screen.css'); ?>
		<style> 
body {
	padding: 100px 0 0 0;
	background: #dfe1e3;
	text-shadow: 0 1px 2px #fdfdfd;
	text-align: center;
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
		<div class="span-3">&nbsp</div>
		<div id="content" class="span-18">
			<div class="span-8"><img width="300" height="450" src="<?php echo $i->link['largeimage']; ?>" /></div>
			<div class="span-10 last">
                            <?php foreach($i->attribute as $key=>$value){ ?>
                            <span><?php echo __($key); ?></span> <?php echo $value; ?><br />
                            <?php }?>
			</div>
                        <hr>
                        <p><?php echo $i->summary; ?></p>
					<h1>请选择你需要的文件版本下载:</h1>
		</div>
		<div class="span-3 last">&nbsp</div>
<?php
echo $other;
?>
	</body> 
</html>
