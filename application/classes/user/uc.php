<?php defined('SYSPATH') or die('No direct script access.');

/**
 * This is a basic user class for UC_Client
 */

class User_UC {
    
    protected $uid;
    protected $username;
    protected $session;
    protected $config;
    
    public function __construct() {
        $this->config = Kohana::config('user');
        include  Kohana::find_file('uc_client', 'client');
        $this->session = Session::instance('cookie');
        Cookie::$salt = $this->config->salt;
        if(!$this->uid){
            $this->getUser();
        }
    }
    
    /**
     *
     * @param String $username
     * @param String $password
     * @param int or false $lifetime
     */
    public function login($username, $password, $lifetime = FALSE) {
        if (!$lifetime) {
            $lifetime = $this->config->lifetime;
        }
        list($uid, $username, $password, $email) = uc_user_login($username, $password);
        if ($uid > 0) {
            $this->username = $username;
            $this->uid = $uid;
            $this->setSession()->saveCookie();
            uc_user_synlogin($uid);
        }
        return $uid;
    }
    
    public function logout() {
        $this->session->destroy();
        Cookie::delete($this->config->cookie_key);
        $this->uid = null;
        $this->username = null;
        uc_user_synlogout();
    }

    /**
     * @return int >0 for uid , and -1 for not login
     */
    public function getUid(){
        if($this->uid>0){
            return $this->uid;
        } else {
            return -1;
        }
    }
    
    public function getUsername()
    {
        return $this->username;
    }

    /**
     *
     * @return Bool
     */
    public function isLogin(){
        if($this->uid){
            return TRUE;
        } else {
            return FALSE;
        }
    }



    protected function saveCookie() {
        $value = json_encode(array(
                    'uid' => $this->uid,
                    'username' => $this->username,
                    'auth' => Cookie::salt($this->uid, $this->username),
                ));
        Cookie::set($this->config->cookie_key, $value, $this->config->lifetime);
        return $this;
    }

    protected function getCookie() {
        $o = json_decode(
                        Cookie::get($this->config->cookie_key, NULL)
        );

        if (isset ($o->auth) && $o->auth == Cookie::salt($o->uid, $o->username)) {
            $this->uid = $o->uid;
            $this->username = $o->username;
            $this->setSession();
        } else {
            Cookie::delete($this->config->cookie_key);
        }
    }

    protected function setSession() {
        $this->session->set($this->config->session_uid, $this->uid);
        $this->session->set($this->config->session_username,  $this->username);
        return $this;
    }

    protected function getUser() {
        $this->uid = $this->session->get($this->config->session_uid);
        if(!$this->uid){
            $this->getCookie();
        }
        return $this;
    }

    public function debug() {
        //$this->uid = 12;
        //$this->username = 'keith';
        //$this->saveCookie();
        //print Cookie::get($this->config->cookie_key);
        //$this->session->destroy();
        //$this->getUser();
        print_r($_SESSION);
        print_r($_COOKIE);

        print $this->uid ;
        print $this->username;
    }
}

