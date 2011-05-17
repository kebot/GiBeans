<?php defined('SYSPATH') or die('No direct script access.'); ?>
<div class="span-4 <?php $last and print('last'); ?>">
    <a href="<?php echo URL::site('book/subject/'.$i->id); ?>">
        <image src="<?php echo $i->link['largeimage']; ?>" />
    </a>
    <h4><?php echo $i->title; ?></h4>
</div>