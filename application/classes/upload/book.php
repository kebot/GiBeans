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
     *
     * @param <type> $files
     */
    public static function upload($files, $book_info, $uid) {
        // set the default file directory
        $file_key = 'book';

        if (isset($files[$file_key])) {
            $file = $files[$file_key];
            $isValid = Upload::valid($file) && Upload::type($file, self::$allowed);
            if ($isValid) {
                $filename = self::_makeFilename($file);
                $path = Upload::save($file, $filename);
                // save in database
                $columns = array('title', 'path', 'owner', 'time', 'size', 'douban_id');
                $values = array($book_info->title, $filename, $uid, DB::expr('NOW()'), $file['size'], $book_info->id);
                $id = DB::insert('books', $columns)->values($values)->execute('base');
                if ($id) {
                    return $id;
                }
            }
        }
    }

    protected static function _makeFilename($file) {
        return sha1_file($file['tmp_name']) . $file['name'];
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
     * @var int the user id for Ucenter
     */
    public $uid = NULL;
    /**
     * @var String the size of the file
     */
    public $size = NULL;

    public function __construct($id=NULL) {
        if ($id) {
            $this->id = $id;
            $handle = DB::select()->from('books')->where('id', '=', $this->id)->execute('base');
            if ($handle->count() == 1) {
                $this->_withInfos($handle);
            }
        }
    }

    public function withInfos($handle) {

        $this->douban_id = $handle->get('douban_id');
        $this->path = self::fullpath($handle->get('path'));
        $this->uid = $handle->get('owner');
        $this->size = $handle->get('size');
    }

    public static function get($handle) {
        $bk = new self();
        $bk->id = $handle->get('id');
        $bk->withInfos($handle);
        return $bk;
    }
}

?>
