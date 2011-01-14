<?php defined('SYSPATH') or die('No direct script access.'); ?>
<div class="span-24">
    <div class="span-14">
        <h1 class='span-4'><a href="<?php echo URL::site('book'); ?>">精弘读书</a></h1>
        <?php if( $user->isLogin() ){
            print $user->getUsername();
            print HTML::anchor('book/logout',' 退出');
        } else {
            print HTML::anchor('book/login','登录');
        }
        ?>
    </div>
    <div class="span-10 last">
        <form action='<?php echo URL::site('book/search');?>'>
            <select name="type">
                <option value="local">可下载资源</option>
                <option value="all">书籍数据库</option>
            </select>
            <input name="title" type='text' value="<?php isset($_GET['title']) AND print($_GET['title']); ?>" />
            <input type='submit' value='search' />
        </form>
    </div>
</div>
<hr>