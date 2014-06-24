<?php

/*
 * Copyright (C) 2014 Aronth.me
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
 * Config
 * Reads and writes configuration files for the application
 *
 * @author AronTh.me
 */
class Config {
    
    // The config file we are working with
    private $config;
    
    // Has the config been changed, does it need resaving ?
    private $isChanged;
    
    // The array holding every value
    protected $configArray = array();
    
    // Set the file for working with in the constructor
    public function Config($configName){
        $this->config = $configName;
    }
    
    // Reads the provided file
    public function read(){
        if(file_exists(APP_CONFIG . $this->config . '.php')){
            require APP_CONFIG . $this->config . '.php';
            $this->isChanged = false;
        }else{
            throw new Exception('Config file '.$this->config.' was not found');
        }
    }
    
    // Returns the config array as php code
    protected function getConfigAsPHP(){
        $php = "<?php\n";
        foreach ($this->configArray as $key => $value){
            $php .= "$"."this->configArray['".$key."'] = '".$value."';\n";
        }
        $php .= "?>";
        return $php;
    }
    
    // Writes the config to the provided file
    public function write(){
        file_put_contents(APP_CONFIG . $this->config.'.php', $this->getConfigAsPHP());
        $this->isChanged = false;
        //echo 'Wrote config filge';
    }
    
    // Gets a value from the config array
    public function getValue($key){
        return isset($this->configArray[$key]) ? $this->configArray[$key] : null;
    }
    
    // Checks if the array has a value assigned to the given key
    public function hasValue($key){
        return isset($this->configArray[$key]) ? true : false;
    }
    
    // Overwrites or creates a new entry in the config array
    public function setValue($key, $value){
        if(!isset($this->configArray[$key]) || $this->configArray[$key] != $value)
            $this->isChanged = true;
        $this->configArray[$key] = $value;
    }
    
    public function setDefaults($array){
        foreach($array as $key => $val){
            if(!isset($this->configArray[$key])){
                $this->configArray[$key] = $val;
                $this->isChanged = true;
            }
        }
    }
    
    public function setDefaultValue($key, $val){
        if(!isset($this->configArray[$key])){
            $this->configArray[$key] = $val;
            $this->isChanged = true;
        }
    }
    
    // Writes the configs to a file if thay have been changed
    public function __destruct() {
        if($this->isChanged)
            $this->write();
    }
    
}
