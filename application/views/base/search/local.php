<div class="span-24">
    <div class="span-8">
        <img src="<?php echo $book->link['largeimage'] ;?>" />
    </div>
    <div class="span-16 last">
        <h2><a href="#"><?php echo $book->title; ?></a></h2>
        <div class="span-16">
            <img class="span-2" src="<?php echo URL::base().'icons/'.$file->ext.'.png';?>" />
            <h5><?php echo substr($file->size/1024/1024,0,3);?> MB</h5>
            <h5><?php echo $file->download_count;?> 人下载</h5>
        </div>
        <hr>
        <div class="span-16">
            <h4><?php echo $book->author[0] ?>/ <?php echo Arr::get($book->attribute,'subtitle');?> / <?php echo Arr::get($book->attribute,'publisher');?> / <?php echo Arr::get($book->attribute,'pubdate');?> / <?php echo Arr::get($book->attribute,'price');?></h4>
            <h5>
                <?php for($count = 1;$count<($book->rating['average']/2);$count++): ?>
                <img style="width:1.5em;height:1.5em;" src="<?php echo URL::base().'icons/woodstar.png';?>" />
                <?php endfor; ?>
                <?php echo $book->rating['average'] ?> (豆瓣上<?php echo $book->rating['numRaters'] ?>人评价)</h5>
            <h3><a href="<?php echo $book->link['alternate'] ;?>">转到豆瓣</a></h3>
        </div>
    </div>
</div>

