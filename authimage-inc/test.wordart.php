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
include("class.wordgenerator.php");
include("class.wordart.php");


$word = new DictionaryWord;
$word->setDictionary(("words/words.txt"));
$word->setLength(5);
$word->pickWord();
$theword = $word->getWord();

$imgword = new WordArt(120,40);
$imgword->setFontDirectory('fonts/');
$imgword->setWord($theword);
$imgword->setTextAngle(5);
$imgword->setFontColor('#000000');
$imgword->drawText();
$imgword->applyFilter("Wavy");
$im = $imgword->getWordArt();
header("Content-type: image/jpeg");
imagejpeg($im);
?>