<?php
if (!$conf = unserialize($TYPO3_CONF_VARS['EXT']['extConf']['nh_shared_ce']))
	die ('ERROR: Server configuration could not be read. Check extension manger!');

if (!t3lib_div::inList($conf['allowedHosts'], $_SERVER['REMOTE_ADDR'])) {
	$hostname = (!empty($_SERVER['REMOTE_HOST'])) ? ($_SERVER['REMOTE_HOST']) : (gethostbyaddr($_SERVER['REMOTE_ADDR']));
	if (!t3lib_div::inList($conf['allowedHosts'], $hostname) )
	die ('ERROR: The client host '.$hostname.' ('.$_SERVER['REMOTE_ADDR'].') is not allowed to access the nh_shared_ce server!');
}

tslib_eidtools::connectDB();

if (t3lib_div::_GET('getcategories')) {
	$content = getCategories();
} elseif ($file = t3lib_div::_GET('getimage')) {
	$content = getImage($file);
} else {
	$content = getContent();
}
echo $content;

function getImage($file) {
	$ret='';
	$file = t3lib_div::getFileAbsFileName($file);
	if (!t3lib_div::_GET('md5') || t3lib_div::_GET('md5') != md5_file($file)) {
		if (t3lib_div::isAllowedAbsPath(dirname($file))) {
				if ($fp = fopen($file, 'r')) {
					$ret = $contents = fread($fp, filesize($file));
				}	else {
					$ret = 'ERROR: Unable to open image '.$file;
				}
		} else {
			$ret = 'ERROR: Invalid image path';
		}
	}
	return $ret;
}

function getContent() {
	GLOBAL $TYPO3_DB;
	$whereAdd = '';
	if ($cat = t3lib_div::_GET('cat')) {
		$whereAdd = ' AND mm.uid_foreign in (' . $TYPO3_DB->cleanIntList($cat) . ')';
	}

	$sql = $TYPO3_DB->SELECTquery('ce.*',
		'tt_content ce, tt_content_tx_nhsharedce_categories_mm mm',
		'mm.uid_local=ce.uid AND ce.hidden=0 AND ce.deleted=0'.$whereAdd
	);
	$res = $TYPO3_DB->sql_query($sql);
	$ret = res2xml($res);
	return $ret;
}

function getCategories(){
	GLOBAL $TYPO3_DB;
	$sql = $TYPO3_DB->SELECTquery('uid, title',
		'tx_nhsharedce_categories',
		'hidden=0 AND deleted=0'
	);
	$res = $TYPO3_DB->sql_query($sql);
	$ret = res2xml($res);
	return $ret;
}

function res2xml($res) {
	GLOBAL $TYPO3_DB;
	$xmlArr = array();
	while ($row = $TYPO3_DB->sql_fetch_assoc($res))
		$xmlArr[] = $row;
	$xml = t3lib_div::array2xml_cs($xmlArr, 'tt_content');
	return $xml;
}
?>