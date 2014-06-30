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
 * Description of OutputBufferHelper
 *
 * @author Aron Þór
 */
class OutputBufferHelper {
    
    /**
     * Starts output buffering
     */
    public static function start(){
        ob_start();
    }
    
    /**
     * Stops and cleans the buffer
     */
    public static function stopAndClean(){
        ob_end_clean();
    }
    
    /**
     * Stops and prints the buffer
     */
    public static function stopAndFlush(){
        ob_end_flush();
    }
    
    /**
     * Prints the data in the Output buffer
     */
    public static function flush(){
        ob_flush();
    }
    
    /**
     * Cleans the data in the Output buffer
     */
    public static function clean(){
        ob_clean();
    }
    
    /**
     * Gets the buffer contents
     */
    public static function getBufferContent(){
        return ob_get_contents();
    }
    
    /**
     * Gets the buffer contents and cleans it afterwards
     * @return string The buffer contents
     */
    public static function getBufferContentAndClean(){
        return ob_get_clean();
    }
    
}
