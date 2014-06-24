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
    
    private static $css = array();
    private static $js = array();
    
    private static $sectionVars = array();
    private static $pageTitle;
    private static $viewData;
    private static $viewVars;
    
    // Sets the template
    public function Template($templateName){
        require_once APP_TEMPLATE.$templateName.DS.'template.php';
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
                $view = self::$viewData;
                $pattern = '#{(.*)}#';
                preg_match_all($pattern, $view, $tags);
                $tags = $tags[1];
                foreach($tags as $tag){
                    if(isset(self::$viewVars[$tag]))
                        $view = str_replace ('{'.$tag.'}', self::$viewVars[$tag], $view);
                }
                $layout = str_replace('{view}', $view, $layout);
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
                    $return = str_replace ('{tpl_title}', formatPageTitle(self::$pageTitle), $return);
                if($var == 'tpl_navlinks')
                    $return = str_replace ('{tpl_navlinks}', formatNavigationLinks(), $return);
                if($var == 'tpl_additionalStyleSheets')
                    $return = str_replace ('{tpl_additionalStyleSheets}', $this->getCSSIncludes(), $return);
                //if($var == 'tpl_additionalJavaScript')
                    //$return = str_replace ('{tpl_additionalJavaScript}', $this->get(), $return);
            }
            if(function_exists('addToTemplateData')){
                self::$sectionVars = array_merge(self::$sectionVars, addToTemplateData());
                //echo 'added';
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
        foreach(self::$css as $sheet){
            $return .= "<link rel=\"stylesheet\" href=\"".$sheet."\" />";
        }
        return $return;
    }
    
    public static function addCSSToHeader($cssFile) {
        self::$css[] = $cssFile;
    }
    
    public static function addJavaScriptToHeader($javascriptFile){
        self::$js[] = $javascriptFile;
    }
    
    public static function addValueToTemplate($key, $value){
        self::$sectionVars[$key] = $value;
    }
    
    public static function setPageTitle($title){
        self::$pageTitle = $title;
    }
    
    public function renderError($msg, $die = true){
        $sections = array();
        $layout = $this->getTemplateLayout();
        $pattern = '#{(.*)}#';
        preg_match_all($pattern, $layout, $sections);
        $tags = $sections[1];
        foreach($tags as $tag){
            if($tag == 'view'){
                $layout = str_replace('{view}', $msg, $layout);
                continue;
            }
            $section = $this->getTemplateSection($tag);
            if($section != false)
                $layout = str_replace('{'.$tag.'}', $section, $layout);
            else
                $layout = str_replace('{'.$tag.'}', 'Section ('.$tag.') was not found in the template', $layout);
        }
        echo $layout;
        if($die)die;
    }
    
    public static function setViewData($view){
        self::$viewData = $view;
    }
    
    public static function addViewVar($var, $val){
        self::$viewVars[$var] = $val;
    }
    
}