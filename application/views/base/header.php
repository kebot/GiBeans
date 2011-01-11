<?php defined('SYSPATH') or die('No direct script access.'); ?>
<div class="span-24">
    <div class="span-14"><h1 class='span-4'>精弘读书</h1></div>
    <div class="span-10 last">
        <form action='<?php echo URL::site('book/search');?>'>
            <select name="type">
                <option value="local">已有的资源</option>
                <option value="all">所有书籍数据</option>
            </select>
            <input name="title" type='text' />
            <input type='submit' value='search' />
        </form>
    </div>
</div>
<hr>