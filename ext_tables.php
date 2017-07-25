<?php
defined('TYPO3_MODE') || die('Access denied.');

if (TYPO3_MODE === 'BE') {
    if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['fbit_berecordlist']['modules'])) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModule(
            'fbit',
            '',
            '',
            '',
            [
                'access' => 'group,user',
                'labels' => 'LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_mod_module.xlf',
                'icon' => '',
                'name' => null,
            ]
        );

        // By default we're only showing one table at a time, also we restrict the record types available per module.
        // This means we don't want users to be able to - accidentially or intentionally - switch to another table other
        // than by using the dropdown menu.
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
[globalString = GP:extension = /.+/]
    mod.web_list.disableSingleTableView = 1
    mod.web_list.itemsLimitSingleTable = 20
[END]
        ');

        $modulesConfiguration = $GLOBALS['TYPO3_CONF_VARS']['EXT']['fbit_berecordlist']['modules'];

        foreach ($modulesConfiguration as $extKey => $moduleConfiguration) {
            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
                'FBIT.BeRecordList',
                (isset($moduleConfiguration['mainModule']) ? $moduleConfiguration['mainModule'] : 'fbit'),
                $extKey,
                '',
                [
                    'Module' => 'list',
                ],
                [
                    'access' => 'user,group',
                    'icon' => $moduleConfiguration['icon'],
                    'labels' => $moduleConfiguration['labels'],
                ]
            );
        }
    }
}