<?php

namespace FBIT\BeRecordList\Utility;

use FBIT\BeRecordList\Domain\Model\Module;
use FBIT\BeRecordList\Domain\Model\Table;
use FBIT\BeRecordList\Domain\Repository\ModuleRepository;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Lang\LanguageService;

class TcaUtility {
    protected $defaultIcons = [
        'actions-document-new',
        'actions-page-open',
        'actions-document-export-csv',
        'actions-document-export-t3d',
        'actions-search',
        'actions-system-cache-clear',
        'actions-refresh',
        'actions-system-shortcut-new',
        'actions-system-help-open',
        'actions-document-paste-into',
    ];

    public function getTables($parameters) {
        $languageService = $this->getLanguageService();
        /** @var IconFactory $iconFactory */
        $iconFactory = GeneralUtility::makeInstance(IconFactory::class);

        foreach ($GLOBALS['TCA'] as $currentTable => $_) {
            if (!empty($GLOBALS['TCA'][$currentTable]['ctrl']['adminOnly'])) {
                // Hide "admin only" tables
                continue;
            }
            $label = !empty($GLOBALS['TCA'][$currentTable]['ctrl']['title']) ? $GLOBALS['TCA'][$currentTable]['ctrl']['title'] : '';
            $icon = $iconFactory->mapRecordTypeToIconIdentifier($currentTable, []);
            $helpText = [];
            $languageService->loadSingleTableDescription($currentTable);

            $extensionKeyMatches = [];
            preg_match('/EXT:(.*?)\//', $label, $extensionKeyMatches);
            $tableLabel = LocalizationUtility::translate($label, $extensionKeyMatches[1]);
            $itemLabel = $currentTable . (!empty($tableLabel) ? ' (' . $tableLabel . ')' : '');

            $parameters['items'][] = [$itemLabel, $currentTable, $icon, $helpText];
        }

        sort($parameters['items']);
    }

    public function getRecordTypes($parameters) {
        /** @var IconFactory $iconFactory */
        $iconFactory = GeneralUtility::makeInstance(IconFactory::class);

        $currentTable = $parameters['row']['tablename'][0];
        $typeIconColumn = $GLOBALS['TCA'][$currentTable]['ctrl']['typeicon_column'];

        if (!empty($typeIconColumn)) {
            $typeItems = $GLOBALS['TCA'][$currentTable]['columns'][$typeIconColumn]['config']['items'];

            foreach ($typeItems as $typeIndex => $typeConfig) {
                $label = $typeConfig[0];

                $extensionKeyMatches = [];
                preg_match('/EXT:(.*?)\//', $label, $extensionKeyMatches);
                $typeLabel = LocalizationUtility::translate($label, $extensionKeyMatches[1]);

                $icon = $iconFactory->getIcon($typeConfig[2], Icon::SIZE_SMALL)->getIdentifier();
                $helpText = [];

                $itemLabel = $typeLabel . ' (' . $typeConfig[1] . ')';

                $parameters['items'][] = [$itemLabel, $typeConfig[1], $icon, $helpText];
            }
        }

        usort($parameters['items'], function($a, $b) {
            return $a[1] > $b[1];
        });
    }

    public function getAvailableIconIdentifiers($parameters) {
        $iconIdentifiers = $this->defaultIcons;

        foreach ($iconIdentifiers as $iconIndex => $iconIdentifier) {
            $label = str_replace('-', ' ', $iconIdentifier);

            $parameters['items'][] = [$label, $iconIdentifier, $iconIdentifier, []];
        }
    }

    public function getRegisteredIconIdentifiers($parameters) {
        /** @var IconRegistry $iconRegistry */
        $iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);
        $iconIdentifiers = $iconRegistry->getAllRegisteredIconIdentifiers();

