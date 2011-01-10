<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Welcome extends Controller {
        public $id = NULL;
        
	public function action_before()
	{
            $this->id = $this->request->param('id');
        }
        
        public function action_book()
        {
            $this->request->send_file($filename, $download);
        }
        

} // End Welcome
