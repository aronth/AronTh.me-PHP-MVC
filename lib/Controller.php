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
    /**
     * An array of values allowed for calling from the request
     * @var array
     */
    private $_registeredPages;
    
    /**
     * Gives the controller a change to prepare it self for checks befor the pages are called
     * Register your pages here
     * Add links to the template for css and javascript file
     */
    abstract function _initController();
    
    /**
     * Checks if the given page name is allowed to call
     * @param string $pagename
     * @return boolean
     */
    function _isAllowedPage($pagename){
        return isset($this->_registeredPages[$pagename]) && $this->_registeredPages[$pagename] != false;
    }
    
    /**
     * Register a page to be allowed to be called in a method
     * @param string $pagename
     */
    function _registerPage($pagename){
        $this->_registeredPages[$pagename] = true;
    }
    
    /**
     * If you for any reason would want to unregister a page you do it here
     * @param string $pagename
     */
    function _unregisterPage($pagename){
        unset($this->_registeredPages[$pagename]);
    }
    
    /**
     * Returns an instance of the model for the 
     * @return \Model
     */
    function _getModel(){
        require_once APP_MODEL.Aronth::getURLParameter(0).'_model.php';
        $modelname = Aronth::getURLParameter(0).'_model';
        $model = new $modelname;
        return $model;
    }
    
    /**
     * Gets the view file from the view directory
     * @param string $file the name of the file, '.php' is added automatically
     */
    function _getView($file){
        OutputBufferHelper::start();
        require_once APP_VIEW.Aronth::getURLParameter(0).DS.$file.'.php';
        $view = OutputBufferHelper::getBufferContentAndClean();
        Template::setViewData($view);
    }
    
    /**
     * Add data to the view, finds the {$tag} in the view file and replaces it with the givel value 
     * @param type $key the name/key, what you call the tag, do not input {article_heading_1}, instead type 'article_heading_1'
     * @param type $val the value you want to be set
     */
    function _setViewTag($tag, $val){
        Template::addViewVar($tag, $val);
    }
    
    /**
     * Redirects the usera after a given time, instant is default
     * @param url $to
     * @param intager $time
     */
    protected function _redirect($to, $time = 0){
        if($time == 0)
            header('Location: '.$to);
        elseif($time > 0)
            header('refresh:'.$time.';url='.$to);
    }
    
    protected function _setPageTitle($pageTitle){
        Template::setPageTitle($pageTitle);
    }
    
}
