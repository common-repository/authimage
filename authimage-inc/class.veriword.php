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
## veriword.ini
##
## USAGE
## create some image with noise texture, put in image directory,
## put some true type font into font directory,
## put a dictionary file into dict directory
##
##
##
## see sample.php for test and usage
##
####

require_once("class.captcha.php");
require_once("class.configreader.php");


class VeriWord extends Captcha {

	var $im;
 	var	$randomword 	= array();
	var	$noise			= array();
	var	$wordart 		= array();
	var $capctha		= array();

	function VeriWord() {
		/* create new session  */
		session_start();

	}

	function setConfiguration () {
		$config = new ConfigReader("veriword.ini");
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

		/* get the CAPTCHA image  */
		$this->im = $this->getCaptcha();

		/* set variable session for verification */
		if(isset($_SESSION['veriword'])) unset($_SESSION['veriword']);
		$_SESSION['veriword'] 		= md5(strtolower($this->getWord()));
	}

	function getWord() {
		return $this->word;
	}

	function outputImage() {
		/* make it not case sensitive*/
		$this->imtype = strtolower($this->captcha['output']);

		/* check image type availability */
		$this->validateType();


		/* show the image  */
		switch($this->imtype){
			case 'jpeg' :
			case 'jpg' 	:
				header("Content-type: image/jpeg");
				imagejpeg($this->im);
				break;
			case 'gif' :
				header("Content-type: image/gif");
				imagegif($this->im);
				break;
			case 'png' :
				header("Content-type: image/png");
				imagepng($this->im);
				break;
			case 'wbmp' :
				header("Content-type: image/vnd.wap.wbmp");
				imagewbmp($this->im);
				break;
		}
	}

	function validateType() {
		/* check image type availability*/
		$is_available = FALSE;

		switch($this->imtype){
			case 'jpeg' :
			case 'jpg' 	:
				if(function_exists("imagejpeg"))
				$is_available = TRUE;
				break;
			case 'gif' :
				if(function_exists("imagegif"))
				$is_available = TRUE;
				break;
			case 'png' :
				if(function_exists("imagepng"))
				$is_available = TRUE;
				break;
			case 'wbmp' :
				if(function_exists("imagewbmp"))
				$is_available = TRUE;
				break;
		}
		if(!$is_available && function_exists("imagejpeg")){
			/* if not available, cast image type to jpeg*/
			$this->imtype = "jpeg";
			return TRUE;
		}
		else if(!$is_available && !function_exists("imagejpeg")){
		   die("No image support in this PHP server");
		}
		else
			return TRUE;
	}

}

?>