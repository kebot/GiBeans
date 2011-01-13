<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="span-24">
    共 <?php echo $total; ?> 条结果 , 第<?=$index?>页.
    <?php for($i=1,$flug = TRUE;$i<=$total/10;$i++):
        if( ($i>2 && $i<$total/10-2) && ($i<$index-4 || $i>$index+4) ):
            if($flug){
                echo '.  ...  .';
                $flug = FALSE;
            }
            continue;
        else:
            if($i==$index){
                echo $i;
            } else {
        ?>
            <a href="<?php echo url::site('book/search') . url::query(array('title'=>$_GET['title'],'page'=>$i,'type'=>$_GET['type']));  ?>"><?php echo $i; ?></a>|
    <?php 
            }
    endif;
    endfor;?>
</div>