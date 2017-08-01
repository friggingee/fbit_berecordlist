<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_table',
        'label' => 'tablename',
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
        'searchFields' => 'tablename,allowed_record_types',
        'iconfile' => 'EXT:fbit_berecordlist/Resources/Public/Icons/tx_fbitberecordlist_domain_model_table.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden, tablename, allowed_record_types',
    ],
    'types' => [
        '1' => ['showitem' => 'hidden, tablename, allowed_record_types'],
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

        'tablename' => [
            'exclude' => true,
            'label' => 'LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_table.tablename',
            'onChange' => 'reload',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['-- Label --', -1],
                ],
                'itemsProcFunc' => \FBIT\BeRecordList\Utility\TcaUtility::class . '->getTables',
                'size' => 1,
                'maxitems' => 1,
                'eval' => 'required'
            ],
        ],
        'allowed_record_types' => [
            'exclude' => true,
            'label' => 'LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_db.xlf:tx_fbitberecordlist_domain_model_table.allowed_record_types',
            'displayCond' => 'FIELD:tablename:REQ:true',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'itemsProcFunc' => \FBIT\BeRecordList\Utility\TcaUtility::class . '->getRecordTypes',
                'size' => 5,
                'maxitems' => 10,
                'eval' => ''
            ],
        ],
    
        'module' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
    ],
];
