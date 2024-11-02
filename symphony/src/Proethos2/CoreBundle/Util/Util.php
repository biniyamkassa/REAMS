<?php

// This file is part of the ProEthos Software. 
// 
// Copyright 2013, PAHO. All rights reserved. You can redistribute it and/or modify
// ProEthos under the terms of the ProEthos License as published by PAHO, which
// restricts commercial use of the Software. 
// 
// ProEthos is distributed in the hope that it will be useful, but WITHOUT ANY
// WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
// PARTICULAR PURPOSE. See the ProEthos License for more details. 
// 
// You should have received a copy of the ProEthos License along with the ProEthos
// Software. If not, see
// https://github.com/bireme/proethos2/blob/master/LICENSE.txt
 

namespace Proethos2\CoreBundle\Util;

class Util {

    var $container;
    var $doctrine;
    var $em;

    public function __construct($container, $doctrine) {
        
        $this->container = $container;
        $this->doctrine = $doctrine;
        $this->em = $this->doctrine->getManager();
    }

    public function getConfiguration($key) {

        $configuration_repository = $this->em->getRepository('Proethos2ModelBundle:Configuration');

        $configuration = $configuration_repository->findOneByKey($key);
        if($configuration and !empty($configuration->getValue())) {
           return $configuration->getValue(); 
        }

        $configuration = $this->container->getParameter($key);
        if(!empty($configuration)) {
           return $configuration; 
        }

        return NULL;
    }

    public function linkify($string) {
        // regex filter
        $reg_pattern = '!(http|ftp|scp)(s)?:\/\/[a-zA-Z0-9.?&_/=-]+!';

        // make the urls to hyperlinks
        return preg_replace($reg_pattern, "<a href=\"\\0\" target=\"_blank\" rel=\"noopener noreferrer\">\\0</a>", $string);
    }

    public static function return_bytes($val) {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        $numericVal = (int)substr($val, 0, -1); 
        switch($last) {
            // The 'G' modifier is available
            case 'g':
                
                $numericVal *= 1024;
            case 'm':
               
                $numericVal *= 1024;
            case 'k':
                $numericVal *= 1024;
        }
    
        return $numericVal;
    }

    private static function get_allowed_file_size_in_text(){
        $update_file_size = self::return_bytes(ini_get('upload_max_filesize'));
        $update_post_size = self::return_bytes(ini_get('post_max_size'));
        if($update_file_size > $update_post_size)
            return ini_get('post_max_size');
        return ini_get('upload_max_filesize');
    }

    public static function get_allowed_file_size(){
        //compare teh lowers file size and select the one to display
        return self::return_bytes(self::get_allowed_file_size_in_text());
    }

    public static function get_printable_file_size(){
        $pritable_text =strtolower(self::get_allowed_file_size_in_text());

        $search = ['m','g','k'];
        $repalce = ['MB','GB','KG'];

        return str_replace($search,$repalce,$pritable_text);

    }
}