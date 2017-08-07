<?php

namespace FBIT\BeRecordList\Utility;

use FBIT\BeRecordList\Domain\Model\Button;
use FBIT\BeRecordList\Domain\Model\Module;
use FBIT\BeRecordList\Domain\Model\Table;
use FBIT\BeRecordList\Domain\Repository\ModuleRepository;
use TYPO3\CMS\Backend\Template\Components\Buttons\Action\HelpButton;
use TYPO3\CMS\Backend\Template\Components\Buttons\Action\ShortcutButton;
use TYPO3\CMS\Backend\Template\Components\Buttons\LinkButton;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Localization\Parser\XliffParser;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class ModuleUtility
{

    /**
     * @var array
     */
    static public $moduleLabels = [];

    /**
     * @var array
     */
    static public $allowedLabels = [];

    /**
     * @var array
     */
    static public $moduleConfig = [];

    static protected $defaultButtons = [
        'left' => [
            [
                'actions-document-new',
            ],
            [
                'actions-page-open',
            ],
            [
                'actions-document-export-csv',
                'actions-document-export-t3d',
            ],
            [
                'actions-search',
            ],
            [
                'actions-document-paste-into',
            ],
        ],
        'right' => [
            [
                'actions-system-cache-clear',
                'actions-refresh',
            ],
            [
                'actions-system-shortcut-new',
            ],
            [
                'actions-system-help-open',
            ],
        ],
    ];

    /**
     * @var string
     */
    static public $extensionName = '';

    static public function getConfiguredModules(): array
    {
        $configuredModules = $GLOBALS['TYPO3_CONF_VARS']['EXT']['fbit_berecordlist']['modules'];

        /** @var ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        /** @var ModuleRepository $moduleRepository */
        $moduleRepository = $objectManager->get(ModuleRepository::class);
        $modules = $moduleRepository->findAll();

        /** @var Module $module */
        foreach ($modules as $module) {
            $moduleTables = $module->getTables();
            $tables = [];
            /** @var Table $table */
            foreach ($moduleTables as $table) {
                if (!empty($table->getAllowedRecordTypes())) {
                    $tables[$table->getTablename()]['allowedRecordTypes'] = explode(',', $table->getAllowedRecordTypes());
                } else {
                    $tables[$table->getTablename()] = true;
                }
            }

            $buttonsLeft = explode(',', $module->getModulelayoutHeaderButtonsLeft());
            $buttonsLeftOverride = $module->getModulelayoutHeaderButtonsLeftOverride();
            foreach ($buttonsLeft as $buttonIndex => $buttonIdentifier) {
                /** @var Button $button */
                foreach ($buttonsLeftOverride as $button) {
                    if ($button->getIdentifier() === $buttonIdentifier) {
                        $buttonsLeft[$buttonIdentifier] = [
                            'icon' => $button->getOverrideIdentifier(),
                        ];
                    } else {
                        $buttonsLeft[$buttonIdentifier] = true;
                    }
                }
                unset($buttonsLeft[$buttonIndex]);
            }
            foreach (self::$defaultButtons['left'] as $groupIndex => $groupButtons) {
                foreach ($groupButtons as $buttonIndex => $buttonIdentifier) {
                    if (!array_key_exists($buttonIdentifier, $buttonsLeft)) {
                        $buttonsLeft[$buttonIdentifier] = false;
                    }
                }
            }

            $buttonsRight = explode(',', $module->getModulelayoutHeaderButtonsRight());
            $buttonsRightOverride = $module->getModulelayoutHeaderButtonsRightOverride();
            foreach ($buttonsRight as $buttonIndex => $buttonIdentifier) {
                /** @var Button $button */
                foreach ($buttonsRightOverride as $button) {
                    if ($button->getIdentifier() === $buttonIdentifier) {
                        $buttonsRight[$buttonIdentifier] = [
                            'icon' => $button->getOverrideIdentifier(),
                        ];
                    } else {
                        $buttonsRight[$buttonIdentifier] = true;
                    }
                }
                unset($buttonsRight[$buttonIndex]);
            }
            foreach (self::$defaultButtons['right'] as $groupIndex => $groupButtons) {
                foreach ($groupButtons as $buttonIndex => $buttonIdentifier) {
                    if (!array_key_exists($buttonIdentifier, $buttonsLeft)) {
                        $buttonsRight[$buttonIdentifier] = false;
                    }
                }
            }

            $sortedGroups = [
                'left' => array_filter(
                    array_map(
                        function ($buttonIdentifier, $buttonData) {
                            return (is_array($buttonData) ? [$buttonData['icon']] : ($buttonData ? [$buttonIdentifier] : null));
                        },
                        array_keys($buttonsLeft),
                        $buttonsLeft
                    )
                ),
                'right' => array_filter(
                    array_map(
                        function ($buttonIdentifier, $buttonData) {
                            return (is_array($buttonData) ? [$buttonData['icon']] : ($buttonData ? [$buttonIdentifier] : null));
                        },
                        array_keys($buttonsRight),
                        $buttonsRight
                    )
                ),
            ];

            $configuredModules[$module->getSignature()] = [
                'icon' => $module->getIcon(),
                'labels' => 'LLL:' . $module->getLabels(),
                'storagePid' => $module->getStoragePid(),
                'tables' => $tables,
                'mainModule' => $module->getMainModule(),
                'moduleLayout' => [
                    'moduleFromRecord' => true,
                    'header' => [
                        'enabled' => $module->getModulelayoutHeaderEnabled(),
                        'menu' => [
                            'showOneOptionPerTable' => $module->getModulelayoutHeaderMenuShowoneoptionpertable(),
                            'showOneOptionPerRecordType' => $module->getModulelayoutHeaderMenuShowoneoptionperrecordtype(),
                        ],
                        'pagepath' => $module->getModulelayoutHeaderPagepath(),
                        'buttons' => [
                            'enabled' => $module->getModulelayoutHeaderButtonsEnabled(),
                            'left' => $buttonsLeft,
                            'right' => $buttonsRight,
                            'sorting' => $sortedGroups,
                        ],
                        'showOneNewRecordButtonPerTable' => $module->getModulelayoutHeaderButtonsShowonenewrecordbuttonpertable(),
                        'showOneNewRecordButtonPerRecordType' => $module->getModulelayoutHeaderButtonsShowonenewrecordbuttonperrecordtype(),
                    ],
                    'footer' => [
                        'enabled' => $module->getModulelayoutFooterEnabled(),
                        'fieldselection' => $module->getModuleylayoutFooterFieldselection(),
                        'listoptions' => [
                            'extendedview' => $module->getModulelayoutFooterListoptionsExtendedview(),
                            'clipboard' => $module->getModulelayoutFooterListoptionsClipboard(),
                            'localization' => $module->getModulelayoutFooterListoptionsLocalization(),
                        ],
                    ],
                ],
            ];
        }

        return $configuredModules;
    }

    /**
     * @param string $extensionName
     * @throws \Exception
     */
    static public function loadModuleConfigurationForExtension(string $extensionName): void
    {
        if (empty($extensionName)) {
            throw new \Exception('No extension key provided.', 1500392363);
        }

        $configuredModules = self::getConfiguredModules();
        if (!isset($configuredModules[$extensionName])) {
            throw new \Exception('There seems to be no configuration available for EXT:' . $extensionName . '. Please refer to the README for more details.', 1500032366);
        }

        self::$extensionName = $extensionName;
        self::$moduleConfig = $configuredModules[$extensionName];
    }

    /**
     * @param $table
     * @return string
     */
    static public function getTableLabel(string $table): string
    {
        preg_match('/EXT:(.*?)\//', $GLOBALS['TCA'][$table]['ctrl']['title'], $matches);
        $label = '';

        if (!empty($matches[1])) {
            $label = LocalizationUtility::translate($GLOBALS['TCA'][$table]['ctrl']['title'], $matches[1]);
        } else {
            $label = LocalizationUtility::translate($GLOBALS['TCA'][$table]['ctrl']['title'], '');
        }

        return $label;
    }

    /**
     * @param $key
     * @return NULL|string
     */
    static public function getModuleLL(string $key): string
    {
        $ll = 'LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_mod_module.xlf:';

        return LocalizationUtility::translate(
            $ll . $key,
            'fbit_berecordlist'
        );
    }

    static public function translate(string $key, string $extension): string
    {
        $ll = self::$moduleConfig['labels'];
        return LocalizationUtility::translate($ll . ':' . $key, $extension);
    }

    /**
     * @param string $table
     * @return string
     */
    static public function getTableIcon(string $table): string
    {
        return $GLOBALS['TCA'][$table]['ctrl']['iconfile'];
    }

    /**
     * @param array $incomingDefValsArray
     * @param string $table
     * @return array
     */
    static public function getNewRecordDefVals(array $incomingDefValsArray, string $table): array
    {
        $defValsArray = [];

        foreach ($incomingDefValsArray as $tablename => $fieldVals) {
            if ($tablename === $table) {
                foreach ($fieldVals as $fieldname => $value) {
                    $defValsArray['defVals[' . $tablename . '][' . $fieldname . ']'] = $value;
                }
            }
        }

        return $defValsArray;
    }

    /**
     * @param string $layoutFeaturePath
     * @return bool
     */
    static public function isLayoutFeatureEnabled(string $layoutFeaturePath): bool
    {
        $featurePathParts = explode('.', $layoutFeaturePath);

        $featureSettingValue = self::$moduleConfig['moduleLayout'][$featurePathParts[0]];

        if (is_array($featureSettingValue)) {
            for ($i = 1; $i < count($featurePathParts); $i++) {
                $featureSettingValue = $featureSettingValue[$featurePathParts[$i]];
            }
        }

        $featureEnabled = (bool)$featureSettingValue;

        return $featureEnabled;
    }

    static public function buildAllowedLabels(): void
    {
        self::buildMenuSelectLabels();
        self::buildAddNewRecordButtonLabels();
        self::buildPossibleModuleHeadlines();

        sort(self::$allowedLabels);
    }

    static public function buildPossibleModuleHeadlines(): void
    {
        self::$allowedLabels[] = 'backend.module.headline';

        $moduleHeadlineLabels = [];

        foreach (self::$moduleConfig['tables'] as $tableName => $tableConfiguration) {
            if (
                ModuleUtility::isLayoutFeatureEnabled('header.showOneNewRecordButtonPerRecordType')
                && is_array($tableConfiguration)
                && is_array($tableConfiguration['allowedRecordTypes'])
            ) {
                foreach ($tableConfiguration['allowedRecordTypes'] as $allowedRecordType) {
                    $moduleHeadlineLabels[] = $tableName . '.type.' . $allowedRecordType;
                }
            } else {
                $moduleHeadlineLabels[] = $tableName;
            }
        }

        self::$allowedLabels = array_merge(
            array_map(
                function ($label) {
                    return 'backend.module.headline.' . $label;
                },
                $moduleHeadlineLabels
            ),
            self::$allowedLabels
        );
    }

    static public function buildAddNewRecordButtonLabels(): void
    {
        self::$allowedLabels[] = 'backend.module.actions.new';

        $newRecordButtonLabels = [];
        $newRecordButtonLabels[] = 'prefix';
        $newRecordButtonLabels[] = 'suffix';

        if (
            ModuleUtility::isLayoutFeatureEnabled('header.showOneNewRecordButtonPerTable')
            || ModuleUtility::isLayoutFeatureEnabled('header.showOneNewRecordButtonPerRecordType')
        ) {
            foreach (self::$moduleConfig['tables'] as $tableName => $tableConfiguration) {
                if (
                    ModuleUtility::isLayoutFeatureEnabled('header.showOneNewRecordButtonPerRecordType')
                    && is_array($tableConfiguration)
                    && is_array($tableConfiguration['allowedRecordTypes'])
                ) {
                    foreach ($tableConfiguration['allowedRecordTypes'] as $allowedRecordType) {
                        $newRecordButtonLabels[] = $tableName . '.type.' . $allowedRecordType;
                    }
                } else {
                    $newRecordButtonLabels[] = $tableName;
                }
            }
        }

        self::$allowedLabels = array_merge(
            array_map(
                function ($label) {
                    return 'backend.module.actions.new.' . $label;
                },
                $newRecordButtonLabels
            ),
            self::$allowedLabels
        );
    }

    static public function buildMenuSelectLabels(): void
    {
        $menuSelectLabels = [];
        $menuSelectLabels[] = 'prefix';
        $menuSelectLabels[] = 'suffix';

        foreach (self::$moduleConfig['tables'] as $tableName => $tableConfiguration) {
            $menuSelectLabels[] = $tableName;

            if (
                ModuleUtility::isLayoutFeatureEnabled('header.menu.showOneOptionPerRecordType')
                && is_array($tableConfiguration)
                && is_array($tableConfiguration['allowedRecordTypes'])
            ) {
                foreach ($tableConfiguration['allowedRecordTypes'] as $allowedRecordType) {
                    $menuSelectLabels[] = $tableName . '.type.' . $allowedRecordType;
                }
            }
        }

        self::$allowedLabels = array_merge(
            array_map(
                function ($label) {
                    return 'backend.module.select.' . $label;
                },
                $menuSelectLabels
            ),
            self::$allowedLabels
        );
    }

    static public function loadModuleLabels(): void
    {
        $llFile = self::$moduleConfig['labels'];
        $llFile = GeneralUtility::getFileAbsFileName(str_replace('LLL:', '', $llFile));
        /** @var XliffParser $xliffParser */
        $xliffParser = GeneralUtility::makeInstance(XliffParser::class);
        self::$moduleLabels = array_keys($xliffParser->getParsedData($llFile, $GLOBALS['LANG']->lang)[$GLOBALS['LANG']->lang]);
    }

    static public function moduleLabelExists($key): bool
    {
        return (in_array($key, self::$moduleLabels) && in_array($key, self::$allowedLabels));
    }

    static public function isButtonAvailable($buttonIdentifier, &$buttons): bool
    {
        $buttonAvailable = false;

        foreach ($buttons as $headerSideButtons) {
            foreach ($headerSideButtons as $groupButtons) {
                foreach ($groupButtons as $buttonObject) {

                    switch (get_class($buttonObject)) {
                        case LinkButton::class:
                            /** @var LinkButton $buttonObject */
                            $buttonObjectIdentifier = $buttonObject->getIcon()->getIdentifier();
                            break;
                        case ShortcutButton::class:
                            $buttonObjectIdentifier = 'shortcut';
                            break;
                        case HelpButton::class:
                            $buttonObjectIdentifier = 'csh';
                            break;
                        default:
                            $buttonObjectIdentifier = '';
                            break;
                    }
                    if ($buttonIdentifier === $buttonObjectIdentifier) {
                        $buttonAvailable = true;
                        break 3;
                    }
                }
            }
        }

        return $buttonAvailable;
    }
}