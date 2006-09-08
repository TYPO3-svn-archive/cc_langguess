<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2003-2006 Rene Fritz (r.fritz@colorcube.de)
*  All rights reserved
*
*  This script is part of the Typo3 project. The Typo3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * servivc 'langguess' for the 'cc_langguess' extension.
 *
 * @author	Rene Fritz <r.fritz@colorcube.de>
 */


require_once(PATH_t3lib.'class.t3lib_svbase.php');
require_once(PATH_t3lib.'class.t3lib_exec.php');

class tx_cclangguess_sv1 extends t3lib_svbase {
	var $prefixId = 'tx_cclangguess_sv1';		// Same as class name
	var $scriptRelPath = 'sv1/class.tx_cclangguess_sv1.php';	// Path to this script relative to the extension dir.
	var $extKey = 'cc_langguess';	// The extension key.



	/**
	 * performs the language detection
	 *
	 * @param	string 	Content which should be processed.
	 * @param	string 	unused
	 * @param	array 	Configuration array
	 * @return	boolean
	 */
	function process($content='', $type='', $conf=array())	{
		global $LANG, $TYPO3_CONF_VARS;

		$this->out = '';

		if (!$conf['encoding'])	$conf['encoding'] = $TYPO3_CONF_VARS['BE']['forceCharset'];

		if (!$conf['encoding']=='utf-8' AND is_object($LANG)) {

			if (!$conf['encoding'])	$conf['encoding'] = 'iso-8859-1';
			$charset = $LANG->csConvObj->parse_charset($conf['encoding']);

			if ($charset!='utf-8')	{
				$content = $content ? $content : $this->getInput();
				$content = $LANG->csConvObj->utf8_encode($content, $charset);
			}
		}


		if (!$content) {
			$content = $this->getInput();
		}
		if ($content) {
				// remove newlines because content is expected in one line - otherwise the lang for each line will be detected
			$content = str_replace("\n", ' ', $content);

			$this->setInput ($content, $type);
		}

		if($inputFile = $this->getInputFile()) {

			$cmd = t3lib_exec::getCommand('perl');
			$cmd.=' '.t3lib_extMgm::extPath('cc_langguess').'lang_guess.pl';
			$cmd.=' -d '.t3lib_extMgm::extPath('cc_langguess').'lang_guess/';
			$cmd.=' 2>&1';
			$cmd.=' <'.$inputFile;

			if ($fpw = @popen ($cmd, 'r')) {

				$read = @fread($fpw, 200);
				@pclose($fpw);
				$read = str_replace('unknown script: \'\'', '', $read);

				list($lang) = explode("\n", $read);
				$lang = trim($lang);
			}

			if(strlen($lang)==2) {
				$this->out = strtoupper($lang);
			} else {
				$this->out = false; // no iso code - better set to false
			}

		} else {
			$this->errorPush(T3_ERR_SV_NO_INPUT, 'No or empty input.');
		}

		return $this->getLastError();
	}


// 	used to rename original language files with ISO prefix

//	function prependLangfilesWithIsoCode () {
//		$path = t3lib_extMgm::extPath('cc_langguess').'lang_guess/train/';
//
//		$handle=opendir($path);
//		while ($file = readdir ($handle)) {
//			if ($file != '.' && $file != '..') {
//
//				list($lang) = explode('.',$file);
//				list($lang) = explode('-',$lang);
//
//				if(strlen($lang)>2) {
//					echo "$file<br />";
//					echo "$lang<br />";
//
//					$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('lg_iso_2', 'static_languages', 'lg_name_en="'.ucfirst($lang).'"');
//					if ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))	{
//						$newfile=strtolower($row['lg_iso_2']).'-'.$file;
//						echo "$newfile<br />";
//						rename ($path.$file, $path.$newfile);
//					}
//
//				}
//			}
//		}
//		closedir($handle);
//	}

}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cc_langguess/sv1/class.tx_cclangguess_sv1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cc_langguess/sv1/class.tx_cclangguess_sv1.php']);
}

?>
