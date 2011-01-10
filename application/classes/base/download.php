<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Description of download
 *
 * @author Kebot<i@yaofur.com>
 * @link http://kebot.me/
 */
class Base_Download {
    /**
     * @param int $file_id
     * @param int $user 
     */
    public static function add($file_id , $uid) {
        DB::update('books')->set(array('download_count'=>DB::expr('download_count+1') ))->where('id', '=', $file_id)->execute('base');
        $result = DB::select()->from('downloads')->where('file_id', '=', $file_id)->and_where('uid', '=', $uid)->execute('base');
        if($result->count()==0){
            $columns = array('file_id','uid','date');
            $values = array($file_id,$uid,DB::expr('NOW()'));
            DB::insert('downloads',$columns)->values($values)->execute('base');
        }
    }
    
    public static function count($people,$max=100,$page=1)
    {
        
    }
    
    
}

?>
