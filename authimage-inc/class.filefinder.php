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
class FileFinder {

	function getRandomFile($dir,$exts) {
		/* pick one file randomly from directory */
		/* Suggested by Gadi Cohen <dragon at wastelands dot net>*/
		
		$filelist = $this->scanDir($dir, $exts);
		if (sizeof($filelist) > 0) {
			srand((double)microtime()*1000000);
			return $dir . $filelist[array_rand($filelist)];
		}
		else
			return FALSE;

	}
	/* function of rolandfx_SPAMMENOT_ at bellsouth dot net */
	function scanDir($dir,$exts) {

		$adir = opendir($dir);
		$files = readdir($adir);
		while (false !== ($files = readdir($adir))) {
			foreach ($exts as $value) {
				if ($this->checkExt($files, $value)) {
					$match_files[] = $files;
					break; //No need to keep looping if we've got a match.
				}
			}
		}
		return $match_files;
		closedir($adir);

	}

	function checkExt($filename, $ext) {
		$passed = FALSE;
		$testext = "\.".$ext."$";
		if (eregi($testext, $filename)) {
		$passed = TRUE;
		}
		return $passed;
	}

	function getExt($file) {

		/* ask the file type */
		$fileinfo  = pathinfo($file);
		return $fileinfo["extension"];

	}
}

?>