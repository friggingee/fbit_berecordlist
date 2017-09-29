<?php

namespace FBIT\BeRecordList\Utility;

use TYPO3\CMS\Backend\Template\Components\Buttons\Action\HelpButton;
use TYPO3\CMS\Backend\Template\Components\Buttons\Action\ShortcutButton;
use TYPO3\CMS\Backend\Template\Components\Buttons\LinkButton;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Localization\Parser\XliffParser;
use TYPO3\CMS\Core\Utility\GeneralUtility;
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

    /**
     * @var string
     */
    static public $extensionName = '';

    /**
     * @var array
     */
    static public $extensionNameMap = [];

    /**
     * @param string $extensionName
     * @throws \Exception
     */
    static public function loadModuleConfigurationForExtension(string $extensionName): void
    {
        if (empty($extensionName)) {
            throw new \Exception('No extension key provided.', 1500392363);
        }

        if (!is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['fbit_berecordlist']['modules'][$extensionName])) {
            throw new \Exception('There seems to be no configuration available for EXT:' . $extensionName . '. Please refer to the README for more details.', 1500032366);
        }

        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['fbit_berecordlist']['modules'][$extensionName]['originalExtensionName'])) {
            $originalExtensionName = $GLOBALS['TYPO3_CONF_VARS']['EXT']['fbit_berecordlist']['modules'][$extensionName]['originalExtensionName'];
            self::$extensionNameMap[$extensionName] = $originalExtensionName;
        }

        self::$extensionName = $extensionName;
        self::$moduleConfig = $GLOBALS['TYPO3_CONF_VARS']['EXT']['fbit_berecordlist']['modules'][$extensionName];
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

    /**
     * @param string $key
     * @param string $extension
     * @return string
     */
    static public function translate(string $key, string $extension): string
    {
        $ll = self::$moduleConfig['labels'];

        if (isset(self::$extensionNameMap[$extension])) {
            $extension = self::$extensionNameMap[$extension];
        }
      
        $translated = LocalizationUtility::translate($ll . ':' . $key, $extension);
        // translated can be null
        return ($translated !== null)?$translated:$key;
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

    /**
     * @param string $path
     * @return mixed
     */
    static public function getModuleSettingByPath(string $path)
    {
        $pathParts = explode('.', $path);

        $settingValue = self::$moduleConfig[$pathParts[0]];

        if (is_array($settingValue)) {
            for ($i = 1; $i < count($pathParts); $i++) {
                $settingValue = $settingValue[$pathParts[$i]];
            }
        }

        return $settingValue;
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