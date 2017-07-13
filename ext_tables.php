<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function () {
        if (TYPO3_MODE === 'BE') {
            if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['fbit_berecordlist']['modules'])) {
                $modulesConfiguration = $GLOBALS['TYPO3_CONF_VARS']['EXT']['fbit_berecordlist']['modules'];

                foreach ($modulesConfiguration as $extKey => $moduleConfiguration) {
                    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
                        'FBIT.BeRecordList',
                        'web',
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
    }
);
