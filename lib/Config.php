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
    
    /**
     * The name of the config file we are working with
     * @var string
     */
    private $config;
    
    /**
     * Have the values changed ? Does the file have to be saved ?
     * @var boolean have values changed
     */
    private $isChanged;
    
    /**
     * The valus gotten from the file or set by defaults if the file was not found
     * @var array The config values
     */
    protected $configArray = array();
    
    /**
     * Sets the config file this instance will work with
     * @param string $configName
     */
    public function Config($configName){
        $this->config = $configName;
    }
    
    /**
     * Reads the file in the /lib/config directory with the name given in the constructor
     */
    public function read(){
        if(file_exists(LIB_CONFIG . $this->config . '.php')){
            require LIB_CONFIG . $this->config . '.php';
            $this->isChanged = false;
            Logger::log('Config file ('.$this->config.') read.');
        }else{
            Logger::log('Config file '.$this->config.' was not found');
        }
    }
    
    /**
     * Returns the configuration file in php syntax
     * @return string the php syntax
     */
    protected function getConfigAsPHP(){
        $php = "<?php\n";
        foreach ($this->configArray as $key => $value){
            $php .= "$"."this->configArray['".$key."'] = '".$value."';\n";
        }
        return $php;
    }
    
    /**
     * Writes the config php file to the /lib/config directory with the name given in the constructor
     * Sets $isChanged to false
     */
    public function write(){
        file_put_contents(LIB_CONFIG . $this->config.'.php', $this->getConfigAsPHP());
        $this->isChanged = false;
        Logger::log('Wrote config file: '.$this->config);
    }
    
    /**
     * Returns the requested valur for the provided key
     * @param string $key Key in the config array
     * @return string/null Returns string value if one is found, else returns null
     */
    public function getValue($key){
        return isset($this->configArray[$key]) ? $this->configArray[$key] : null;
    }
    
    /**
     * Checks for a value for $key
     * @param string $key The name of the value to look for
     * @return boolean True if found, false otherwise
     */
    public function hasValue($key){
        return isset($this->configArray[$key]) ? true : false;
    }
    
    /**
     * Sets a value in the config array. Also sets $isChanged to true
     * @param string $key
     * @param string $value
     */
    public function setValue($key, $value){
        if(!isset($this->configArray[$key]) || $this->configArray[$key] != $value)
            $this->isChanged = true;
        $oldVal = "";
        if(isset($this->configArray[$key]) && $this->configArray[$key] != $value)
            $oldVal = $this->configArray[$key];
        $this->configArray[$key] = $value;
        if($oldVal != "")
            Logger::log('Overwrote config value '.$key.' to '.$value.' was '.$oldVal);
        else
            Logger::log('Set config value '.$key.' to '.$value);
    }
    
    /**
     * Checks for every key in the $array for if the value exists, if not it gets set to that value given in the $array. Also sets $isChanged to true
     * @param array $array Config values 
     */
    public function setDefaults($array){
        $values = '';
        foreach($array as $key => $val){
            if(!isset($this->configArray[$key])){
                $this->configArray[$key] = $val;
                $values .= '['.$key.' => '.$val.']';
                $this->isChanged = true;
            }
        }
        Logger::log('Set defaults for '.$this->config.' '.$values.'');
    }
    
    /**
     * Sets a default value $val to $key if anothe value does not exist already, if it is set the mothod sets $isChanged to true
     * @param string $key the key in the $configArray
     * @param string $val the value is set if it is not already defined
     */
    public function setDefaultValue($key, $val){
        if(!isset($this->configArray[$key])){
            $this->configArray[$key] = $val;
            $this->isChanged = true;
            Logger::log('Set default for '.$this->config.' ['.$key.' => '.$val.']');
        }
    }
    
    /**
     * Writes the file to if $isChanged is true
     */
    public function __destruct() {
        if($this->isChanged)
            $this->write();
    }
    
}
