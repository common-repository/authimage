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

class WordGenerator {

	var $word;
	var $length;

	function WordGenerator() {

	}

	function setDictionary(){}

	function setLength($n=0) {
		$this->length = $n;
	}

	function getWord(){

		  return $this->word;
	}

}

class RandomWord extends WordGenerator {

	var $word;
	var $length;

	function pickWord() {

		$randomword = '';
		$str_cons = "ng,ch,ty,ny,gy,py,by,q,w,r,t,y,p,s,d,f,g,h,j,k,l,z,x,c,v,b,n,m";
		$str_vowl = "i,a,u,e,o";

		$ar_cons = explode(",", $str_cons);
		$ar_vowl = explode(",", $str_vowl);

		while(strlen($randomword)<$this->length+18){
			srand((double)microtime()*1000000);
			$randomword .= sprintf("%s",$ar_cons[array_rand($ar_cons)]);
			$randomword .= sprintf("%s",$ar_vowl[array_rand($ar_vowl)]);
		}

		$this->word = substr($randomword,rand(0,9),$this->length);

	}


}

class DictionaryWord extends WordGenerator {

	var $dictionary;
	var $words;

	function DictionaryWord() {
	}

	function setDictionary($filename) {
		$dictdata = new DictionaryReader($filename);
		$this->dictionary = $dictdata->readDictionary();
	}

	function pickWord() {
		srand((double)microtime()*1000000);
		$choosed = $this->dictionary[array_rand($this->dictionary)];
		if($this->length) {
			if(strlen($choosed)==$this->length){
				$this->word = sprintf("%s", $choosed);
			}
			else $this->pickWord();
		} else {
			$this->word = sprintf("%s", $choosed);
		}
	}

}

class SemiDictionaryWord extends DictionaryWord {

	var $dictionary;
	var $words;
	var $test='';

	function SemiDictionaryWord() {
	}

	function setLength($n) {
		if($n>2)
			$this->length = $n-2;
	}

	function pickWord() {

		parent::pickWord();
		if($this->test=='passed') return;

		$randomword = $this->word;

		$endstr = substr($randomword,-1);

		$str_cons = "q,w,r,t,y,p,s,d,f,g,h,j,k,l,z,x,c,v,b,n,m";
		$str_vowl = "i,a,u,e,o";

		$ar_cons = explode(",", $str_cons);
		$ar_vowl = explode(",", $str_vowl);

		srand((double)microtime()*1000000);
		if(!in_array($endstr,$ar_cons))
		$randomword .= sprintf("%s",$ar_cons[array_rand($ar_cons)]);
		$randomword .= sprintf("%s",$ar_vowl[array_rand($ar_vowl)]);
		$this->test ='passed';
		$this->word = $randomword;

	}

}


class DictionaryReader extends FileFinder {

	var $dictfile;

	function DictionaryReader($filename) {

		if(is_file($filename)){
			$this->dictfile = $filename;
		}
		else
			die(FILE_DICT_NOT_FOUND);
	}

	function setDictionaryFormat() {

	}

	function readDictionary() {

		$fd = fopen ($this->dictfile, "r");
		while( !feof( $fd ) )
		{
			$line = fgets( $fd, 4096 );
			$dictionary[] = trim( $line );
		}
		 fclose ($fd);
		return  $dictionary;

	}

}

?>