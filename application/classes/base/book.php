<?php defined('SYSPATH') or die('No direct script access.');

class Base_Book extends Douban_API_Book {
    protected $id = NULL;
    
    protected $book_info = NULL;
    
    protected $cache = NULL;
    
    public function  __construct($id=null) {
        parent::__construct();
        $this->id = $id;

        $this->cache = Cache::instance('book');

        if($id){
            $this->book_info = $this->get($id);
        }
    }

    public function infos(){
        if ($this->book_info){
            return $this->book_info;
        }
    }

    /**
     *
     * @param int $book_id
     * @return StdClass
     */
    public  function  get($book_id) {
        if(!$this->cache->get($book_id)){
            $book = parent::get($book_id);
            $book->link['largeimage']=str_replace('spic','lpic',$book->link['image']);
            $book->link['subject']=URL::site('book/subject/'.$book_id);
            $book->files = $this->_getFiles();
            if($book){
                $this->cache->set($book_id, $book);
            }
        }
        return $this->cache->get($book_id);
    }
    
    protected function _getFiles() {
        $files = array();
        $books = DB::select()->from('books')->where('douban_id','=',  $this->id)->execute('base');
        while($books->valid()){
            $files[] = Upload_Book::get($books);
            $books->next();
        }
        return $files;
    }

    /**
     * @return Boolean
     */
    public function removeCache()
    {
        return $this->cache->delete_all();
    }

    /**
     * @todo remove debug functions
     */
    public function debug() {
        //$this->removeCache();
        $this->book_info = $this->get($this->id);
        print_r($this->book_info);
    }
}




?>
