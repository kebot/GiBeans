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
    protected $book_id;

    public function  before() {
        $this->user = new User();
        $this->book_id = $this->request->param('id',NULL);
        $this->client = new Base_Book($this->book_id);
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
        
        $this->request->response = View::factory('base/item')
                ->bind('other',$other)
                ->render();
        
        //@todo remove debug
        $this->client->debug();

    }

    public function action_upload()
    {
        if(!$this->book_id){
            $this->request->redirect('/book');
        }
        
        if(isset($_FILES['book'])){
            $uid = $this->user->getUid();

            //@todo remove debug
            $uid = 1;

            $upload = Model_Base_Upload_Book::upload($_FILES, $this->client->infos(), $uid);
            
            $this->request->response = "1";
        } else {
            $url = URL::site($this->request->uri);
            $max = 30000000;
//            print $url;
//            return;

            $this->request->response = View::factory('base/item/upload')->bind('action',$url)->bind('max_file_size', $max)->render();
        }
    }

    public function action_down()
    {
        $path = APPPATH . 'upload' . DIRECTORY_SEPARATOR . 'book' . DIRECTORY_SEPARATOR . '686de452126181ebc16ca62ba45f8d17aa6e7314使用Subversion进行版本控制.chm';
        $this->request->response = $this->request->uri(array(
            'controller' => 'book',
            'action' => 'download' ,
            'id' => '12345',
        ));
        //$this->request->send_file($path,'test.chm');
    }
}
?>
