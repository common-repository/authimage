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
## class.flashcaptcha.php
## flash.veriword.php
## veriword.ini
##
## USAGE
## create some SWF animation, put in movies directory,
## put a dictionary file into dict directory
##
#######
class Img2Bitmap {

	var $tempimg;

	function Img2Bitmap($image) {
		$this->tempimg = "tmp/".uniqid(time()).".jpg";
		imagejpeg ($image, $this->tempimg);
		imagedestroy($image);
	}

	function getBitmap() {
		$fp 	= fopen($this->tempimg,"rb");
		$i 		= fread($fp,filesize($this->tempimg));
		$bitmap 	= new SWFBitmap($i);
		fclose($fp);
		chmod($this->tempimg,755);
		unlink ($this->tempimg);

		return $bitmap;
	}


}


?>