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
     * The Version of the framework released by aronth
     */
    const VERSION = 0.1;
    
    /**
     * The build number for beta releases
     */
    const BUILD = 1;
    
    /**
     * Is the framework in debug mode ?
     */
    const DEBUG = true;
    
    /**
     * Should the log be saved or only reviewed
     */
    const SAVE_LOG = false;
    
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
        if(Aronth::DEBUG){
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            Logger::log('Debug mode set !!');
        }
        Logger::log('Framework initiation started..');
        // Gets the configuration for the website and reads it
        self::$config = new Config('site');
        self::$config->read();
        
        // Predefined default values if the configs do not exist
        // These are simply here for the first run to set it self to defaults
        $defaultConfigs = array(
            'sitename' => 'AronTh.me',
            'defaultController' => 'home',
            'defaultPage' => 'index',
            'template' => 'default',
            'timezone' => 'GMT'
        );
        self::$config->setDefaults($defaultConfigs);
        
        date_default_timezone_set(self::$config->getValue('timezone'));
        
        // Init User System
        User::initUser();
        
        $this -> init();
        $this -> run();
        $this -> render();
    }
    
    /**
     * Initializes the core, prepares the template
     */
    public function init(){
        // Start output buffering
        OutputBufferHelper::start();
        
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
            Logger::log('Controller ('.  htmlentities($controllerName).') could not be found');
        }
        
        $controller = new $controllerName;
        if(!($controller instanceof Controller))
            $this->template->renderError('Controller ('.  htmlentities($controllerName).') is not an instance of Controller');
        
        $controller->_initController();
        
        if(!$controller->_isAllowedPage($controllerPage))
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
    
    public function __destruct() {
        if(self::SAVE_LOG)
            Logger::saveLog();
    }
    
}
