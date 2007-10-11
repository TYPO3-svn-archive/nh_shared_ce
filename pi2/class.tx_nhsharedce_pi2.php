<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2007 Nikolas Hagelstein <nikolas.hagelstein@gmail.com>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
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

require_once(PATH_tslib.'class.tslib_pibase.php');
require_once(PATH_t3lib .'class.t3lib_basicfilefunc.php');
/**
 * Plugin 'Shared CE client' for the 'nh_shared_content' extension.
 *
 * @author    Nikolas Hagelstein <nikolas.hagelstein@gmail.com>
 * @package    TYPO3
 * @subpackage    tx_nhsharedce
 */
class tx_nhsharedce_pi2 extends tslib_pibase {
	var $prefixId = 'tx_nhsharedce_pi2';
	var $scriptRelPath = 'pi2/class.tx_nhsharedce_p21.php';
	var $extKey = 'nh_shared_ce';
	var $pi_checkCHash = true;
	var $imgPath = 'uploads/tx_nhsharedce/';

	function main($content,$conf) {
		$this->pi_initPIFlexForm();
		$server = $this ->pi_getFFvalue($this->cObj->data['pi_flexform'], 'server');
		$categories = $this -> pi_getFFvalue($this->cObj->data['pi_flexform'], 'categories');

		if (empty($server)) {
			$content ='ERROR: No server specified.';
			return $this->pi_wrapInBaseClass($content);
		}

		$client = &t3lib_div::getUserObj('EXT:'.$this->extKey.'/class.tx_nhsharedce_client.php:tx_nhsharedce_client');
		$buffer = $client->doRequest("http://$server/index.php?eID=tx_nhsharedce_pi1&cat=$categories");
		if (!empty($client->error))
			return $this->pi_wrapInBaseClass($client->error);

		$arr = t3lib_div::xml2array($buffer);
		$cObj =t3lib_div::makeInstance('tslib_cObj');
		$fileFunc = &t3lib_div::makeInstance('t3lib_basicFileFunctions');
		$imgPathOrg = $GLOBALS['TSFE']->tmpl->setup['tt_content.']['image.']['20.']['imgPath'];
		$GLOBALS['TSFE']->tmpl->setup['tt_content.']['image.']['20.']['imgPath'] = $this->imgPath;

		foreach ($arr as $k => $v) {
		  $images = array();
		  preg_match_all("@<img.*?src\s*=\s*['\"](.*?)['\"].*?>@i", $v['bodytext'], $images);

			if (!empty($v['image'])) {
				$images[1] = array_merge($images[1], split(',', $v['image']));
			}

			if ($images[1]) {
				$fileNameOld = $fileNameNew = array();
				$i = 0;
				foreach($images[1] as $img) {

					$orgPath =  (dirname($img)=='.' ? ('uploads/pics') : dirname($img));
					$fileName = basename($img);
					$fileNameOld[$i] = $img;

					$urlParam = '';
					if ($md5 = @md5_file(t3lib_div::getFileAbsFileName($this->imgPath.$fileName))) {
						$urlParam='&md5='.$md5;
						$fileNameNew[$i] = $this->imgPath.$fileName;
					} else {
						$fileNameNew[$i] = $fileFunc->getUniqueName($fileName , $this->imgPath);
					}

					$buffer = $client->doRequest("http://$server/?eID=tx_nhsharedce_pi1&getimage=$orgPath/$fileName$urlParam");
					if (empty($client->error) && !empty($buffer))
						t3lib_div::writefile($fileNameNew[$i], $buffer);

					$i++;
					/*
					echo "$img -> $path -> $filename -> $fileNameOld[$i] -> $fileNameNew[$i] <br />";
					echo "http://$server/?eID=tx_nhsharedce_pi1&getimage=$orgPath/$fileName$urlParam<br />";
					echo $client->error."<br /><br />";
					*/
				}
				$v['bodytext'] = str_replace($fileNameOld, $fileNameNew, $v['bodytext']);
			}

			$cObj->start($v, '_NO_TABLE');
			$content.=$cObj->cObjGetSingle('<tt_content', array());
		}


		$GLOBALS['TSFE']->tmpl->setup['tt_content.']['image.']['20.']['imgPath'] = $imgPathOrg;

		return $this->pi_wrapInBaseClass($content);
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/nh_shared_ce/pi2/class.tx_nhsharedce_pi2.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/nh_shared_ce/pi2/class.tx_nhsharedce_pi2.php']);
}
?>