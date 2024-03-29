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

// A shortcut to using DIRECTORY_SEPARATOR

define('DS', DIRECTORY_SEPARATOR);

// Defining the root path

define('WEB_ROOT', dirname(__DIR__));

//
//// The Core
//

// The bootstrap folder

define('WEB_BOOT', WEB_ROOT . DS . 'bootstrap' . DS);

// Here is the library

define('WEB_LIB', WEB_ROOT . DS . 'lib' . DS);

define('LIB_HELPER', WEB_LIB . 'helper' . DS);
define('LIB_CONFIG', WEB_LIB . 'config' . DS);
define('LIB_TEMPLATE', WEB_LIB . 'template' . DS);

define('DIR_LOGS', WEB_ROOT . DS . 'logs' . DS);

// 
//// The Application
//

define('APP_BASE', WEB_ROOT . DS . 'app' . DS);
define('APP_CONTROLLER', APP_BASE . 'controller' . DS);
define('APP_LANG', APP_BASE . 'lang' . DS);
define('APP_MODEL', APP_BASE . 'model' . DS);
define('APP_TEMPLATE', APP_BASE . 'template' . DS);
define('APP_VIEW', APP_BASE . 'view' . DS);
