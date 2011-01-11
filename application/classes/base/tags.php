<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Description of tags
 *
 * @author Kebot<i@yaofur.com>
 * @link http://kebot.me/
 */
class Base_Tags
{
    public static $columns = array('id','type','value','popular');



    /**
     *
     * @param string $value
     * @param string $type
     * @return Base_Tags
     */
    public static function get($value,$type=NULL)
    {
        $handle = DB::select()->from('tags')->where('value', '=', $value);
        
        $type AND $handle->where('type', '=', $type);
        
        $handle = $handle->execute('base');
        
        if($handle->count() == 0){
            $id = self::add($value,$type);
        } else {
            $id = $handle->get('id');
        }
        return new self($id);
    }
    /**
     *
     * @param string $value
     * @param string $type 
     */
    public static function add($value,$type=NULL){
        $values = array(NULL,$type,$value,0);
        $id = DB::insert('tags')->columns(self::$columns)->values($values)->execute('base');
        return $id[0];
    }
    /**
     * @var int
     */
    public $id = NULL;
    /**
     * @var string
     */
    public $type = NULL;
    /**
     * @var string
     */
    public $value = NULL;
    /**
     * @var int
     */
    public $popular = NULL;
    
    public function __construct($id) {
        $handle = DB::select()->from('tags')->where('id', '=', $id)->execute('base');
        if($handle->count()){
            foreach (self::$columns as $value) {
                $this->$value = $handle->get($value);
            }
        }
    }
    /**
     * create a relationship between douban subject and a tag
     * @param type $douban_id
     * @return int id of relationship
     */
    public function link($douban_id)
    {
        $table = 'relationship';
        $select = DB::select()->from($table)->where('d_id', '=', $douban_id)->and_where('t_id', '=', $this->id)->execute('base');
        if( $select->count()==0 ){
            $columns = array('d_id','t_id');
            $values = array($douban_id,  $this->id);
            $id = DB::insert($table, $columns)->values($values)->execute('base');
            //Popular +1
            DB::update('tags')->set(array('popular'=>DB::expr('popular+1')))->where('id', '=', $this->id)->execute('base');
            return $id;
        } else {
            return $select->get('id');
        }
    }
}

?>
