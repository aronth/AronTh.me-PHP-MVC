<?php

// This file contains functions for setting up the template

function formatPageTitle($pageTitle){
    return Aronth::getSiteConfig()->getValue('sitename') . ($pageTitle != null ? ' | ' . $pageTitle : '');
}

function formatNavigationLinks(){
    $nav = '<ul>';
    
    $nav .= '<li><a href="/">Home</a></li>';
    $nav .= '<li><a href="/home/about/">About</a></li>';
    $nav .= '<li><a href="/projects/">Projects</a></li>';
    $nav .= '<li><a href="/contact/">Contact</a></li>';
    
    $nav .= '</ul>';
    return $nav;
}

function addToTemplateData(){
    $data = array(
        'def_year' => date('y'),
        'usertitle' => "Welcome",
        'user' => getUser()
    );
    return $data;
}

function getUser(){
    $user = '';
    if(User::isLoggedIn()){
        $user .= '<h2 class="username">'.User::getUserData('username').'</h2>';
        $user .=  '<a href="'.NavigationHelper::getLinkToPage('profile', 'me').'"><button>My Profile!</button></a>'
                . '<a href="'.NavigationHelper::getLinkToPage('login', 'logout').'"><button>Logout!</button></a>';
        if(User::getUserData('isAdmin') == 1)
            $user .= '<a href="'.NavigationHelper::getLinkToPage('admin').'"><button>Control Panel!</button></a>';
    }else{
        $user .= '<a href="'.NavigationHelper::getLinkToPage('login', '').'"><button>Login!</button></a>'
               . '<a href="'.NavigationHelper::getLinkToPage('login', 'signup').'"><button>Signup!</button></a>';
    }
    return $user;
}