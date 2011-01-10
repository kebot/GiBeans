<?php defined('SYSPATH') or die('No direct script access.'); ?>
<div class="span-3">&nbsp</div>
<div id="content" class="span-18">
    <div class="span-8"><img width="300" height="450" src="<?php echo $i->link['largeimage']; ?>" /></div>
    <div class="span-10 last">
        <?php foreach ($i->attribute as $key => $value) { ?>
            <span><?php echo __($key); ?></span> <?php echo $value; ?><br />
        <?php } ?>
    </div>
    <hr>
    <p><?php echo $i->summary; ?></p>
    <h1>请选择你需要的文件版本下载:</h1>
</div>
<div class="span-3 last">&nbsp</div>
<?php
if($i->files):
foreach ($i->files as $file):?>
<div class="span-6 last">
    <div class="span-5"><?php echo HTML::anchor($file->download_url,HTML::image('icons/'.$file->ext.'.png')); ?></div>
    <div class="span-5 last">文件大小: <?php echo substr($file->size/1024/1024,0,3);  ?>MB</div>
</div>
<?php endforeach;
else:
    echo '暂时没有用户上传文件...';
    
endif;
?>