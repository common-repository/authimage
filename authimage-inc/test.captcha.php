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

require_once("class.captcha.php");


$imgcaptcha = new Captcha;

$captcha['width'] = 200;
$captcha['height'] = 80;

$randomword['type'] = 'DictionaryWord';
$randomword['dict'] = 'words/words.txt';
$randomword['length'] = 5;

//Noise
$noise['type'] = 'RandomNoise';
$noise['dir'] = 'noises/';


//WordArt
$wordart['dir'] = 'fonts/';
$wordart['capital'] = TRUE;
$wordart['color'] = '#000000';
$wordart['angle'] = 20;
$wordart['filter'] = 'Wavy';

$imgcaptcha->setConfiguration($captcha, $randomword,$noise,$wordart);
$im = $imgcaptcha->getCaptcha();

header("Content-type: image/jpeg");
imagejpeg($im);
?>