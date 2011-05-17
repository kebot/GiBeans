<?php defined('SYSPATH') or die('No direct script access.');

class Base_Book extends Douban_API_Book {
    
    /**
     *
     * @param int $id
     * @return Base_Book
     */
    public static function factory($id=NULL){
        return new self($id);
    }

    protected $id = NULL;
    protected $book_info = NULL;
    protected $cache = NULL;

    public function __construct($id=null) {
        parent::__construct();
        $this->id = $id;

        $this->cache = Cache::instance('book');

        if ($id) {
            $this->book_info = $this->get($id);
        }
    }

    public function infos() {
        if ($this->book_info) {
            return $this->book_info;
        }
    }

    /**
     *
     * @param int $book_id
     * @return StdClass
     */
    public function get($book_id) {
        if (!$this->cache->get($book_id)) {
            $book = parent::get($book_id);
            //$book->link['largeimage'] = str_replace('spic', 'lpic', $book->link['image']);
            //$book->link['subject'] = URL::site('book/subject/' . $book_id);
            $book->files = $this->_getFiles();
            if ($book) {
                $this->cache->set($book_id, $book);
            }
        }
        return $this->cache->get($book_id);
    }

    protected function _getFiles() {
        $files = array();
        $books = DB::select()->from('books')->where('douban_id', '=', $this->id)->execute('base');
        while ($books->valid()) {
            $files[] = Upload_Book::get($books);
            $books->next();
        }
        return $files;
    }

    /**
     * @return Boolean
     */
    public function removeCache() {
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

    public static function format($book) {
        print 'call new format';
        $book = parent::format($book);
        // book subject url for Jhbean
        $book->link['subject'] = URL::site('book/subject/' . $book->id);
        
        $book->link['largeimage'] = str_replace('spic', 'lpic', $book->link['image']);
        
        isset($book->attribute) OR $book->attribute = Array();
        
        isset($book->author) OR $book->author = __('Unknow Author');
        
        return $book;
    }
    
    
    /*
     * @param number $isbn
     * @return library link
     */
    public static function find_in_library($isbn){
        
        //print $isbn;
        
        $action = 'http://210.32.205.60/bmls.php';
        $data = array(
            'T1'=>1,
            'T2'=>1,
            'T3'=>4,
            'T4'=>25,
            'T5'=>$isbn            
        );
        $response = Douban_Request::post($action, $data);
        $pattern = '/<a href=\'(ml1-1.php.+?)\'>(.+?)<\/a>/';//"<a href=\"ml1-1.php\?.*\">.*</a>";
        
        $subject = $response->to_normal();
        
//        print $subject;
        
        if( ! preg_match_all($pattern, $subject, $matchesarray) )
        {
            return array();
        }
        
        //var_dump($matchesarray);
        
        $links = $matchesarray[1];
        
        foreach($links as $key => $object){
            $link = iconv("gb2312", "UTF-8", $object);
            $links[$key] = 'http://210.32.205.60/'.$link;
        }
        return $links;
    }
}

?>
