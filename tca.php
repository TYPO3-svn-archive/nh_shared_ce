<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA["tx_nhsharedce_categories"] = array (
	"ctrl" => $TCA["tx_nhsharedce_categories"]["ctrl"],
	"interface" => array (
		"showRecordFieldList" => "sys_language_uid,l18n_parent,l18n_diffsource,hidden,title"
	),
	"feInterface" => $TCA["tx_nhsharedce_categories"]["feInterface"],
	"columns" => array (
		'sys_language_uid' => array (
			'exclude' => 1,
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => array (
				'type'                => 'select',
				'foreign_table'       => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
				)
			)
		),
		'l18n_parent' => array (
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude'     => 1,
			'label'       => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config'      => array (
				'type'  => 'select',
				'items' => array (
					array('', 0),
				),
				'foreign_table'       => 'tx_nhsharedce_categories',
				'foreign_table_where' => 'AND tx_nhsharedce_categories.pid=###CURRENT_PID### AND tx_nhsharedce_categories.sys_language_uid IN (-1,0)',
			)
		),
		'l18n_diffsource' => array (
			'config' => array (
				'type' => 'passthrough'
			)
		),
		'hidden' => array (
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		"title" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:nh_shared_ce/locallang_db.xml:tx_nhsharedce_categories.title",
			"config" => Array (
				"type" => "input",
				"size" => "30",
			)
		),
	),
	"types" => array (
		"0" => array("showitem" => "sys_language_uid;;;;1-1-1, l18n_parent, l18n_diffsource, hidden;;1, title;;;;2-2-2")
	),
	"palettes" => array (
		"1" => array("showitem" => "")
	)
);
?>