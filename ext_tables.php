<?php
defined('TYPO3_MODE') || die('Access denied.');

if (TYPO3_MODE === 'BE') {
    if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['fbit_berecordlist']['modules'])) {
        if (TYPO3_MODE === 'BE') {
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
        }

        $modulesConfiguration = $GLOBALS['TYPO3_CONF_VARS']['EXT']['fbit_berecordlist']['modules'];

        foreach ($modulesConfiguration as $extKey => $moduleConfiguration) {
            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
                'FBIT.BeRecordList',
                'fbit',
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