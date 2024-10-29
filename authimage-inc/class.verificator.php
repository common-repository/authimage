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
class VeriFicator {

	var $postvar;

	function VeriFicator($postvar) {
		/* create session  */
		session_start();
		$this->postvar = $postvar;
	}

	function verifyWord() {

		$submittedveriword = strtolower($this->postvar);

		if (md5($submittedveriword) == $_SESSION['veriword'])
			return true;
		else
			return false;
	}

	function verified() {
		if($this->verifyWord()) return true;
		else return false;

	}

}


?>