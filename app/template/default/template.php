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
        'loginlink' => NavigationHelper::getLinkToPage('login', ''),
        'signuplink' => NavigationHelper::getLinkToPage('login', 'signup')
    );
    return $data;
}