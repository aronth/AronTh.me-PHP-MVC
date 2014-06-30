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
 * Description of NavigationHelper
 *
 * @author Aron Þór
 */
class NavigationHelper {
    
    /**
     * Returns the request from the browser
     * @return string
     */
    public static function getRequest(){
        return $_SERVER['REQUEST_URI'];
    }
    
    /**
     * Returns a link to a page
     * Feed it string arguments to make the link
     * @return string The link
     */
    public static function getLinkToPage(){
        return '/'.implode('/', func_get_args());
    }
    
    /**
     * Gets a link with the domain
     * Feed it string arguments to make the link
     * @return string the link
     */
    public static function getAbsoluteLinkToPage(){
        return 'http://'.$_SERVER['SERVER_NAME'].'/'.implode('/', func_get_args());
    }
    
}
