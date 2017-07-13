<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function () {
        if (TYPO3_MODE === 'BE') {
            if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['sbb_berecordlist']['modules'])) {
                $modulesConfiguration = $GLOBALS['TYPO3_CONF_VARS']['EXT']['sbb_berecordlist']['modules'];

                foreach ($modulesConfiguration as $extKey => $moduleConfiguration) {
                    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
                        'SBB.SbbBeRecordList',
                        'sbb',
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