        foreach ($iconIdentifiers as $iconIndex => $iconIdentifier) {
            $label = str_replace('-', ' ', $iconIdentifier);

            $parameters['items'][] = [$label, $iconIdentifier, $iconIdentifier, []];
        }
    }

    public function getHeaderButtonsLeft($parameters) {
        $newItems = [];

        if (
            $parameters['row']['uid'] > 0
            && $parameters['row']['tables'] > 0
        ) {
            /** @var ObjectManager $objectManager */
            $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
            /** @var ModuleRepository $moduleRepository */
            $moduleRepository = $objectManager->get(ModuleRepository::class);
            /** @var Module $moduleObject */
            $moduleObject = $moduleRepository->findByUid($parameters['row']['uid']);

            $moduleTables = $moduleObject->getTables();

            if ($parameters['row']['modulelayout_header_buttons_showonenewrecordbuttonpertable']) {
                /**
                 * @var int $key
                 * @var Table $moduleTable
                 */
                foreach ($moduleTables as $key => $moduleTable) {
                    $tableName = $moduleTable->getTablename();

                    $tableLabel = ModuleUtility::getTableLabel($tableName);
                    $iconIdentifier = 'tcarecords-' . $tableName . '-default';
                    $itemLabel = 'Create new ' . $tableLabel . ' record (' . $iconIdentifier . ')';

                    $newItems[] = [$itemLabel, $iconIdentifier];
                }
            }

            if ($parameters['row']['modulelayout_header_buttons_showonenewrecordbuttonperrecordtype']) {
                $newItems = [];

                /**
                 * @var int $key
                 * @var Table $moduleTable
                 */
                foreach ($moduleTables as $key => $moduleTable) {
                    $tableName = $moduleTable->getTablename();
                    $allowedRecordTypes = explode(',', $moduleTable->getAllowedRecordTypes());
                    $typeIconColumn = $GLOBALS['TCA'][$tableName]['ctrl']['typeicon_column'];
                    $typeConfigs = $GLOBALS['TCA'][$tableName]['columns'][$typeIconColumn]['config']['items'];

                    foreach ($typeConfigs as $typeConfig) {
                        if (in_array($typeConfig[1], $allowedRecordTypes)) {
                            $label = $typeConfig[0];

                            $extensionKeyMatches = [];
                            preg_match('/EXT:(.*?)\//', $label, $extensionKeyMatches);
                            $typeLabel = LocalizationUtility::translate($label, $extensionKeyMatches[1]);

                            $itemLabel = 'Create new ' . $typeLabel . ' record (' . $typeConfig[2] . ')';

                            $newItems[] = [$itemLabel, $typeConfig[2]];
                        }
                    }
                }
            }
        }

        $parameters['items'] = array_merge($parameters['items'], $newItems);
    }

    public function getAvailableLocallangFiles($parameters) {
        $availableExtensions = ExtensionManagementUtility::getLoadedExtensionListArray();
        $availableLocallangFiles = [];

        foreach ($availableExtensions as $extensionName) {
            $extensionPath = ExtensionManagementUtility::extPath($extensionName);
            if (strstr($extensionPath, 'typo3conf')) {
                $locallangPath = $extensionPath . 'Resources/Private/Language/';
                $findResult = [];
                exec('find ' . $locallangPath . ' -type f | grep -P "xml|xlf"', $findResult);
                foreach ($findResult as $filepath) {
                    if (strlen(basename($filepath)) - strpos(basename($filepath), '.') === 4) {
                        $availableLocallangFiles[$extensionName][] = basename($filepath);
                    }
                }
            }
        }

        foreach ($availableLocallangFiles as $extensionName => $files) {
            $parameters['items'][] = [$extensionName, '--div--', ''];

            foreach ($files as $filename) {
                $parameters['items'][] = [$filename, 'EXT:' . $extensionName . '/Resources/Private/Language/' . $filename];
            }
        }
    }

    public function getAvailableModules($parameters) {
        $availableModules = array_keys($GLOBALS['TBE_MODULES']);

        foreach ($availableModules as $module) {
            if (!strstr($module, '_')) {
                $parameters['items'][] = [$module, $module];
            }
        }
    }

    /**
     * @return LanguageService
     */
    protected function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }
}