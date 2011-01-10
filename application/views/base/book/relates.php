<?php defined('SYSPATH') or die('No direct script access.'); ?>
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