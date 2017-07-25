# BeRecordList

This extension provides a "generator" for backend modules which are intended to list one or more types of records.

## Configuration

Initially, the extension does not do anything besides adding an empty main module entry in the module list.

To create a new "record list" you need to add the following configuration to your extension's `ext_tables.php`:

```
$GLOBALS['TYPO3_CONF_VARS']['EXT']['fbit_berecordlist']['modules']['YOUR_EXTKEY'] = [
    'icon' => 'EXT:your_extkey/Resources/Public/Icons/your_module_icon.svg',
    'labels' => 'LLL:EXT:your_extkey/Resources/Private/Language/locallang_mod_yourmodll.xlf',
    'storagePid' => (provide a single page uid as an integer here),
    'tables' => [
        'tx_yourext_domain_model_name' => true,
        'tx_yourext_domain_model_name2' => [
            'allowedRecordTypes' => [
                (integer value of allowed record type, see the table's TCA type column configuration and items)
            ]
        ]
    ],
    // leave empty to use the default "fbit" main module
    'mainModule' => 'your_main_module_key',
    'moduleLayout' => [
        'header' => [
            'enabled' => true,
            'menu' => [
                'showOneOptionPerTable' => true,
                'showOneOptionPerRecordType' => true,
            ],
            'pagepath' => false,
            'buttons' => [
                'enabled' => true,
                'left' => [
                    'actions-document-new' => false,
                    'actions-page-open' => false,
                    'actions-document-export-csv' => false,
                    'actions-document-export-t3d' => false,
                    // override icon using another registered icon identifier
                    'actions-search' => [
                        'icon' => 'actions-filter'
                    ],
                ],
                'right' => [
                    'actions-system-cache-clear' => false,
                    'actions-refresh' => false,
                    // the following two can not have their icon changed
                    'shortcut' => false,
                    'csh' => false,
                ],
                // You MUST provide every button that will be in the buttons bar after all operations,
                // including the generation of new buttons based on table or record type and the removal
                // of buttons based on the settings above.
                //
                // If the deep count of button identifiers in the sorting array is not exactly equal to the
                // deep count of available buttons, no sorting will be done.
                //
                // Be aware that the sorting also has to include dynamically generated buttons.
                // The core provides the button "actions-document-paste-into" if a record is copied or cut,
                // so make sure to ínclude this identifier wherever you like to have the button.
                'sorting' => [
                    'left' => [
                        [
                            'actions-filter'
                        ],
                        [
                            'icon identifier or icon identifier of table or table type',
                        ],
                    ],
                ]
            ],
            // If true, creates an identifier composed of "tcarecords-[tablename]-default", e.g. tcarecords-tx_ext_domain_model_singletyperecord-default.
            'showOneNewRecordButtonPerTable' => true,
            // If true, creates buttons with the identifier taken from the type item definition in the table's TCA:
            // $GLOBALS['TCA'][tableName]['columns'][$GLOBALS['TCA'][tableName]['typeicon_column']]['config']['items'][itemIndex][2]
            // or if no types are available ($GLOBALS['TCA'][tableName]['typeicon_column'] is empty or not set),
            // creates an identifier composed of "tcarecords-[tablename]-default", e.g. tcarecords-tx_ext_domain_model_singletyperecord-default.
            'showOneNewRecordButtonPerRecordType' => true,
        ],
        'footer' => [
            'enabled' => false,
            // show the list of possible columns to display in the table?
            'fieldselection' => true,
            // show the checkboxes at the bottom?
            'listoptions' => [
                'extendedview' => true,
                'clipboard' => true,
                'localization' => true,
            ],
        ],
    ],
];
```

For each EXTKEY you add to `$GLOBALS['TYPO3_CONF_VARS']['EXT']['fbit_berecordlist']['modules']` you will get a new submodule inside the main module created by this extension.

## Contributing

Please open issues and pull requests [here](https://github.com/friggingee/fbit-berecordlist).