<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {
        $extKey = 'fbit_berecordlist';

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript(
            'Backend RecordList Manager',
            'setup',
            '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:fbit_berecordlist/Configuration/TypoScript/setup.ts">'
        );

        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['recordlist/Modules/Recordlist/index.php']['drawFooterHook'][$extKey] = \FBIT\BeRecordList\Hooks\RecordListDrawFooterHook::class . '->adjustWebListModule';
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['Backend\Template\Components\ButtonBar']['getButtonsHook'][$extKey] = \FBIT\BeRecordList\Hooks\ButtonBarGetButtonsHook::class . '->getButtons';
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/class.db_list_extra.inc']['getTable'][$extKey] = \FBIT\BeRecordList\Hooks\RecordListGetTableHook::class;
    }
);
