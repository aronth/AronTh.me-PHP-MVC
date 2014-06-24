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
 * Description of home
 *
 * @author Aron Þór
 */
class home extends Controller{
    
    public function initController() {
        $this->registerPage('about');
    }
    
    public function index() {
        Template::setPageTitle('Home');
        Template::addViewVar('pagetitle', 'Latest Articles');
        $this->getView('index');
    }
    
    public function about(){
        Template::setPageTitle('About');
        Template::addViewVar('pagetitle', 'About The Project');
        $this->getView('about');
    }
}
