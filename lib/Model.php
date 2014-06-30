<?php

/*
 * Copyright (C) Error: on line 4, column 33 in Templates/Licenses/license-gpl20.txt
  The string doesn't match the expected date/time format. The string to parse was: "23.6.2014". The expected format was: "MMM d, yyyy". Ronni
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
 * Description of Model
 *
 * @author Ronni
 */
class Model {
    
    /**
     * The database connection
     * @var \PDO 
     */
    private $database;
    
    /**
     * The constructor
     * Gets the database connection
     */
    public function __construct() {
        $this->database = Aronth::getDatabaseConnection();
    }
    
    /**
     * Gets a database connection
     * @return \PDO
     */
    protected function getDatabase(){
        return $this->database;
    }
    
    /**
     * Returns true if all the passed data is 
     * @return boolean/array
     */
    protected function checkForPostData(){
        $notThere = array();
        foreach(func_get_args() as $post){
            if(!isset($_POST[$post]) || $_POST[$post] == ""){
                $notThere[] = $post;
            }
        }
        return (count($notThere) == 0 ? true : $notThere);
    }
    
    /**
     * Returns a filtered post input
     * @param string $key The key in the $_POST array
     * @return string filtered string from Post Data
     */
    public function getPostData($key){
        return filter_input(INPUT_POST, $key);
    }
    
}
