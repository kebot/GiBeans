<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Book extends Controller_Template {

    public $template = 'base/main';
    /**
     *
     * @var User
     */
    protected $user;
    /**
     *
     * @var Model_Base_Book
     */
    protected $client;
    /**
     * @var int
     */
    protected $book_id = NULL;

    public function before() {
        parent::before();
        
        // set the default language
        //@todo will support other language
        Kohana_I18n::lang('zh-cn');
        
        $this->user = new User();
        // @todo debug
        $this->book_id = $this->request->param('id', NULL);
        $this->client = new Base_Book($this->book_id);
        
        
        // template
        $this->template->title = '';
        $this->template->header = View::factory('base/header')->bind('user', $this->user)->render();
        $this->template->content = '';
        $this->template->footer = View::factory('base/footer')->render();
        $this->template->pagination = '';

        //Cache::instance('book')->delete_all();
    }

    public function action_index() {
        $handle = DB::select()
                        ->from('books')
                        ->order_by('time', 'DESC')
                        ->limit(12)
                        ->execute('base');
        $newcoming = $this->view_list($handle , 6);

        $handle = DB::select()
                        ->from('books')
                        ->order_by('download_count', 'DESC')
                        ->limit(12)
                        ->execute('base');
        $hotdownload = $this->view_list($handle , 6);


        $data = array(
            'tags' => Base_Tags::popularTags(),
            'newcoming' => $newcoming,
            'hotdownload' => $hotdownload,
        );

        $this->template->content = View::factory('base/index', $data)
                        ->render();

        $this->template->title = __('index');
        
    }

    /**
     *
     * @param Database_Result $handle
     * @return type 
     */
    public function view_list($handle , $maxPreLine=3) {
        $view = '';
        $flug = 1;
        while ($handle->valid()) {
            if($flug == $maxPreLine){
                $flug = 1;
                $last = TRUE;
            } else {
                $flug++;
                $last = FALSE;
            }
            $book = new Base_Book($handle->get('douban_id'));
            $infos = $book->infos();
            $view .= View::factory('base/list')->bind('i', $infos)->bind('last', $last)->render();
            

            $handle->next();
        }
        return $view;
    }

    public function action_search() {
        // set the limit items per page
        $limit = 10;

        // Variable for pagination
        $total = 0;
 
        // get the get query
        $get = $_GET;
        foreach (array('title', 'ext', 'order', 'direction', 'page', 'type') as $key) {
            $$key = Arr::get($get, $key);
        }

        if ($type == 'all') {
            // Search form douban api
            $result = $this->client->search(urlencode($title), $page, $limit);
            //print_r($result);
            
            $total = isset($result->total) ? $result->total : 0;

            // @todo rewrite this to Module Douban API
            if ($total) {
                foreach ($result->entry as $book) {
                    $view = View::factory('base/search/local');
                    $view->book = $book;
                    $this->template->content .= $view->render();
                }
            } else {
                $this->template->content = 'Sorry , No found';
            }
        } else {
            $handle = DB::select()->from('books')->where('title', 'LIKE', '%' . $title . '%')->limit($limit);
            $ext AND $handle->and_where('ext', '=', $ext);
            $order AND $handle->order_by($order, $direction);
            $page AND $handle->offset($limit * ($page - 1));
            $handle = $handle->execute('base');

            $this->template->title = __('search') . $title . 'result';

            $total = $handle->count();

            while ($handle->valid()) {
                $view = View::factory('base/search/local');
                $file = Upload_Book::get($handle);
                $infos = $file->book()->infos();
                $view->file = $file;
                $view->book = $infos;
                $this->template->content .= $view->render();
                $handle->next();
            }
            if ($total == 0) {
                $get['type'] = 'all';
                $this->template->content = __('no result for download') . HTML::anchor(URL::site('book/search', TRUE) . URL::query($get), __('search for upload'));
            }
        }

        // set for pagination
        $pagination = Pagination::factory(array(
                    'current_page' => array(
                        'source' => 'query_string',
                        'key' => 'page'
                    ),
                    'total_items' => $total,
                    'items_per_page' => 5,
                    'auto_hide' => FALSE,
                ));
        $this->template->pagination = $pagination->render();
    }
    
    
    // Single tag
    public function action_tag() {
        // the tag id
        $id = $this->request->param('id', NULL);
        
        $items = DB::select('d_id')->from('relationship')->where('t_id', '=', $id)->execute('base')->as_array();
        if($items){
            
            foreach($items as $id){
                $id = $id['d_id'];
                $view = View::factory('base/search/local');
                $view->file = NULL;
                $view->book = Base_Book::factory($id)->infos();
                $this->template->content .= $view->render();
            }
        } else {
            $this->template->content = __('no result found');
        }
    }


    public function action_subject() {
        $max = 30000000;
        $book_info = $this->client->infos();
        $upload_url = URL::site('book/upload/' . $this->book_id, true);
        View::bind_global('upload_url', $upload_url);
        View::bind_global('i', $book_info);

        //$upload = View::factory('base/upload')->bind('action', $upload_url)->bind('max_file_size', $max)->render();
        $this->template->content = View::factory('base/item')
                        ->render();
        $this->template->title = $book_info->title;
    }

    public function action_upload() {
        if (!$this->book_id) {
            $this->request->redirect('/book');
        }
        $uid = $this->user->getUid();
        
        if ($uid <= 0) {
                $this->template->content = 'please login first';
                return;
        }

        if (isset($_FILES['book'])) {

            $infos = $this->client->infos();

            //limit the description to 140
            $description = Kohana_Text::limit_chars($_POST['description'], 140);

            $upload = Upload_Book::upload($_FILES, $this->client->infos(), $uid, $description);

            // Update the tags from douban
            if (!$infos->files) {
                Upload_Book::insertTags($infos);
            }

            if ($upload == -1) {
                $message = __('File Exists');
            } elseif ($upload == -2) {
                $message = __('Wrong File Type');
            } elseif ($upload == -3) {
                $message = __('Unknow Error');
            } else {
                $message = __('Upload Success');
            }

            Cache::instance('book')->delete($this->book_id);

            $message .= HTML::anchor('book/subject/' . $this->book_id, __('return'));
            $this->template->content = $message;
        } else {
            $url = URL::site($this->request->uri);
            $max = 30000000;
            $this->template->content = View::factory('base/upload')->bind('action', $url)->bind('max_file_size', $max)->render();
        }
    }

    public function action_download() {
        /*
          $path = APPPATH . 'upload' . DIRECTORY_SEPARATOR . 'book' . DIRECTORY_SEPARATOR . '686de452126181ebc16ca62ba45f8d17aa6e7314使用Subversion进行版本控制.chm';
         */
        $fileid = $_GET['file'];
        if (!is_numeric($fileid)) {
            $this->request->response = __('no fileid.');
        }

        $file = new Upload_Book($fileid);

        Base_Download::add($fileid, $this->user->getUid());

        if ($file->douban_id == $this->book_id) {
            $this->request->send_file($file->path, $this->client->infos()->title . '.' . $file->ext);
        }
    }

    // todo 验证登陆
    public function action_login()
    {
        if($this->user->isLogin()){
            $this->template->content = __('alread login');
            return;
        }
        
        
        if(isset ($_POST['username']) and isset($_POST['password'])){
            $statu = $this->user->login($_POST['username'], $_POST['password']);
            if($statu>0){
                $this->request->redirect('book');
                return;
            }
            
            switch ($statu) {
            case -1:
                $message = __('user not exist');
                break;
            case -2:
                $message = __('wrong password');
                break;
            case -3:
                $message = __('wrong security question');
                break;
            case -4:
                $message = __('wrong username/password format');
            default :
                $message = __('Unknow Error');
                break;
            }
            
            $this->template->content = $message;
        } else {
            $this->template->content .= View::factory('login')->render();
        }
    }
    
    //
    public function action_logout()
    {
        $this->user->logout();
        $this->request->redirect('book');
    }


    
    /**
     * @todo remove debug
     */
    public function action_debug() {
        //Kohana_I18n::lang('zh-cn');
        $isbn = '9787508608686';
        Base_Book::find_in_library('9787508608686');
    }

}

?>
