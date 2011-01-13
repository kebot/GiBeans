<?php defined('SYSPATH') or die('No direct script access.');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of welcome
 *
 * @author Kebot<i@yaofur.com>
 * @link http://kebot.me/
 */
class Controller_Welcome extends Controller{
    
    public function action_index()
    {
        $this->request->redirect('book');
    }
    
    
}

?>
