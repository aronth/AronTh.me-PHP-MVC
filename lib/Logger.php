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
 * Description of Logger
 *
 * @author Aron Þór
 */
class Logger {
    
    /**
     * The log messages in an array
     * @var array
     */
    private static $lines = array();
    
    /**
     * Add a message to the log if the framework is in debug mode
     * @param string $msg The log message
     */
    public static function log($msg){
        if(Aronth::DEBUG)
            self::$lines[] = $msg;
    }
    
    /**
     * Returns the prepared file as a string
     * @return string
     */
    private static function getLogAsFile(){
        $ret = "## This error log file was created on ".date('d/m/Y').' at '.date('G:s').PHP_EOL;
        $ret .= "## Request sent ".NavigationHelper::getRequest().PHP_EOL;
        $ret .= "## Version: ".Aronth::VERSION.'('.Aronth::BUILD.')'.PHP_EOL;
        if(count(self::$lines) > 0) {
            foreach(self::$lines as $line){
                $ret.='-'.$line.PHP_EOL;
            }
        }
        return $ret;
    }
    
    /**
     * Saves the file to the logs folder
     */
    private static function saveLogFile(){
        file_put_contents(DIR_LOGS.'LOG.'.Aronth::getURLParameter(0).'.'.Aronth::getURLParameter(1).'_'.date('d:m:Y-G:s').'.txt', self::getLogAsFile());
    }
    
    /**
     * Saves the file when the script ends id the framework is in debug mode
     */
    public static function saveLog() {
        if(Aronth::DEBUG)
            self::saveLogFile();
    }
    
}
