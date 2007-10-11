<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');
$TCA['tx_nhsharedce_categories'] = array (
	"ctrl" => array (
		'title' => 'LLL:EXT:nh_shared_ce/locallang_db.xml:tx_nhsharedce_categories',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l18n_parent',
		'transOrigDiffSourceField' => 'l18n_diffsource',
		'sortby' => 'sorting',
		'delete' => 'deleted',
		'enablecolumns' => array (
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_nhsharedce_categories.gif',
	),
	'feInterface' => array (
		'fe_admin_fieldList' => 'sys_language_uid, l18n_parent, l18n_diffsource, hidden, title',
	)
);

$tempColumns = Array (
	'tx_nhsharedce_categories' => Array (
		'displayCond' => 'FIELD:CType:IN:text,textpic,html,menu,table',
		'exclude' => 1,
		'label' => 'LLL:EXT:nh_shared_ce/locallang_db.xml:tt_content.tx_nhsharedce_categories',
		'config' => Array (
			'type' => 'select',
			'foreign_table' => 'tx_nhsharedce_categories',
			'foreign_table_where' => 'ORDER BY tx_nhsharedce_categories.uid',
			'size' => 10,
			'minitems' => 0,
			'maxitems' => 100,
			'MM' => 'tt_content_tx_nhsharedce_categories_mm',
		)
	),
);


t3lib_div::loadTCA('tt_content');
t3lib_extMgm::addTCAcolumns('tt_content',$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('tt_content','tx_nhsharedce_categories;;;;1-1-1');

$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi2']='layout,select_key';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi2']='pi_flexform';
t3lib_extMgm::addPlugin(array('LLL:EXT:nh_shared_ce/locallang_db.xml:tt_content.list_type_pi2', $_EXTKEY.'_pi2'),'list_type');
t3lib_extMgm::addStaticFile($_EXTKEY,"pi2/static/","Shared CE client");
t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_pi2', 'FILE:EXT:nh_shared_ce/flexform_ds_pi2.xml');

if (TYPO3_MODE=="BE") {
	$TBE_MODULES_EXT["xMOD_db_new_content_el"]["addElClasses"]["tx_nhsharedce_pi2_wizicon"] = t3lib_extMgm::extPath($_EXTKEY).'pi2/class.tx_nhsharedce_pi2_wizicon.php';
	include_once(t3lib_extMgm::extPath($_EXTKEY).'class.tx_nhsharedce_flexform.php');
}
?>