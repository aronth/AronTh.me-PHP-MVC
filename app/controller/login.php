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
 * Description of login
 *
 * @author Ronni
 */
class login extends Controller{
    
    public function initController() {
        $this->registerPage('signup');
        $this->registerPage('takesignup');
        $this->registerPage('takelogin');
        Template::addCSSToHeader('/app/view/login/login.css');
    }
    
    public function index(){
        Template::setPageTitle('Login');
        Template::addViewVar('takeloginlink', NavigationHelper::getLinkToPage('login', 'takelogin'));
        $this->getView('login');
    }
    
    public function signup(){
        Template::setPageTitle('Sign-up');
        Template::addViewVar('takesignuplink', NavigationHelper::getLinkToPage('login', 'takesignup'));
        $this->getView('signup');
    }
    
    public function takesignup(){
        $model = $this->getModel();
        $valid = $model->validateSignupPostData();
        if(!$valid){
            $this->error(implode('<br/>', $model->getErrorReport()));
            return;
        }
        Template::setPageTitle('Verification Email Sent!');
        Template::addViewVar('username', $model->getUsername());
        Template::addViewVar('email', $model->getEmail());
        $this->getView('takesignup');
    }
    
    public function takelogin(){
        $model = $this->getModel();
        $valid = $model->validateLoginPostData();
        if(!$valid){
            $this->error(implode('<br/>', $model->getErrorReport()));
            return;
        }
        Template::setPageTitle('Logged In!');
        Template::addViewVar('username', $model->getUsername());
        $this->getView('takelogin');
    }
    
    private function error($message, $title = "Error"){
        Template::setPageTitle($title);
        Template::addViewVar('errormessage', $message);
        $this->getView('error');
    }
    
}
