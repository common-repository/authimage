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
require_once("class.filter.php");

class WordArt {

	var $width;
	var $height;
	var $text_angle=0;
	var $text_color = "#000000";
    var $image;
    var $word;
	var $fontname;
	var $typing;

	function WordArt($width, $height) {
		$this->width = $width;
		$this->height = $height;
	}

	function setWord($word) {
		$this->word = $word;
	}

	function setCapital($flag=FALSE) {
		if($flag)
			$this->word = strtoupper($this->word);
    }

	function setFontDirectory($directory){
		$this->fontdirectory = $directory;
	}

	function setFont($filename)  {
	   $this->fontname = $filename;
	}

	function setFontColor($hexcolor){
	   $this->text_color = $hexcolor;
	}

	function setTextAngle($degree) {
		$this->text_angle = $degree;
	}

	function setTyping($typing='linear') {
		$this->typing = $typing;
	}

	function getFont() {
		$font = new FontChooser;
		if($this->fontdirectory && $this->fontname) {
			$font->setFontDirectory($this->fontdirectory);
			return $font->getFont($this->fontname);
		} else if($this->fontdirectory && !$this->fontname) {
			$font->setFontDirectory($this->fontdirectory);
			return $font->getRandomFont();
		} else
			return $font->getRandomBuiltinFont();

	}

	function drawText() {

		$text_font 		= $this->getFont();

		/* create canvas for text drawing */
		$im_text 		= @imagecreate ($this->width, $this->height);
		$this->bg_color 		= imagecolorallocate ($im_text, 255, 255, 255);
		/* pick color for text */
		$RGB_color 		= $this->hex2rgb( $this->text_color );
		$text_color 	= imagecolorallocate ($im_text, $RGB_color['R'], $RGB_color['G'], $RGB_color['B']);

		/* numeric means built-in font */
		if(is_numeric($text_font)) {

			$text_width 	= imagefontwidth($text_font) * strlen($this->word);
			$text_height 	= imagefontheight($text_font);
			$margin			= $text_width * 0.3;
			$im_string 		= @imagecreatetruecolor( $text_width + $margin, $text_height + $margin );

			/* calculate center position of text */
			$text_x     	= $margin/2;
			$text_y 		= $margin/2;

			imagestring ( $im_string, $text_font, $text_x, $text_y, $this->word, $text_color );

			//resize the text, because built-in font is very small
			imagecopyresampled ($im_text,
							$im_string,
							0, 0, 0, 0,
							$this->width,
							$this->height,
							$text_width+$margin,
							$text_height+$margin);

		} else {
			/* initial text size */
			$text_size	= 40;
			/* calculate text width and height */
			$box 		= imagettfbbox ( $text_size, $this->text_angle, $text_font, $this->word);
			$text_width	= $box[2]-$box[0]; //text width
			$text_height= $box[5]-$box[3]; //text height

			/* adjust text size */
			$text_size  = round((20 * $this->width)/$text_width);

			/* recalculate text width and height */
			$box 		= imagettfbbox ( $text_size, $this->text_angle, $text_font, $this->word);
			$text_width	= $box[2]-$box[0]; //text width
			$text_height= $box[5]-$box[3]; //text height

			/* calculate center position of text */
			$text_x     	= ($this->width - $text_width)/2;
			$text_y 		= ($this->height - $text_height)/2;

			/* draw text into canvas */
			if($this->typing=='linear') {

				imagettftext(	$im_text,
								$text_size,
								$this->text_angle,
								$text_x,
								$text_y,
								$text_color,
								$text_font,
								$this->word);
			} else {

				$word_length = strlen($this->word);
				$char_words = preg_split('//', $this->word, -1, PREG_SPLIT_NO_EMPTY);
				$char_width_avg =  round($text_width / $word_length) + 0.12 * $text_width;
				$text_x = ($this->width - $word_length * $char_width_avg)/2;

				/* type character one by one with various size and angle */
				for($i=0; $i < $word_length; $i++) {

					$rsize = rand(0,10);
					$rangle = rand(-20,20);
					$rlift = rand(-5,5);

					imagettftext(	$im_text,
									$text_size+$rsize,
									$this->text_angle+$rangle,
									$text_x+$i*$char_width_avg,
									$text_y+$rlift,
									$text_color,
									$text_font,
									$char_words[$i]);
				}
			}
		}

		$this->image = $im_text;
	}

	function applyFilter($filtername) {

		if(!$filtername) return;
		$im = new $filtername;
		$im->setImage($this->image);
		$im->run();
		$im_filtered = $im->getImage();

		$this->image =  $im_filtered;
		$this->bg_color = imagecolorallocate ($this->image, 255, 255, 255);
    }

	function getWordArt() {

		imagecolortransparent($this->image, $this->bg_color);

        return $this->image;
		imagedestroy($this->image);

    }

    function hex2rgb($color) {

		$color = str_replace('#', '', $color);
		$ret = array(
			'R' => hexdec(substr($color, 0, 2)),
			'G' => hexdec(substr($color, 2, 2)),
			'B' => hexdec(substr($color, 4, 2))
		);
		return $ret;
	}
}

class FontChooser extends FileFinder {

	var $dirfont;

	function setFontDirectory($directory) {
		if(is_dir($directory)){
			$this->dirfont = $directory;
		} else
			die(FONT_DIR_NOT_FOUND);
	}

	function getRandomFont() {

		$exts = array("ttf","TTF");
		$filelist = $this->scandir($this->dirfont, $exts);
		if (sizeof($filelist) > 0){
			srand((double)microtime()*1000000);
			return $this->dirfont . $filelist[array_rand($filelist)];
		}
	}

	function getFont($filename) {
		if(is_file($filename)){
			return $this->dirfont . $filename;
		} else
			die(FONT_NOT_FOUND);
	}

	function getRandomBuiltinFont() {
		   return rand(1,5);
	}


}



?>