<?php defined('SYSPATH') or die('No direct script access.'); ?>
<div id="content" class="span-24">
    <div class="span-8">
        <img width="300" height="450" src="<?php echo $i->link['largeimage']; ?>" />
    </div>
    <div class="span-16 last">
        <?php foreach ($i->attribute as $key => $value) { ?>
            <span><?php echo __($key); ?></span> <?php echo $value; ?><br />
        <?php } ?>
    </div>
    <hr>
    <p><?php echo $i->summary; ?></p>
    <h1>请选择你需要的文件版本下载:</h1>
<hr>
<?php
if($i->files):
foreach ($i->files as $key => $file):?>
<div class="span-8 <?php if( ($key+1)%3 == 0) echo 'last'; ?>">
    <div>
        <a href="<?php echo $file->download_url;?>">
            <?php echo HTML::image('icons/'.$file->ext.'.png'); ?>
        </a>
    </div>
    <hr>
    <div>
        文件大小: <?php echo substr($file->size/1024/1024,0,3);  ?>MB<br>
        备注: <?php echo $file->description; ?><br>
        <a href="#">报告不符合的资源...</a>
    </div>
</div>
<?php endforeach;
else:
    echo '暂时没有用户上传文件...';
endif;
?>
<hr>

<h2><a href="<?php echo URL::site('book/upload/'.$i->id); ?>">我来上传</a></h2>

</div>