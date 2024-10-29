<?
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
## put some FDB font into font directory,
## put a dictionary file into dict directory
##
#######
require_once("class.flashcaptcha.php");

$vword = new FlashCaptcha;
$vword->setConfiguration();
$vword->setCaptcha();
$vword->outputFlash();
$swf = $vword->getSWFName();
$w = $vword->getWidth();
$h = $vword->getHeight();

echo "<OBJECT classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://active.macromedia.com/flash2/cabs/swflash.cab#version=4,0,0,0\" ID=objects WIDTH=$w HEIGHT=$h>
<PARAM NAME=movie VALUE=$swf>
<EMBED src=$swf  WIDTH=$w HEIGHT=$h TYPE=\"application/x-shockwave-flash\" PLUGINSPAGE=\"http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash\">
</OBJECT>";
?>