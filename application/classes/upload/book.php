<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Description of book
 *
 *
 * @author Kebot<i@yaofur.com>
 * @link http://kebot.me/
 */
Upload::$default_directory = APPPATH . 'upload' . DIRECTORY_SEPARATOR . 'book';

class Upload_Book {

    /**
     * allowed file types.
     * @var array
     */
    protected static $allowed = array(
        'txt', 'pdf', 'chm' 
    );

    /**
     * do upload
     * @param array $files
     * @param StdClass $book_info
     * @param int $uid
     * @return success the upload file; int -1 - file alread exists ; -2 - Wrong file type -3 Unknow Error
     */
    public static function upload($files, $book_info, $uid) {
        // set the key for upload file array
        $file_key = 'book';
        
        if (isset($files[$file_key])) {
            $file = $files[$file_key];
            // valid the file type
            $isValid = Upload::valid($file) && Upload::type($file, self::$allowed);
            if ($isValid) {
                $ext = self::_getExt($file['name']);
                $filename = self::_makeFilename($file);
                if(self::isFileExists($filename)){
                    return -1;
                }
                $path = Upload::save($file, $filename);
                // save in database
                $columns = array('title', 'sha1', 'owner', 'time', 'size', 'douban_id', 'ext');
                $values = array($book_info->title, $filename, $uid, DB::expr('NOW()'), $file['size'], $book_info->id, $ext);
                $id = DB::insert('books', $columns)->values($values)->execute('base');
                
                if ($id) {
                    return $id;
                } else {
                    return -3;
                }
            } else {
                return -2;
            }
        }
    }
    /**
     * @todo unfinished 
     * @uses Base_Tags
     */
    public static function insertTags($infos)
    {
        foreach($infos->tags as $tag=>$value){
            $tag = Base_Tags::get($tag)->link($infos->id);
        }
    }

    /**
     * 
     * @param string $sha1
     * @return boolean
     */
    public static function isFileExists($sha1)
    {
        $count = DB::select()->from('books')->where('sha1', '=', $sha1)->execute('base')->count();
        if($count >= 1)
        {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /**
     *
     * @param Database_Result $handle
     * @return Upload_Book
     */
    public static function get($handle) {
        $bk = new self();
        $bk->id = $handle->get('id');
        $bk->withInfos($handle);
        return $bk;
    }

    protected static function _makeFilename($file) {
        return sha1_file($file['tmp_name']);
    }

    protected static function _getExt($filename) {
        $retval = '';

        $pt = strrpos($filename, '.');
        if ($pt) {
            $retval = substr($filename, $pt + 1, strlen($filename) - $pt);
        }

        return $retval;
    }

    protected static function fullpath($filename) {
        return Upload::$default_directory . DIRECTORY_SEPARATOR . $filename;
    }

    /**
     * @var int  the id for a single file in DB
     */
    public $id = NULL;
    /**
     * @var String
     */
    public $path = NULL;
    /**
     * @var String
     */
    public $ext = NULL;
    /**
     * @var int the user id for Ucenter
     */
    public $uid = NULL;
    /**
     * @var String the size of the file
     */
    public $size = NULL;
    
    /**
     * @var int 
     */
    public $douban_id = NULL;
    /**
     * @param String 
     */
    public $download_url = NULL;

    public function __construct($id=NULL) {
        if ($id) {
            $this->id = $id;
            $handle = DB::select()->from('books')->where('id', '=', $this->id)->execute('base');
            if ($handle->count() == 1) {
                $this->withInfos($handle);
            }
        }
    }
    
    public function withInfos($handle) {
        $this->douban_id = $handle->get('douban_id');
        $this->path = self::fullpath($handle->get('sha1'));
        $this->uid = $handle->get('owner');
        $this->size = $handle->get('size');
        $this->ext = $handle->get('ext');
        $this->download_count = $handle->get('download_count');
        $this->download_url = URL::site('book/download/'.$this->douban_id).'?file='.$this->id;
    }
    /**
     *
     * @param int $id
     * @return Base_Book 
     */
    public function book(){
        if($this->douban_id){
            return new Base_Book($this->douban_id);
        }
    }
}

?>
