# BeRecordList

This extension provides a "generator" for backend modules which are intended to list one or more types of records.

## Configuration

Initially, the extension does not do anything besides adding an empty main module entry in the module list.

To create a new "record list" you need to add the following configuration to your extension's `ext_tables.php`:

```
$GLOBALS['TYPO3_CONF_VARS']['EXT']['fbit_berecordlist']['modules']['YOUR_EXTKEY'] = [
    'icon' => 'EXT:path/to/your/module/icon.png',
    'labels' => 'EXT:path/to/your/local/lang.xlf',
    'storagePid' => 'The uid of the page your records are stored on (use a real int here)',
    'tables' => [
        'sql_table_name1' => true,
        'sql_table_name2' => true
    ]
];
```

For each EXTKEY you add to `$GLOBALS['TYPO3_CONF_VARS']['EXT']['fbit_berecordlist']['modules']` you will get a new submodule inside the main module created by this extension.

## Contributing

Please open issues and pull requests [here](https://github.com/friggingee/fbit-berecordlist).