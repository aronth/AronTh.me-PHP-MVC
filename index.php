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

// Turn on error reporting and displaing 
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get the bootstrap to setup for us
require_once 'bootstrap/paths.php';
require_once WEB_BOOT.'bootstrap.php';

// Get the core of the site
$aronth = new Aronth();

// Initialize the rendering
$aronth -> init();