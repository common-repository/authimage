<?php
######
## VeriWord
## A PHP CAPTCHA System
## Version 2
#######
## Author: Huda M Elmatsani
## Email: 	justhuda ## netscape ## net
##
## 25/07/2004
#######
## Copyright (c) 2004 Huda M Elmatsani All rights reserved.
## This program is free for any purpose use.
########
##
## PACKAGE OF VERIWORD V2
## sample.php
## image.veriword.php
## class.veriword.php
## class.captcha.php
## class.noisegenerator.php
## class.wordgenerator.php
## class.wordart.php
## class.filefinder.php
## class.configreader.php
## veriword.ini
##
#######
require_once("class.filefinder.php");

class ConfigReader extends FileFinder {
    var $configfile;
    var $arrconfigs = array();

	function ConfigReader($filename) {

		if(is_file($filename)){
			$this->configfile = $filename;
		}
		else
			die(FILE_DICT_NOT_FOUND);
	}


	function readConfig() {

		$fd = fopen ($this->configfile, "r");
		while( !feof( $fd ) )
		{
			$line = fgets( $fd, 4096 );
            if (eregi( "^[A-Z]", $line )) {
                list ( $key, $val ) = split( "=", $line );
                $key = trim($key);
                $val = trim($val);
                $configs[$key] = $val;
            }
		}
		 fclose ($fd);
		$this->arrconfigs = $configs;

	}

    function getConfig($key) {
        if(array_key_exists($key,$this->arrconfigs))
            return $this->arrconfigs[$key];
        else return '';
    }
}

?>