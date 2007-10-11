<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_nhsharedce_categories=1
');
$TYPO3_CONF_VARS['FE']['eID_include']['tx_nhsharedce_pi1'] = 'EXT:nh_shared_ce/pi1/fe_index.php';
?>