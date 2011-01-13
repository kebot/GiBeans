<?php defined('SYSPATH') or die('No direct script access.');

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
    protected $book_id=NULL;

    public function  before() {
        parent::before();
        
        $this->user = new User();
        // @todo debug
        $this->book_id = $this->request->param('id',NULL);
        if($this->book_id){
            $this->client = new Base_Book($this->book_id);
        } else {
            $this->client =  new Douban_API_Book();
        }

        // template
        $this->template->title = '';
        $this->template->header = View::factory('base/header')->render();
        $this->template->content = '';
        $this->template->footer = '';
        
        //Cache::instance('book')->delete_all();
        
        
    }
    /**
     *
     *
     * @todo don't use $_GET array and add pages
     */
    public function action_searchclient()
    {
        $q = Validate::not_empty($_GET['q']);
        $result = $this->client->search($q);
        var_dump($result);
    }

    public function action_index()
    {
        /*
        $files = DB::select()->from('books')->order_by('time', 'DESC')->limit(30)->execute('base');
        while ($files->valid()){
            $file = Upload_Book::get($files);
            
            $info = $file->book()->infos();
            print $info->title . '<br>';
            print HTML::image($info->link['image']) . '<br>';
            
            $files->next();
        }
         **/
         
        $this->request->response = 'index.here';
    }
    
    public function action_search()
    {
        $limit = 10;
        $get = $_GET;
        foreach (array('title','ext','order','direction','page','type') as $key){
            $$key = Arr::get($get, $key);
        }
        
        if($type == 'all'){
            $this->searchall($title, $page, $limit);
            return;
        }
        
        
        
        $handle = DB::select()->from('books')->where('title', 'LIKE', '%'.$title.'%')->limit($limit);
        $ext AND $handle->and_where('ext', '=', $ext);
        
        $order AND $handle->order_by($order, $direction);
        
        $page AND $handle->offset($limit*($page-1));
        
        $handle = $handle->execute('base');
       
        while ($handle->valid()){
            $view = View::factory('base/search/local');
            $file = Upload_Book::get($handle);
            $infos = $file->book()->infos();
            
            $view->file = $file;
            $view->book = $infos;
            
            $this->template->content .= $view->render();
            $handle->next();
        }
        $this->template->title = '搜索'.$title.'的结果';
        
    }
    
    public function searchall($query,$index=1,$max=10)
    {
        $result = $this->client->search($query, $index, $max);
        $this->template->title = $result->title;
        
        $this->template->content .= View::factory('base/search/pages')
                ->bind('total', $result->total)
                        ->bind('index', $result->index)
                        ->render();
        
        foreach ($result->entry as  $book) {
            $view = View::factory('base/search/local');
            $book->link['subject'] = URL::site('book/subject/'.$book->id);
            $book->link['largeimage']=str_replace('spic','lpic',$book->link['image']);
            isset($book->attribute) OR $book->attribute = Array();
            isset($book->author) OR $book->author = __('Unknow Author');
            $view->book = $book;
            $this->template->content .= $view->render();
        }
    }
    
    
    public function action_subject()
    {
        $book_info = $this->client->infos();
        
        $upload_url = URL::site('book/upload/'.$this->book_id,true);
        
        View::bind_global('upload_url', $upload_url);
        View::bind_global('i', $book_info);
        
        $max = 30000000;
        $upload = View::factory('base/upload')->bind('action',$upload_url)->bind('max_file_size',$max )->render();
        $this->template->content = View::factory('base/item')
                ->render() . $upload;
        $this->template->title = $book_info->title;
    }

    public function action_upload()
    {
        if(!$this->book_id){
            $this->request->redirect('/book');
        }
        
        if(isset($_FILES['book'])){
            $uid = $this->user->getUid();
            
            //@todo remove this FOR DEBUG
            $uid = 1;
            
            if($uid<0){
                $this->request->response = __('Not Login,Can not upload file');
                return;
            }
            

            $infos = $this->client->infos();
            
            $upload = Upload_Book::upload($_FILES, $this->client->infos(), $uid);
            
            // Update the tags from douban
            if(!$infos->files){
                Upload_Book::insertTags($infos);
            }
            
            if($upload == -1){
                $message = __('File Exists');
            } elseif($upload == -2) {
                $message = __('Wrong File Type');
            } elseif($upload == -3){
                $message = __('Unknow Error');
            } else {
                $message = __('Upload Success');
            }
            
            Cache::instance('book')->delete($this->book_id);
            
            $message .= HTML::anchor('book/subject/'.$this->book_id, __('return'));
            $this->template->content = $message;
            
        } else {
            $url = URL::site($this->request->uri);
            $max = 30000000;
            $this->template->content = View::factory('base/upload')->bind('action',$url)->bind('max_file_size', $max)->render();
        }
    }

    public function action_download()
    {
        /*
        $path = APPPATH . 'upload' . DIRECTORY_SEPARATOR . 'book' . DIRECTORY_SEPARATOR . '686de452126181ebc16ca62ba45f8d17aa6e7314使用Subversion进行版本控制.chm';
        */
        $fileid = $_GET['file'];
        if(!is_numeric($fileid)){
            $this->request->response = __('no fileid.');
        }
        
        $file = new Upload_Book($fileid);
        
        Base_Download::add($fileid, $this->user->getUid());
        
        if($file->douban_id == $this->book_id){
            $this->request->send_file($file->path,$this->client->infos()->title.'.'.$file->ext);
        }
    }
    
        


    /**
     * @todo remove debug
     */
    public function action_debug()
    {
        //$book = new Upload_Book(4);
        var_dump($this->client);
        //Upload_Book::insertTags($this->client->infos());
        //print_r( Base_Tags::add('ddd') );
        //$infos = $this->client->infos();
        Base_Download::add(1, 2);
    }
    
}
?>
