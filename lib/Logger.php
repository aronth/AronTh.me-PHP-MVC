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
    
    public static $instance;
    
    private $filename;
    public $lines = array();
    
    public function __construct($filename) {
        $this->filename = $filename;
    }
    
    public static function log($msg){
        self::$instance->lines[] = $msg;
    }
    
    private function getLogAsFile(){
        $ret = "## This error log file was created on ".date('d/m/Y').' at '.date('G:s:u').PHP_EOL;
        if(count(self::$instance->lines) > 0) {
            foreach(self::$instance->lines as $line){
                $ret.=$line.PHP_EOL;
            }
        }
        return $ret;
    }
    
    private function saveLogFile(){
        file_put_contents(APP_LOGS.'LOG_'.Aronth::getURLParameter(0).'_'.Aronth::getURLParameter(1).'_'.date('d_m_Y_G_s_u').'.txt', $this->getLogAsFile());
    }
    
    public function __destruct() {
        $this->saveLogFile();
    }
    
}
