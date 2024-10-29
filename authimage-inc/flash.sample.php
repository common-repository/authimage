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
## class.filter.php
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
#######

require_once("class.verificator.php");
$veri = new VeriFicator($_POST['veriword']);
$verified = $veri->verified();



if($verified){	
echo "You typed the right word";
}
else {
?>
<form action="" method="post">
<table width="276" border="0" cellpadding="4" cellspacing="0" bgcolor="#1242A6" style="border-color:#B0C5EA; border-style:solid; border-width:1 ">
    <tr>
      <td colspan="3" height="8"></td>
      </tr>
	<tr>
	  <td width="1">&nbsp;</td>
	  <td colspan="2"><? include("flash.veriword.php");?>
	  </td>
	  </tr>
	<tr>
      <td>&nbsp;</td>
	  <td width="95"><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">text verification</font></td>
	  <td width="154"><input name="veriword" type="text" id="veriword" style="font-size=14;height=20;width=100" maxlength="10"></td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="submit" name="Submit" value="Submit"></td>
      </tr>
    <tr>
      <td colspan="3" height="8"></td>
      </tr>
  </table>
</form>
<?
}
?>