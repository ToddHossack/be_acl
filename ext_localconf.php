<?php
if (!defined ("TYPO3_MODE")) 	die ("Access denied.");
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_beacl_acl=1
');



require_once(t3lib_extMgm::extPath('be_acl').'class.tx_beacl_objsel.php');
require_once(t3lib_extMgm::extPath('be_acl').'res/class.tx_beacl_userauthgroup.php');
$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_userauthgroup.php']['calcPerms'][] = 'tx_beacl_userAuthGroup->calcPerms';
$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_userauthgroup.php']['getPagePermsClause'][] = 'tx_beacl_userAuthGroup->getPagePermsClause';


$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Perm\\Controller\\PermissionModuleController'] = array(
	'className' => 'Tx_BeAcl_Xclass_PermissionModuleController',
);


$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'EXT:be_acl/class.tx_beacl_hooks.php:tx_beacl_hooks';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] = 'EXT:be_acl/class.tx_beacl_hooks.php:tx_beacl_hooks';

$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['tx_be_acl_timestamp'] = array(
	'frontend' => 'TYPO3\\CMS\\Core\\Cache\\Frontend\\StringFrontend',
	'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\SimpleFileBackend',
);

$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['tx_be_acl_permissions'] = array(
	'frontend' => 'TYPO3\\CMS\\Core\\Cache\\Frontend\\VariableFrontend',
	'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\SimpleFileBackend',
);
?>
