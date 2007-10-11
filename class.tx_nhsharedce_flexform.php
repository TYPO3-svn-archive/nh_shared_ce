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
/**
 * Plugin 'Flexform helper' for the 'nh_shared_content' extension.
 *
 * @author    Nikolas Hagelstein <nikolas.hagelstein@gmail.com>
 * @package    TYPO3
 * @subpackage    tx_nhsharedce
 */

class tx_nhsharedce_flexform {
	function getCategoryItems($config) {
		ini_set('display_errors', 1);
		$pi_flexform = t3lib_div::xml2array($config['row']['pi_flexform']);

		if (!is_array($pi_flexform) || !$server = $pi_flexform['data']['sDEF']['lDEF']['server']['vDEF'])
			return $config;

		$client = &t3lib_div::getUserObj('EXT:nh_shared_ce/class.tx_nhsharedce_client.php:tx_nhsharedce_client');
		$buffer = $client->doRequest("http://$server/index.php?eID=tx_nhsharedce_pi1&getcategories=1");
		$arr = t3lib_div::xml2array($buffer);
		if (is_array($arr)) {
			foreach($arr as $k => $v)
				$config['items'][] =array($v['title'], $v['uid']);
		}

/*
		t3lib_div::debug($pi_flexform['data']['sDEF']['lDEF']['server']);
		t3lib_div::debug($content);
		t3lib_div::debug($arr);
*/

	}
}
?>