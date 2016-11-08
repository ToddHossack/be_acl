<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages("tx_beacl_acl");

//\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('be_acl','EXT:be_acl/Configuration/Typoscript/setup.txt','BeAcl setup');

$TCA["tx_beacl_acl"] = array(
	"ctrl" => Array(
		"title" => "LLL:EXT:be_acl/locallang_db.xml:tx_beacl_acl",
		"label" => "uid",
		"tstamp" => "tstamp",
		"crdate" => "crdate",
		"cruser_id" => "cruser_id",
		"type" => "type",
		"default_sortby" => "ORDER BY type",
		"dynamicConfigFile" => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . "tca.php",
		"iconfile" => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . "icon_tx_beacl_acl.gif",
	),
	"feInterface" => array(
		"fe_admin_fieldList" => "type, object_id, permissions, recursive",
	)
);
