<?php
## v2   Beta   ## 27 October 2004
## v1.3 Stable ## 24 October 2004
## v1.2 Stable ## 18 August 2004
## v1.1 Stable ## 01 August 2004
## v1	Stable ## 27 July 2004
######
## VeriWord
## A PHP CAPTCHA System
##
## CAPTCHA is short for Completely Automated Public Turing test to tell Computers and Humans Apart,
## a technique  used by a computer to tell if it is interacting with a human or
## another computer. Because computing is becoming pervasive, and computerized tasks and
## services are commonplace, the need for increased levels of security has led to
## the development of this way for computers to ensure that they are dealing with humans
## in situations where human interaction is essential to security. Activities such as
## online commerce transactions, search engine submissions, Web polls, Web registrations,
## free e-mail service registration and other automated services are subject to
## software programs, or bots, that mimic the behavior of humans in order to skew
## the results of the automated task or perform malicious activities,
## such as gathering e-mail addresses for spamming or ordering hundreds of tickets
## to a concert.
## In order to validate the digital transaction, using the CAPTCHA system the user
## is presented with a distorted word typically placed on top of a distorted background.
## The user must type the word into a field in order to complete the process.
## Computers have a difficult time decoding the distorted words while humans can easily
## decipher the text. By entering that text, the user validates the transaction
## and the computer knows it is dealing with a human and not a bot.
## This definition is picked up from http://www.webopedia.com/TERM/C/CAPTCHA.html
##
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
## class.flashcaptcha.php
## flash.veriword.php
## veriword.ini
##
## USAGE
## create some SWF animation, put in movies directory,
## put some FDB font into font directory,
## put a dictionary file into dict directory
##
##
##
## see flash.sample.php for test and usage
##
####

error_reporting(0);

require_once("class.captcha.php");
require_once("class.configreader.php");
require_once("class.img2bitmap.php");

class FlashCaptcha extends Captcha {

	var $im;
 	var	$randomword 	= array();
	var	$noise			= array();
	var	$wordart 		= array();
	var $capctha		= array();
	var $flashcaptcha;
	var $swfname;

	function FlashCaptha() {
		/* create new session  */
		session_start();

	}

	function setConfiguration () {
		$config = new ConfigReader("flashveriword.ini");
        $config->readConfig();
		//Captcha
		$this->captcha['width']		= $config->getConfig('captcha_width');
		$this->captcha['height']	= $config->getConfig('captcha_height');
		$this->captcha['output']	= $config->getConfig('captcha_output');
		//RandomWord
		$this->randomword['type'] 	= $config->getConfig('randomword_type');
		$this->randomword['dict'] 	= $config->getConfig('randomword_dict');
		$this->randomword['length'] = $config->getConfig('randomword_length');
		//Noise
		$this->noise['type'] 		= $config->getConfig('noise_type');
		$this->noise['dir'] 		= $config->getConfig('noise_dir');

		//WordArt
		$this->wordart['dir'] 		= $config->getConfig('wordart_dir');
		$this->wordart['capital'] 	= $config->getConfig('wordart_capital');
		$this->wordart['color']		= $config->getConfig('wordart_color');
		$this->wordart['angle']		= $config->getConfig('wordart_angle');
		$this->wordart['typing']	= $config->getConfig('wordart_typing');
		$this->wordart['filter'] 	= $config->getConfig('wordart_filter');



    }

	function setCaptcha() {

		/* get captcha  */
		$this->word   = $this->getRandomWord();

		/* set variable session for verification */
		if(isset($_SESSION['veriword'])) unset($_SESSION['veriword']);
		$_SESSION['veriword'] 		= md5(strtolower($this->word));


	}

	function getWord() {
		return $this->word;
	}

	function getWidth() {
		return $this->captcha['width'];
	}

	function getHeight() {
		return $this->captcha['height'];
	}

	function createFlash() {

		$w = $this->getWidth();
		$h = $this->getHeight();

        $xFont=new SWFFont("_sans");
        $xText=new SWFText();
        $xText->setFont($xFont);
        $xText->setColor(0,0,0);
        $xText->setHeight(40);
        $xText->addString($this->getWord());
        $txtWidth = $xText->getWidth($this->getWord());
        $txtHeight = $xText->getAscent()+$xText->getDescent();;

		$txtMovie = new SWFMovie();
		$txtMovie->setDimension($w,$h);
		$txtMovie->setBackground(255,255,255);
		$txSprite=$txtMovie->add($xText);
        $txSprite->moveto(($w-$txtWidth)/2,2*$h/3);
		$swf = "tmp/".uniqid(time()).".swf";
		$txtMovie->save($swf);

		//animation SWF
		$aniMovie = new SWFMovie();
		$aniMovie->setDimension($w,$h);
		$aniMovie->setBackground(255,255,255);

		$aniMovie->add(new SWFAction("
			loadMovie('$swf','_level0');
			loadMovie('movies/ball.swf','_level1');
			loadMovie('movies/bar.swf','_level2');
			"));

		$aniMovie->nextFrame();

		$this->flashcaptcha = $aniMovie;

	}

	function outputFlash() {
		$this->createFlash();
		$this->swfname = "tmp/".uniqid(time()).".swf";
		$this->flashcaptcha->save($this->swfname);
		chmod($swf,755);
		unlink ($swf);

	}

	function getSWFName () {
		return $this->swfname;
	}

}
?>