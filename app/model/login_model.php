<?php

/*
 * Copyright (C) Error: on line 4, column 33 in Templates/Licenses/license-gpl20.txt
  The string doesn't match the expected date/time format. The string to parse was: "23.6.2014". The expected format was: "MMM d, yyyy". Ronni
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
 * Description of login_model
 *
 * @author Ronni
 */
class login_model extends Model{
    
    private $errorReport = array();
    private $isError = false;
    
    private $username;
    private $email;
    
    public function validateSignupPostData(){
        $not = $this->checkForPostData('username', 'password', 'password2', 'email');
        if($not != true){
            foreach ($not as $value){
                switch ($value){
                    case 'username': $this->addToErrorReport('Username may not be empty.');break;
                    case 'password': $this->addToErrorReport('Password may not be empty.');break;
                    case 'password2': $this->addToErrorReport('Re-typed password may not be empty.');break;
                    case 'email': $this->addToErrorReport('E-mail address may not be empty.');break;
                    default: $this->addToErrorReport('Something went wrong, report this.');break;
                }
            }
            return false;
        }
        
        $username = $this->getPostData('username');
        $password = $this->getPostData('password');
        $retyped = $this->getPostData('password2');
        $email = $this->getPostData('email');
        
        $uLength = strlen($username);
        if($uLength < 3 || $uLength > 32)
            $this->addToErrorReport ('Username length must be between 3 and 32 charactes.');
        
        if(strlen($password) < 6)
            $this->addToErrorReport ('Password length must be at least 6 characters.');
        
        if($password != $retyped)
            $this->addToErrorReport ('Passwords do not match');
        
        if(!$this->isEmailAddressValid($email))
            $this->addToErrorReport ('E-mail address is not valid');
        
        $suc = User::createUser($username, $password, $email);
        
        if($suc == false)
            $this->addToErrorReport ('Could not register, maybe the email is already registered or the username is already in use');
        
        // Later send an email
        
        if($this->isError)
            return false;
        
        $this->username = $username;
        $this->email = $email;
        
        return true;
    }
    
    public function validateLoginPostData(){
        $not = $this->checkForPostData('username', 'password');
        if($not != true){
            $this->addToErrorReport('Username not password may not be empty.');
            return false;
        }
        
        $username = $this->getPostData('username');
        $password = $this->getPostData('password');
        
        
    }
    
    public function getUsername(){
        return $this->username;
    }
    
    public function getEmail(){
        return $this->email;
    }
    
    private function isEmailAddressValid($address){
        return filter_var($address, FILTER_VALIDATE_EMAIL);
    }
    
    private function addToErrorReport($errorMessage){
        $this->errorReport[] = $errorMessage;
        $this->isError = true;
    }
    
    public function getErrorReport(){
        return $this->errorReport;
    }
    
    public function isError(){
        return $this->isError;
    }
    
    public function clearError(){
        $this->isError = false;
        $this->errorReport = array();
    }
    
}
