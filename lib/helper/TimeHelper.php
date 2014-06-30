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
 * Description of TimeHelper
 *
 * @author Aron Þór
 */
class TimeHelper {
    
    public static function getDayOfWeek(){
        return date('w');
    }
    
    public static function getDayOfMonth(){
        return date('j');
    }
    
    public static function getDayOfYear(){
        return date('z');
    }
    
    public static function getWeekOfYear(){
        return date('W');
    }
    
    public static function isLeapYear(){
        return date('L') == 1 ? true : false;
    }
    
    public static function getYear(){
        return date('Y');
    }
    
    public static function getTimezone(){
        return date('e');
    }
    
}
