<?php defined('SYSPATH') or die('No direct script access.'); ?>

<h1 style="color:red;">注意,产品为内部测试,所有数据将会随时被删除... -- 听临</h1>

<div class="span-24 last">
    <h3>登陆请使用精弘的帐号密码,可能有些新注册的帐号没有被导入</h3>
    <h3>请在右上角搜索书籍数据库,进入要上传的书籍点击链接上传相应书籍(注意:需要登陆后才能上传)</h3>
    <img src="<?php echo URL::base().'icons/upload.png' ?>">
</div>
<div class="span-12">
    <h2><a href="<?php echo URL::site('book/search').'?type=local&order=time&direction=desc' ?>">最新上传- - - -</a></h2>
    <?php echo $newcoming; ?>
</div>

<div class="span-12 last">
    <h2><a href="<?php echo URL::site('book/search').'?type=local&order=download_count&direction=desc' ?>">最热下载- - - -</a></h2>
    <?php echo $hotdownload; ?>
</div>

<dv class="span-12">
    <h2>热门标签:</h2>
    <?php
    foreach ($tags as $tag):
        echo $tag['value'];
        echo $tag['popular'];
        echo '<br>';
    endforeach; ?>
</div>