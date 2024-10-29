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
require_once("class.noisegenerator.php");
require_once("class.wordart.php");
require_once("class.wordgenerator.php");

class Captcha {

	var $width;
	var $height;
	var $image;
	var $word;

	var	$randomword 	= array();
	var	$noise			= array();
	var	$wordart 		= array();
	var $capctha		= array();


	function Captcha() {


	}

	function setConfiguration ($captchas, $randomwords,$noises,$wordarts) {
        $this->captcha = $captchas;
        $this->randomword = $randomwords;
        $this->noise = $noises;
        $this->wordart = $wordarts;
	}

	function getRandomWord() {
		$word = new  $this->randomword['type'];
		$word->setDictionary($this->randomword['dict']);
		$word->setLength($this->randomword['length']);
		$word->pickWord();
		return $word->getWord();
	}


	function getNoise() {
		$imnoise = new $this->noise['type'];
		$imnoise->pickRandomBackground($this->noise['dir']);
		return $imnoise->getNoise();
	}

	function getWordArt($word) {
		$imword = new WordArt($this->width,$this->height);
		$imword->setFontDirectory($this->wordart['dir']);
		$imword->setWord($word);
		$imword->setCapital($this->wordart['capital']);
		$imword->setTextAngle(rand(-$this->wordart['angle'],$this->wordart['angle']));
		$imword->setFontColor($this->wordart['color']);
		$imword->drawText();
		$imword->applyFilter($this->wordart['filter']);
		return $imword->getWordArt();
	}


	function getCaptcha() {
		$this->width  = $this->captcha['width'];
		$this->height = $this->captcha['height'];

		$this->word = $this->getRandomWord();
		$noise      = $this->getNoise();
		$wordart    = $this->getWordArt($this->word);

		$noise_width 	= imagesx($noise);
		$noise_height 	= imagesy($noise);

			/* resize the background image to fit the size of image output */
		$imcaptcha 		= @imagecreatetruecolor($this->width,$this->height);
		imagecopyresampled ($imcaptcha,
							$noise,
							0, 0, 0, 0,
							$this->width,
							$this->height,
							$noise_width,
							$noise_height);

		/* put text image into background image   */

		imagecopymerge ( 	$imcaptcha,
							$wordart,
							0, 0, 0, 0,
							$this->width,
							$this->height,
							80 );

		return $imcaptcha;

	}

}

?>