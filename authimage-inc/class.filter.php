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
## class.filter.php
## class.filefinder.php
## class.configreader.php
## veriword.ini
##
#######
class Filter {

	var $image;
	var $filtered;
	var $width;
	var $height;

	function setImage($image) {

		$this->image      = $image;
		$this->width 	  = imagesx($image);
		$this->height 	  = imagesy($image);
	}

	function getImage() {
		return $this->filtered;
	}

}

class Wavy extends Filter {

	var $filtered;

	function run() {

		/* variable for waving*/
		srand((double)microtime()*1000000);
		$width_extra	= rand(10,20);


		/* create canvas for redrawing image */
		$canvas 	= @imagecreatetruecolor($this->width+$width_extra,
											$this->height+$width_extra);


		$dstX = 0;
		$dstY = 0;
		$dstW = $width_extra;
		$dstH = $this->width;
		$srcX = 0;
		$srcY = 0;
		$srcW = $width_extra;
		$srcH = $this->width-2*$width_extra;
		$h = rand(4,9);
		/* waving */
		for($i=0; $i < $this->width; $i++) {
			imagecopyresized($canvas, $this->image,
			$dstX+$i, $dstY,
			$srcX+$i, $srcY,
			$dstW+$i, $dstH+$width_extra*(sin(deg2rad(2*$i*$h))+sin(deg2rad($i*$h))),
			$srcW+$i, $srcH);
		}

		$this->filtered = $canvas;

	}

}

class Bubbly extends Filter {

	var $filtered;

	function run() {
		/* bubbling image */
		$black = imagecolorallocate ($this->image, 0, 0, 0);
		$white = imagecolorallocate ($this->image, 255, 255, 255);

		for($i=0; $i < $this->width; $i=$i+5) {
			srand((double)microtime()*1000000);
			$w = rand(6,10);
			$y = rand(0,$this->height);
			imagefilledellipse ( $this->image, $i, $y, $w+2, $w+2, $black );
			imagefilledellipse ( $this->image, $i, $y, $w, $w, $white );
			srand((double)microtime()*1000000);
			$w = rand(6,10);
			imagefilledellipse ( $this->image, $i, rand(0,$this->height), $w, $w, $black );
			$w = rand(4,10);
			imageellipse ( $this->image, $i, rand(0,$this->height), $w, $w, $black );
		}

		$this->filtered = $this->image;

	}
}

class BreakType extends Filter {

	var $filtered;

	function run() {

		/* variable for waving*/
		srand((double)microtime()*1000000);
		$width_extra	= rand(10,20);


		/* create canvas for redrawing image */
		$canvas1 	= @imagecreatetruecolor($this->height,$this->height);
		$canvas2 	= @imagecreatetruecolor($this->width,$this->height);


		imagecopyresized($canvas1, $this->image,
			0, 0,
			0, 0,
			$this->height, $this->height,
			$this->width, $this->height);


		imagecopyresized($canvas2, $canvas1,
			0, 0,
			0, 0,
			$this->width, $this->height,
			$this->height, $this->height);

		$this->filtered = $canvas2;

	}

}

?>