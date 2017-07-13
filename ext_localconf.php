<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {
        $extKey = 'fbit_berecordlist';

        //add default module config
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript($extKey, 'setup', '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . $extKey . '/Configuration/TypoScript/setup.ts">');

        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['recordlist/Modules/Recordlist/index.php']['drawFooterHook'][] = 'FBIT\\BeRecordList\\Hooks\\RecordListDrawFooterHook->getDocHeaderMenu';
    }
);
