<?php defined('SYSPATH') or die('No direct script access.'); ?>
<div class="span-24">
    <div class="span-14"><h1 class='span-4'><a href="<?php echo URL::site('book'); ?>">精弘读书</a></h1></div>
    <div class="span-10 last">
        <form action='<?php echo URL::site('book/search');?>'>
            <select name="type">
                <option value="local">可下载资源</option>
                <option value="all">书籍数据库</option>
            </select>
            <input name="title" type='text' />
            <input type='submit' value='search' />
        </form>
    </div>
</div>
<hr>