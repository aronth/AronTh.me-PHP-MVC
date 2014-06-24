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
 * Description of Controller
 *
 * @author Aron Þór
 */
abstract class Controller {
    
    private $controllerName;
    private $registeredPages;
    
    function __construct($controllerName) {
        $this->controllerName = $controllerName;
        $this->registerPage('index');
        //$this->initController();
    }
    
    abstract function initController();
    
    abstract function index();
    
    function isAllowedPage($pagename){
        return isset($this->registeredPages[$pagename]) && $this->registeredPages[$pagename] != false;
    }
    
    function registerPage($pagename){
        $this->registeredPages[$pagename] = true;
    }
    
    function unregisterPage($pagename){
        unset($this->registeredPages[$pagename]);
    }
    
    function getModel(){
        require_once APP_MODEL.Aronth::getURLParameter(0).'_model.php';
        $modelname = Aronth::getURLParameter(0).'_model';
        $model = new $modelname;
        return $model;
    }
    
    function getView($file){
        OutputBufferHelper::start();
        require_once APP_VIEW.Aronth::getURLParameter(0).DS.$file.'.php';
        $view = OutputBufferHelper::getBufferContentAndClean();
        Template::setViewData($view);
    }
    
    function addTemplateData($key, $val){
        Template::addValueToTemplate($key, $val);
    }
    
}
