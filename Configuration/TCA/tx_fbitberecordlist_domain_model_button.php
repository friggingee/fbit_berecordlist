<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_button',
        'label' => 'identifier',
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
        'searchFields' => 'identifier,override_identifier,header_side',
        'iconfile' => 'EXT:fbit_berecordlist/Resources/Public/Icons/tx_fbitberecordlist_domain_model_button.gif',
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden, identifier, override_identifier, header_side',
    ],
    'types' => [
        '1' => ['showitem' => 'hidden, identifier, override_identifier, header_side'],
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
                        '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled',
                    ],
                ],
            ],
        ],

        'identifier' => [
            'exclude' => true,
            'label' => 'LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_button.identifier',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['-- Label --', 0],
                ],
                'itemsProcFunc' => \FBIT\BeRecordList\Utility\TcaUtility::class . '->getAvailableIconIdentifiers',
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
        'override_identifier' => [
            'exclude' => true,
            'label' => 'LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_button.override_identifier',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['-- Label --', 0],
                ],
                'itemsProcFunc' => \FBIT\BeRecordList\Utility\TcaUtility::class . '->getRegisteredIconIdentifiers',
                'size' => 1,
                'maxitems' => 1,
                'eval' => '',
                'fieldWizard' => [
                    'selectIcons' => [
                        'disabled' => false,
                    ],
                ],
            ],
        ],
        'header_side' => [
            'exclude' => true,
            'label' => 'LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_button.header_side',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['left', 0],
                    ['right', 1],
                ],
                'size' => 1,
                'maxitems' => 1,
                'eval' => '',
            ],
        ],

        'module' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'module2' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
    ],
];
