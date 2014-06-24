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
    
    /**
     * The Config instance that holds the configuration of the website
     * @var \Config 
     */
    private static $config;
    
    /**
     * The Config instance that holds the configuration for the database access
     * @var \Config
     */
    private static $dbConfig;
     
    /**
     * The instance of the Template class that is performing all the layout preperation and buildup
     * @var \Template 
     */
    private $template;
    
    /**
     * These are all the parameters from the request
     * @var array 
     */
    private static $urlParameters = array();
    
    /**
     * This is the constructor
     */
    public function AronTh(){
        Logger::$instance = new Logger('system');
        
        // Gets the configuration for the website and reads it
        self::$config = new Config('site');
        self::$config->read();
        
        // Predefined default values if the configs do not exist
        // These are simply here for the first run to set it self to defaults
        if(!self::$config->hasValue('sitename'))self::$config->setValue('sitename', 'AronTh.me');
        if(!self::$config->hasValue('defaultController'))self::$config->setValue('defaultController', 'home');
        if(!self::$config->hasValue('defaultPage'))self::$config->setValue('defaultPage', 'index');
        if(!self::$config->hasValue('template'))self::$config->setValue('template', 'default');
        
        // Writes the configs if they have changed
        self::$config->write();
    }
    
    /**
     * Initializes the core, prepares the template
     */
    public function init(){
        
        
        // Start output buffering
        OutputBufferHelper::start();
        
        // Init User System
        User::initUser();
        User::checkForCookies(true);
        
        // Break down the request
        self::$urlParameters = $this->splitUrl();
        
        // Sets up the template that will be used
        $this->template = new Template(self::$config->getValue('template'));
    }
    
    /**
     * Gets the controller and executes all the thinking in the controller
     */
    public function run(){
        if(self::getURLParameter(0) == null)
            self::$urlParameters[0] = self::$config->getValue('defaultController');
        
        if(self::getURLParameter(1) == null)
            self::$urlParameters[1] = self::$config->getValue('defaultPage');
        
        $controllerName = self::getURLParameter(0);
        $controllerPage = self::getURLParameter(1);
        
        if(!file_exists(APP_CONTROLLER.$controllerName.'.php')){
            $this->template->renderError('Controller ('.  htmlentities($controllerName).') could not be found');
            Logger::log('');
        }
        
        $controller = new $controllerName($controllerName);
        if(!($controller instanceof Controller))
            $this->template->renderError('Controller ('.  htmlentities($controllerName).') is not an instance of Controller');
        
        $controller->initController();
        
        if(!$controller->isAllowedPage($controllerPage))
            $this->template->renderError('Page ('.  htmlentities($controllerPage).') is not accessible');
        
        call_user_func(array($controller, $controllerPage));
    }
    
    /**
     * Renders the template after executon of the controller
     */
    public function render(){
        $this->template->renderTemplate();
    }
    
    /**
     * Returns an array of all the parameteres in the browsers request to the site
     * @return array
     */
    private function splitUrl(){
        return explode('/', substr(NavigationHelper::getRequest(), 1));
    }
    
    /**
     * Gets the value from the request
     * @param int $key Position in the array
     * @return string|null The paramater or null if it does not exist
     */
    public static function getURLParameter($key){
        return isset(self::$urlParameters[$key]) ? self::$urlParameters[$key] : null;
    }
    
    /**
     * Gets the instance of the config that handels the website
     * @return \Config The config instance
     */
    public static function getSiteConfig(){
        return self::$config;
    }
    
    /**
     * Gets an active connection to the database
     * @return \PDO
     */
    public static function getDatabaseConnection(){
        self::$dbConfig = new Config('database');
        self::$dbConfig->read();
        $dsn = 'mysql:host='.self::$dbConfig->getValue('host').';port='.self::$dbConfig->getValue('port').';dbname='.self::$dbConfig->getValue('database');
        $username = self::$dbConfig->getValue('user');
        $passwd = self::$dbConfig->getValue('pass');
        $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);
        $db = new PDO($dsn, $username, $passwd, $options);
        return $db;
    }
    
}
