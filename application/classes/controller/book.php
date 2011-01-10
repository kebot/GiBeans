<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Book extends Controller {

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
        $this->user = new User();
        // @todo debug
        $this->book_id = $this->request->param('id',NULL);
        if($this->book_id){
            $this->client = new Base_Book($this->book_id);
        }
    }
    /**
     *
     *
     * @todo don't use $_GET array and add pages
     */
    public function action_search()
    {
        $q = Validate::not_empty($_GET['q']);
        $result = $this->client->search($q);
        var_dump($result);
    }

    public function action_index()
    {
        
        
        $this->request->response = 'index.here';
    }

    public function action_subject()
    {
        $book_info = $this->client->infos();
        
        

        $upload_url = URL::site('book/upload/'.$this->book_id,true);
        
        View::bind_global('upload_url', $upload_url);
        View::bind_global('i', $book_info);
        
        $other = View::factory('base/book/relates')->render();
        $max = 30000000;
        $other.= View::factory('base/upload')->bind('action',$upload_url)->bind('max_file_size',$max )->render();
        $this->request->response = View::factory('base/item')
                ->bind('other',$other)
                ->render();
        
        //@todo remove debug
        $this->client->removeCache();

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
            
            $this->request->response = $message;
        } else {
            $url = URL::site($this->request->uri);
            $max = 30000000;
//            print $url;
//            return;

            $this->request->response = View::factory('base/upload')->bind('action',$url)->bind('max_file_size', $max)->render();
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
        //var_dump($this->client);
        //Upload_Book::insertTags($this->client->infos());
        //print_r( Base_Tags::add('ddd') );
        //$infos = $this->client->infos();
        Base_Download::add(1, 2);
    }
    
}
?>
