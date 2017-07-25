<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {
        $extKey = 'fbit_berecordlist';

        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['recordlist/Modules/Recordlist/index.php']['drawFooterHook'][] = \FBIT\BeRecordList\Hooks\RecordListDrawFooterHook::class . '->adjustWebListModule';
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['Backend\Template\Components\ButtonBar']['getButtonsHook'][] = \FBIT\BeRecordList\Hooks\ButtonBarGetButtonsHook::class . '->getButtons';
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/class.db_list_extra.inc']['getTable'][] = \FBIT\BeRecordList\Hooks\RecordListGetTableHook::class;
    }
);
