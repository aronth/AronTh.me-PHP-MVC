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
 * Handles the template
 *
 * @author Aron Þór
 */
class Template {
    
    // The template name
    private $templateName;
    
    private $css = array();
    private $js = array();
    
    private static $sectionVars = array();
    private static $pageTitle;
    
    // Sets the template
    public function Template($templateName){
        $this->templateName = $templateName;
    }
    
    public function getTemplateName(){
        return $this->templateName;
    }
    
    public function renderTemplate(){
        $template = $this->parseLayout();
        echo $template;
    }
    
    private function parseLayout(){
        $sections = array();
        $layout = $this->getTemplateLayout();
        $pattern = '#{(.*)}#';
        preg_match_all($pattern, $layout, $sections);
        $tags = $sections[1];
        foreach($tags as $tag){
            if($tag == 'view'){
                
                // Gets the view of the app running
                
                
                continue;
            }
            $section = $this->getTemplateSection($tag);
            if($section != false)
                $layout = str_replace('{'.$tag.'}', $section, $layout);
            else
                $layout = str_replace('{'.$tag.'}', 'Section ('.$tag.') was not found in the template', $layout);
        }
        return $layout;
    }
    
    private function getTemplateSection($section){
        if(!file_exists(APP_TEMPLATE.$this->templateName.DS.$section.'.tpl'))
                return false;
        $vars = array();
        OutputBufferHelper::start();
        $this->getFileFromTemplate($section);
        $return = OutputBufferHelper::getBufferContentAndClean();
        $pattern = '#{(.*)}#';
        preg_match_all($pattern, $return, $vars);
        $variables = $vars[1];
        foreach($variables as $var){
            if(substr($var, 0, 4) == 'tpl_'){
                if($var == 'tpl_sitename')
                    $return = str_replace ('{tpl_sitename}', AronTh::getSiteConfig()->getValue('sitename'), $return);
                if($var == 'tpl_title')
                    $return = str_replace ('{tpl_title}', (isset(self::$pageTitle) && strlen (self::$pageTitle) > 0 ? self::$pageTitle : 'undefined'), $return);
            }
            if(!isset(self::$sectionVars[$var]))
                continue;
            $return = str_replace('{'.$var.'}', self::$sectionVars[$var], $return);
        }
        return $return;
    }
    
    private function getTemplateLayout(){
        OutputBufferHelper::start();
        $this->getFileFromTemplate('layout');
        return OutputBufferHelper::getBufferContentAndClean();
    }
    
    private function getFileFromTemplate($file){
        require APP_TEMPLATE.$this->templateName.DS.$file.'.tpl';
    }
    
    private function getCSSIncludes(){
        $return = "";
        for($i = 0; $i < count($this->css); $i++){
            $return .= "<link rel=\"stylesheet\" href=\"".$this->css[$i]."\" />";
        }
        return $return;
    }
    
    public function addCSSToHeader($cssFile) {
        $this->css[] = $cssFile;
    }
    
    public function addJavaScriptToHeader($javascriptFile){
        $this->js[] = $javascriptFile;
    }
    
    public static function addValueToTemplate($key, $value){
        self::$sectionVars[$var] = $value;
    }
    
    public static function setPageTitle($title){
        self::$pageTitle = $title;
    }
    
}