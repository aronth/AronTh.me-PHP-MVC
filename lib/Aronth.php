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
 * The base class wrapping most things together
 *
 * @author Aron Þór
 */
class Aronth {
    
    // The Config class instance for "site"
    private static $config;
    
    // The array of url parameters
    private static $urlParameters = array();
    
    // The constructor that initializes the core
    public function AronTh(){
        self::$config = new Config('site');
        self::$config->read();
    }
    
    // Makes the core ready for the application
    public function init(){
        self::$urlParameters = $this->splitUrl();
    }
    
    // Runs the Application and makes it ready for rendering
    public function run(){
        
    }
    
    // Renders the output of the application and the template
    public function render(){
        
    }
    
    // Splits the url and returns it as an array
    private function splitUrl(){
        return explode('/', substr(self::getRequest(), 1));
    }
    
    // 
    public static function getURLParameter($key){
        return isset(self::$urlParameters[$key]) ? self::$urlParameters[$key] : null;
    }
    
    // Returns the requested url parameters from the browsers address
    public static function getRequest(){
        return $_SERVER['REQUEST_URI'];
    }
    
    // Returns the Site config instance
    public static function getSiteConfig(){
        return self::$config;
    }
    
}
