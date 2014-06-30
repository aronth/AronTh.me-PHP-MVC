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
     * The active connection to the database
     * @var PDO 
     */
    private static $database;
    
    /**
     * The data about the used that is logged in
     * @var array
     */
    private static $userData = array();
    
    /**
     * Simply know if a user is logged in or not
     * Default: false
     * @var boolean
     */
    private static $isLoggedIn = false;
    
    /**
     * Initiates the user and stores it in a private static instance variable for every method to access it
     */
    public static function initUser(){
        self::$database = Aronth::getDatabaseConnection();
        self::checkForCookies();
        Logger::log('User initiated');
    }

    /**
     * Checks if the client has the login cookies, $shouldLogin is set to true it will try to login a user with that information
     * Returns true if has cookies and false if not
     * @param boolean $shouldLogin Should it try to login the user if he has the required information
     * @return boolean
     */
    public static function checkForCookies(){
        if(isset($_COOKIE['uid']) && isset($_COOKIE['pwd'])){
            Logger::log('Login cookies found');
            $uid = $_COOKIE['uid'];
            $pwd = $_COOKIE['pwd'];
            if(self::verifyUserLoginCookie($uid, $pwd)){
                Logger::log('Login cookies verified');
                self::$isLoggedIn = true;
                self::getUserDataForId($uid);
                return true;
            }
        }
        Logger::log('Login cookies not found');
        return false;
    }
    
    public static function getUserData($key){
        return self::$userData[$key];
    }

    public static function getUserDataForId($uid){
        $q = self::$database->prepare('SELECT * FROM users WHERE id=:uid');
        $q->bindValue(':uid', $uid);
        if($q->execute()){
            $data = $q->fetch(PDO::FETCH_ASSOC);
            self::$userData = $data;
            Logger::log('User data set for user '.$data['username']);
        }
    }
    
    /**
     * Loggs the user in, sets the User data and sets $isLoggedIn to true if everything works
     * @param intager $uid The User ID
     * @param string $password The hashed password
     */
    public static function setLoginCookies($uid, $password){
        setcookie('uid', $uid, time()+3600, '/');
        setcookie('pwd', $password, time()+3600, '/');
        Logger::log('Login cookies set');
    }
    
    public static function verifyUserLogin($uid, $password, $shouldLogin = false){
        $q = self::$database->prepare('SELECT passhash FROM users WHERE id=:uid');
        $q->bindValue(':uid', $uid);
        if($q->execute()){
            Logger::log('Login:Passhash found in database for id');
            $data = $q->fetch(PDO::FETCH_ASSOC);
            if(password_verify($password, $data['passhash'])){
                Logger::log('Login:Passhash hash been verified');
                if($shouldLogin)
                    self::setLoginCookies($uid, $data['passhash']);
                return true;
            }
        }else{
            Logger::log('Someone tryed to log in with an id that was not matched, that should not happen');
        }
        return false;
    }
    
    public static function verifyUserLoginCookie($uid, $password, $shouldLogin = false){
        $q = self::$database->prepare('SELECT passhash FROM users WHERE id=:uid');
        $q->bindValue(':uid', $uid);
        if($q->execute()){
            Logger::log('Login:Passhash found in database for id');
            $data = $q->fetch(PDO::FETCH_ASSOC);
            if($password == $data['passhash']){
                Logger::log('Login:Passhash hash been verified');
                if($shouldLogin)
                    self::setLoginCookies($uid, $data['passhash']);
                return true;
            }
        }else{
            Logger::log('Someone tryed to log in with an id that was not matched, that should not happen');
        }
        return false;
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
        $q = self::$database->prepare('INSERT INTO users (username, passhash, email, validationKey, regtime) VALUES (:u, :p, :e, :v, :r)');
        $q->bindParam(':u', $username);
        $q->bindParam(':p', $hash);
        $q->bindParam(':e', $email);
        $q->bindParam(':v', $validKey);
        $q->bindParam(':r', $regTime);
        $q->execute();
        return true;
    }
    
    /**
     * Gets the id from the user with the provided username
     * @param string $username The given username
     * @return int the user id
     */
    public static function getUserIDByUsername($username){
        $q = self::$database->prepare('SELECT id FROM users WHERE username=:name');
        $q->bindValue(':name', $username);
        if($q->execute()){
            $data = $q->fetch(PDO::FETCH_ASSOC);
            if($data)
                return $data['id'];
        }
    }
    
    /**
     * Checks if the user is logged in
     * @return boolean
     */
    public static function isLoggedIn(){
        return self::$isLoggedIn;
    }
    
    /**
     * Unsets the cookies, makes the cookie expire the last second and sets $isLoggedIn to false
     */
    public static function logoutUser(){
        setcookie('uid', 0, -1, '/');
        setcookie('pwd', 0, -1, '/');
        unset($_COOKIE['uid']);
        unset($_COOKIE['pwd']);
        self::$isLoggedIn = false;
        self::$userData = null;
    }
    
}
