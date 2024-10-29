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

class NoiseGenerator {

	var $im_noise;

	function pickRandomBackground($dir=''){}

	function getNoise() {
		return $this->im_noise;
	}


}

class BackgroundNoise extends NoiseGenerator {

	var $im_noise;

	function pickRandomBackground($dir) {
		$exts = array("gif","jpg$|\\.jpeg","png");
		$bgdata = new BackgroundReader($dir, $exts);
		$this->background = $bgdata->readBackground();
		$this->extension  = $bgdata->getExt($this->background);
	}

	function getNoise() {

		/* create "noise" background image from your image stock*/
		switch($this->extension){
			case 'jpeg' :
			case 'jpg' 	:
				$im_noise 	= @imagecreatefromjpeg ($this->background);
				break;
			case 'gif' :
				$im_noise 	= @imagecreatefromgif ($this->background);
				break;
			case 'png' :
				$im_noise 	= @imagecreatefrompng ($this->background);
				break;
		}
		return $im_noise;
		imagedestroy($im_noise);
	}

}

class RandomNoise extends NoiseGenerator {

	var $im_noise;
    function pickRandomBackground() {

		$width=100; $height=50;
		/* create "noise" background image*/
		$im_noise 	= @imagecreate($width,$height);
		$bg_color 	= imagecolorallocate ($im_noise, 255, 255, 255);
		imagefill ( $im_noise, 0, 0, $bg_color );

		for($i=0; $i < $height; $i++) {
			$c = rand (0,255);
            $d = rand (0,10);
            $e = rand (0,10);
            $f = rand (0,10);
			$line_color 	= imagecolorallocate ($im_noise, $c+$d, $c+$e, $c+$f);
			imagesetthickness ( $im_noise, rand(1,5) );
			imageline( $im_noise, 0, $i+rand(-15,15), $width, $i+rand(-15,15), $line_color );
		}

		$this->im_noise = $im_noise;
	}

    function getNoise() {
        return $this->im_noise;
        imagedestroy($im_noise);
    }

}

class BackgroundReader extends FileFinder {

	function BackgroundReader($dirname) {

		if(is_dir($dirname)){
			$this->dirname = $dirname;
		}
		else
			die(DIRECTORY_NOT_FOUND);
	}

	function readBackground() {
		$exts = array("gif","jpg$|\\.jpeg","png");
        return $this->getRandomFile($this->dirname,$exts);
	}

}

?>