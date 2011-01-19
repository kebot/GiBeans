<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * This is a basic class for Kohana View
 *
 * @author Kebot<i@yaofur.com>
 * @link http://kebot.me/
 */
class View_Base {
    
    public function __construct($view)
    {
        View::factory($view);
    }
    
    
    
    
}

?>
