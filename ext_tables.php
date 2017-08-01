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

        $extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['fbit_berecordlist']);

        if ((bool)$extConf['enableConfiguratorModule']) {
            $GLOBALS['TYPO3_CONF_VARS']['EXT']['fbit_berecordlist']['modules']['fbit_berecordlist'] = [
                'icon' => 'EXT:fbit_berecordlist/Resources/Public/Icons/ext_icon.svg',
                'labels' => 'LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_mod_modulemanager.xlf',
                'mainModule' => 'tools',
                'storagePid' => 0,
                'tables' => [
                    'tx_fbitberecordlist_domain_model_module' => true,
                ],
                'moduleLayout' => [
                    'header' => [
                        'enabled' => true,
                        'buttons' => [
                            'enabled' => true,
                            'left' => [
                                'actions-document-view' => false,
                            ],
                            'right' => [
                            ],
                        ],
                    ],
                    'footer' => [
                        'enabled' => false,
                    ],
                ],
            ];
        }

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('fbitberecordlist', 'Configuration/TypoScript', 'Backend RecordList Manager');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_fbitberecordlist_domain_model_module', 'EXT:fbit_berecordlist/Resources/Private/Language/locallang_csh_tx_fbitberecordlist_domain_model_module.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_fbitberecordlist_domain_model_module');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_fbitberecordlist_domain_model_table', 'EXT:fbit_berecordlist/Resources/Private/Language/locallang_csh_tx_fbitberecordlist_domain_model_table.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_fbitberecordlist_domain_model_table');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_fbitberecordlist_domain_model_button', 'EXT:fbit_berecordlist/Resources/Private/Language/locallang_csh_tx_fbitberecordlist_domain_model_button.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_fbitberecordlist_domain_model_button');

        // By default we're only showing one table at a time, also we restrict the record types available per module.
        // This means we don't want users to be able to - accidentially or intentionally - switch to another table other
        // than by using the dropdown menu.
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
[globalString = GP:extension = /.+/]
    mod.web_list.disableSingleTableView = 1
    mod.web_list.itemsLimitSingleTable = 20
[END]
        ');

        $modulesConfiguration = \FBIT\BeRecordList\Utility\ModuleUtility::getConfiguredModules();

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