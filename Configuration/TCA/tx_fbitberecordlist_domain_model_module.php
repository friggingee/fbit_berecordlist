<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module',
        'label' => 'signature',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'rootLevel' => 1,
        'sortby' => 'sorting',
        'versioningWS' => true,
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'signature,icon,labels,storage_pid,main_module,modulelayout_header_enabled,modulelayout_header_menu_showoneoptionpertable,modulelayout_header_menu_showoneoptionperrecordtype,modulelayout_header_pagepath,modulelayout_header_buttons_enabled,modulelayout_header_buttons_showonenewrecordbuttonpertable,modulelayout_header_buttons_showonenewrecordbuttonperrecordtype,modulelayout_footer_enabled,moduleylayout_footer_fieldselection,modulelayout_footer_listoptions_extendedview,modulelayout_footer_listoptions_clipboard,modulelayout_footer_listoptions_localization,tables,modulelayout_header_buttons_left,modulelayout_header_buttons_right,modulelayout_header_buttons_left_override,modulelayout_header_buttons_right_override',
        'iconfile' => 'EXT:fbit_berecordlist/Resources/Public/Icons/tx_fbitberecordlist_domain_model_module.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden, signature, icon, labels, storage_pid, main_module, modulelayout_header_enabled, modulelayout_header_menu_showoneoptionpertable, modulelayout_header_menu_showoneoptionperrecordtype, modulelayout_header_pagepath, modulelayout_header_buttons_enabled, modulelayout_header_buttons_showonenewrecordbuttonpertable, modulelayout_header_buttons_showonenewrecordbuttonperrecordtype, modulelayout_footer_enabled, moduleylayout_footer_fieldselection, modulelayout_footer_listoptions_extendedview, modulelayout_footer_listoptions_clipboard, modulelayout_footer_listoptions_localization, tables, modulelayout_header_buttons_left, modulelayout_header_buttons_right, modulelayout_header_buttons_left_override, modulelayout_header_buttons_right_override',
    ],
    'types' => [
        '1' => ['showitem' => 'hidden, signature, icon, labels, storage_pid, main_module, --div--;LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module.modulelayout_header, modulelayout_header_enabled, modulelayout_header_menu_showoneoptionpertable, modulelayout_header_menu_showoneoptionperrecordtype, modulelayout_header_pagepath, --div--;LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module.modulelayout_header_buttons, modulelayout_header_buttons_enabled, modulelayout_header_buttons_showonenewrecordbuttonpertable, modulelayout_header_buttons_showonenewrecordbuttonperrecordtype,  modulelayout_header_buttons_left, modulelayout_header_buttons_left_override, modulelayout_header_buttons_right, modulelayout_header_buttons_right_override, --div--;LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module.modulelayout_footer, modulelayout_footer_enabled, moduleylayout_footer_fieldselection, modulelayout_footer_listoptions_extendedview, modulelayout_footer_listoptions_clipboard, modulelayout_footer_listoptions_localization, --div--;LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module.relations, tables'],
    ],
    'columns' => [
        't3ver_label' => [
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
                    ]
                ],
            ],
        ],

        'signature' => [
            'exclude' => true,
            'label' => 'LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module.signature',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required,lower'
            ],
        ],
        'icon' => [
            'exclude' => true,
            'label' => 'LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module.icon',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['-- Label --', 0],
                ],
                'itemsProcFunc' => \FBIT\BeRecordList\Utility\TcaUtility::class . '->getRegisteredIconIdentifiers',
                'size' => 1,
                'maxitems' => 1,
                'eval' => 'required',
                'fieldWizard' => [
                    'selectIcons' => [
                        'disabled' => false,
                    ],
                ],
            ],
        ],
        'labels' => [
            'exclude' => true,
            'label' => 'LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module.labels',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['-- Label --', 0],
                ],
                'itemsProcFunc' => \FBIT\BeRecordList\Utility\TcaUtility::class . '->getAvailableLocallangFiles',
                'size' => 1,
                'maxitems' => 1,
                'eval' => 'required',
            ],
        ],
        'storage_pid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module.storage_pid',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int,required',
                'range' => [
                    'lower' => 1,
                ],
            ]
        ],
        'main_module' => [
            'exclude' => true,
            'label' => 'LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module.main_module',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['-- Label --', 0],
                ],
                'itemsProcFunc' => \FBIT\BeRecordList\Utility\TcaUtility::class . '->getAvailableModules',
                'size' => 1,
                'maxitems' => 1,
                'eval' => 'required',
            ],
        ],
        'modulelayout_header_enabled' => [
            'exclude' => true,
            'label' => 'LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module.modulelayout_header_enabled',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
                    ]
                ],
                'default' => 1,
            ]
        ],
        'modulelayout_header_menu_showoneoptionpertable' => [
            'exclude' => true,
            'label' => 'LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module.modulelayout_header_menu_showoneoptionpertable',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
                    ]
                ],
                'default' => 0,
            ]
        ],
        'modulelayout_header_menu_showoneoptionperrecordtype' => [
            'exclude' => true,
            'label' => 'LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module.modulelayout_header_menu_showoneoptionperrecordtype',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
                    ]
                ],
                'default' => 0,
            ]
        ],
        'modulelayout_header_pagepath' => [
            'exclude' => true,
            'label' => 'LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module.modulelayout_header_pagepath',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
                    ]
                ],
                'default' => 1,
            ]
        ],
        'modulelayout_header_buttons_enabled' => [
            'exclude' => true,
            'label' => 'LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module.modulelayout_header_buttons_enabled',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
                    ]
                ],
                'default' => 1,
            ]
        ],
        'modulelayout_header_buttons_showonenewrecordbuttonpertable' => [
            'exclude' => true,
            'label' => 'LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module.modulelayout_header_buttons_showonenewrecordbuttonpertable',
            'onChange' => 'reload',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
                    ]
                ],
                'default' => 0,
            ]
        ],
        'modulelayout_header_buttons_showonenewrecordbuttonperrecordtype' => [
            'exclude' => true,
            'label' => 'LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module.modulelayout_header_buttons_showonenewrecordbuttonperrecordtype',
            'onChange' => 'reload',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
                    ]
                ],
                'default' => 0,
            ]
        ],
        'modulelayout_footer_enabled' => [
            'exclude' => true,
            'label' => 'LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module.modulelayout_footer_enabled',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
                    ]
                ],
                'default' => 1,
            ]
        ],
        'moduleylayout_footer_fieldselection' => [
            'exclude' => true,
            'label' => 'LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module.moduleylayout_footer_fieldselection',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
                    ]
                ],
                'default' => 1,
            ]
        ],
        'modulelayout_footer_listoptions_extendedview' => [
            'exclude' => true,
            'label' => 'LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module.modulelayout_footer_listoptions_extendedview',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
                    ]
                ],
                'default' => 1,
            ]
        ],
        'modulelayout_footer_listoptions_clipboard' => [
            'exclude' => true,
            'label' => 'LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module.modulelayout_footer_listoptions_clipboard',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
                    ]
                ],
                'default' => 1,
            ]
        ],
        'modulelayout_footer_listoptions_localization' => [
            'exclude' => true,
            'label' => 'LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module.modulelayout_footer_listoptions_localization',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
                    ]
                ],
                'default' => 1,
            ]
        ],
        'tables' => [
            'exclude' => true,
            'label' => 'LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module.tables',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_fbitberecordlist_domain_model_table',
                'foreign_field' => 'module',
                'foreign_sortby' => 'sorting',
                'maxitems' => 9999,
                'appearance' => [
                    'collapseAll' => 0,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'useSortable' => 1,
                    'showAllLocalizationLink' => 1
                ],
            ],

        ],
        'modulelayout_header_buttons_left' => [
            'exclude' => true,
            'label' => 'LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module.modulelayout_header_buttons_left',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'items' => [
                    ['LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module.modulelayout_header_buttons_left.actions-document-new', 'actions-document-new'],
                    ['LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module.modulelayout_header_buttons_left.actions-page-open', 'actions-page-open'],
                    ['LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module.modulelayout_header_buttons_left.actions-document-export-csv', 'actions-document-export-csv'],
                    ['LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module.modulelayout_header_buttons_left.actions-document-export-t3d', 'actions-document-export-t3d'],
                    ['LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module.modulelayout_header_buttons_left.actions-search', 'actions-search'],
                    ['LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module.modulelayout_header_buttons_left.actions-document-paste-into', 'actions-document-paste-into'],
                ],
                'itemsProcFunc' => \FBIT\BeRecordList\Utility\TcaUtility::class . '->getHeaderButtonsLeft',
                'minitems' => 0,
                'maxitems' => '15',
            ],
        ],
        'modulelayout_header_buttons_right' => [
            'exclude' => true,
            'label' => 'LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module.modulelayout_header_buttons_right',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'items' => [
                    ['LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module.modulelayout_header_buttons_right.actions-system-cache-clear', 'actions-system-cache-clear'],
                    ['LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module.modulelayout_header_buttons_right.actions-refresh', 'actions-refresh'],
                    ['LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module.modulelayout_header_buttons_right.actions-system-shortcut-new', 'actions-system-shortcut-new'],
                    ['LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module.modulelayout_header_buttons_right.actions-system-help-open', 'actions-system-help-open'],
                ],
                'minitems' => 0,
                'maxitems' => '15',
            ],
        ],
        'modulelayout_header_buttons_left_override' => [
            'exclude' => true,
            'label' => 'LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module.modulelayout_header_buttons_left_override',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_fbitberecordlist_domain_model_button',
                'foreign_field' => 'module',
                'foreign_sortby' => 'sorting',
                'maxitems' => 9999,
                'appearance' => [
                    'collapseAll' => 1,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'useSortable' => 1,
                    'showAllLocalizationLink' => 1
                ],
            ],
        ],
        'modulelayout_header_buttons_right_override' => [
            'exclude' => true,
            'label' => 'LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_module.modulelayout_header_buttons_right_override',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_fbitberecordlist_domain_model_button',
                'foreign_field' => 'module2',
                'foreign_sortby' => 'sorting',
                'maxitems' => 9999,
                'appearance' => [
                    'collapseAll' => 1,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'useSortable' => 1,
                    'showAllLocalizationLink' => 1
                ],
            ],
        ],
    ],
];
