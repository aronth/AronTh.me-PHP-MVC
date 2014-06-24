<?php

/*
 * Copyright (C) 2014 AronTh.me
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

/**
 * Description of User
 *
 * @author Aron Þór
 */
class User {
    /**
     * The instance of the user
     * @var User
     */
    private static $instance = null;
    
    /**
     * The active connection to the database
     * @var PDO 
     */
    private $database;
    
    /**
     * The data about the used that is logged in
     * @var array
     */
    private $userData = array();
    
    /**
     * Simply know if a user is logged in or not
     * Default: false
     * @var boolean
     */
    private $isLoggedIn = false;
    
    /**
     * Initiates the user and stores it in a private static instance variable for every method to access it
     */
    public static function initUser(){
        if(self::$instance == null)
            self::$instance = new User();
    }
    
    /**
     * Constructor gets a database connection
     */
    public function __construct() {
        $this->database = Aronth::getDatabaseConnection();
    }
    
    /**
     * Gets the user class instance
     * @return User
     */
    public static function getUser(){
        return self::$instance;
    }

    /**
     * Checks if the client has the login cookies, $shouldLogin is set to true it will try to login a user with that information
     * Returns true if has cookies and false if not
     * @param boolean $shouldLogin Should it try to login the user if he has the required information
     * @return boolean
     */
    public static function checkForCookies($shouldLogin = false){
        if(self::$instance == null)
            self::initUser ();
        if(isset($_COOKIE['uid']) && isset($_COOKIE['pwd'])){
            $uid = $_COOKIE['uid'];
            $pwd = $_COOKIE['pwd'];
            if($shouldLogin)
                self::loginUser($uid, $pwd);
            return true;
        }
        return false;
    }
    
    /**
     * Loggs the user in, sets the User data and sets $isLoggedIn to true if everything works
     * @param intager $uid The User ID
     * @param string $password The hashed password
     */
    public static function loginUser($uid, $password){
        if(self::$instance == null)
            self::initUser ();
        
    }
     /**
      * Creates a row in the database for the user
      * @param string $username The requested username
      * @param string $password The requested password
      * @param string $email The requested email address
      * @return boolean if successfull or not
      */
    public static function createUser($username, $password, $email){
        if(self::$instance == null)
            self::initUser ();
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $validKey = md5(time().$username);
        $regTime = time();
        $q = $this->database->prepare('INSERT INTO users (username, passhash, email, validationKey, regtime) VALUES (:u, :p, :e, :v, :r)');
        $q->bindParam(':u', $username);
        $q->bindParam(':p', $hash);
        $q->bindParam(':e', $email);
        $q->bindParam(':v', $validKey);
        $q->bindParam(':r', $regTime);
        $q->execute();
        return true;
    }
    
    /**
     * Checks if the user is logged in
     * @return boolean
     */
    public static function isLoggedIn(){
        if(self::$instance == null)
            self::initUser();
        return self::$instance->isLoggedIn;
    }
    
    /**
     * Unsets the cookies, makes the cookie expire the last second and sets $isLoggedIn to false
     */
    public static function logout(){
        setcookie('uid', 0, -1);
        setcookie('pwd', 0, -1);
        unset($_COOKIE['uid']);
        unset($_COOKIE['pwd']);
        self::$instance->isLoggedIn = false;
    }
    
}
