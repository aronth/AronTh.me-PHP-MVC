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

/*
 * The autoload for the framework
 */

function aronthAutoload($className){
    $xpl = explode('\\', $className);
    if(count($xpl) > 1){
        if($xpl[0] == 'lib')
            return require_once WEB_LIB.$className.'.php';
        if($xpl[0] == 'controller')
            return require_once APP_CONTROLLER.$className.'.php';
        if($xpl[0] == 'model')
            return require_once APP_MODEL.$className.'.php';
        if($xpl[0] == 'helper')
            return require_once APP_HELPER.$className.'.php';
        return;
    }
    if(file_exists(WEB_LIB.$className.'.php'))
        return require_once WEB_LIB.$className.'.php';
    if(file_exists(APP_CONTROLLER.$className.'.php'))
        return require_once APP_CONTROLLER.$className.'.php';
    if(file_exists(APP_HELPER.$className.'.php'))
        return require_once APP_HELPER.$className.'.php';
    if(file_exists(APP_MODEL.$className.'.php'))
        return require_once APP_MODEL.$className.'.php';
}

// Registering the autoload function for calling

spl_autoload_register('aronthAutoload');