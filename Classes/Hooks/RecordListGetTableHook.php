<?php

namespace FBIT\BeRecordList\Hooks;

use FBIT\BeRecordList\Utility\ModuleUtility;
use TYPO3\CMS\Backend\RecordList\RecordListGetTableHookInterface;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Recordlist\RecordList\DatabaseRecordList;

class RecordListGetTableHook implements RecordListGetTableHookInterface
{
    protected $extensionName = '';

    /**
     * @var DatabaseRecordList $databaseRecordList
     */
    protected $databaseRecordList;

    protected $defaultSelectedFields = [
        'title',
        'uid',
        'pid',
        'hidden',
        'starttime',
        'endtime',
        'fe_group',
        'type',
        'editlock',
        'sys_language_uid',
        'l10n_parent',
    ];

    protected $defaultFieldArray = [
        'title',
        '_CONTROL_',
        '_CLIPBOARD_',
        '_LOCALIZATION_',
        '_LOCALIZATION_b',
    ];

    /**
     * @param string $table
     * @param int $pageId
     * @param string $additionalWhereClause
     * @param string $selectedFieldsList
     * @param \TYPO3\CMS\Recordlist\RecordList\DatabaseRecordList $parentObject
     */
    public function getDBlistQuery($table, $pageId, &$additionalWhereClause, &$selectedFieldsList, &$parentObject)
    {
        $this->databaseRecordList = &$parentObject;

        $this->redirectIfBackFromLocalization();

        $this->extensionName = GeneralUtility::_GP('extension');
        if (!empty($this->extensionName)) {
            ModuleUtility::loadModuleConfigurationForExtension($this->extensionName);
            $this->setRecordListFeatures();
            $this->addRecordTypeParametersToQuery($table, $pageId, $additionalWhereClause, $parentObject);
            $this->setDisplayFields($table, $selectedFieldsList, $parentObject);
        }
    }

    protected function setRecordListFeatures(): void
    {
        foreach ([
            'footer.listoptions.extendedview',
            'footer.listoptions.clipboard',
            'footer.listoptions.localization'
        ] as $layoutFeaturePath) {
            $layoutFeatureSetting = ModuleUtility::getModuleSettingByPath('moduleLayout.' . $layoutFeaturePath);

            if ($layoutFeatureSetting !== true) {
                $layoutFeatureSettingParts = explode('-', $layoutFeatureSetting);

                switch ($layoutFeaturePath) {
                    case 'footer.listoptions.extendedview':
                        switch ($layoutFeatureSettingParts[0]) {
                            case 'set':
                                $this->databaseRecordList->allFields = 1;
                                $GLOBALS['SOBE']->MOD_SETTINGS['bigControlPanel'] = 'expanded';
                                break;
                            case 'unset':
                                $this->databaseRecordList->allFields = 0;
                                $GLOBALS['SOBE']->MOD_SETTINGS['bigControlPanel'] = 'collapsed';
                        }
                        break;
                    case 'footer.listoptions.clipboard':
                        switch ($layoutFeatureSettingParts[0]) {
                            case 'set':
                                $this->databaseRecordList->showClipboard = true;
                                $GLOBALS['SOBE']->MOD_SETTINGS['clipBoard'] = true;
                                break;
                            case 'unset':
                                $this->databaseRecordList->showClipboard = false;
                                $GLOBALS['SOBE']->MOD_SETTINGS['clipBoard'] = false;
                                break;
                        }
                        break;
                    case 'footer.listoptions.localization':
                        switch ($layoutFeatureSettingParts[0]) {
                            case 'set':
                                $this->databaseRecordList->initializeLanguages();
                                $this->databaseRecordList->localizationView = true;
                                $GLOBALS['SOBE']->MOD_SETTINGS['localization'] = true;
                                break;
                            case 'unset':
                                $this->databaseRecordList->pageOverlays = [];
                                $this->databaseRecordList->languageIconTitles = [];
                                $this->databaseRecordList->localizationView = false;
                                $GLOBALS['SOBE']->MOD_SETTINGS['localization'] = false;
                                unset($this->defaultFieldArray[array_search('_LOCALIZATION_', $this->defaultFieldArray)]);
                                unset($this->defaultFieldArray[array_search('_LOCALIZATION_b', $this->defaultFieldArray)]);
                                break;
                        }
                        break;
                }
            }
        }
    }

    /**
     * Sets all relevant DatabaseRecordList variables to the fields given in the module's table configuration.
     *
     * @param string $table
     * @param string $selectedFieldsList
     * @param DatabaseRecordList $recordList
     */
    protected function setDisplayFields(string $table, string &$selectedFieldsList, DatabaseRecordList &$recordList): void
    {
        $displayFields = [];

        $tableDisplayFields = ModuleUtility::$moduleConfig['tables'][$table]['displayFields'];
        if (is_array($tableDisplayFields)) {
            $displayFields = array_keys($tableDisplayFields);
            foreach ($displayFields as $displayField) {
                $recordList->addElement_tdCssClass[$displayField] = 'col-displayfield-' . $displayField;
            }
        }

        $selectedFieldsList = implode(',', array_unique(array_merge($this->defaultSelectedFields, $displayFields)));
        $recordList->setFields[$table] = $displayFields;
        $recordList->displayFields[$table] = $displayFields;
        $recordList->fieldArray = array_merge($this->defaultFieldArray, $displayFields);
    }

    /**
     * Adds record type parameters to the database query if needed.
     *
     * @param string $table
     * @param int $pageId
     * @param string $additionalWhereClause
     * @param DatabaseRecordList $parentObject
     */
    protected function addRecordTypeParametersToQuery(string $table, int $pageId, string &$additionalWhereClause, DatabaseRecordList &$parentObject): void
    {
        $recordType = GeneralUtility::_GP('recordtype');
        $recordTypeColumn = GeneralUtility::_GP('recordtypecolumn');

        if (
            !empty($this->extensionName)
            && $recordType !== null
            && !empty($recordTypeColumn)
        ) {
            /** @var QueryBuilder $queryBuilder */
            $queryBuilder = $parentObject->getQueryBuilder($table, $pageId);
            $addWhere = (string)$queryBuilder->expr()->andX(
                $queryBuilder->expr()->eq(
                    $recordTypeColumn,
                    '"' . $recordType . '"'
                )
            );

            $additionalWhereClause = (string)$queryBuilder->expr()->andX($addWhere, $additionalWhereClause);
        }
    }

    /**
     * Checks the current returnUrl for the existence of a "backFromLocalization=1" parameter and redirects to the given
     * returnUrl (excluding that parameter) if found.
     *
     * This is needed since when localizing, the returnUrl is not being interpreted correctly, otherwise.
     */
    protected function redirectIfBackFromLocalization(): void
    {
        $returnUrl = GeneralUtility::_GET('returnUrl');
        if (!empty($returnUrl)) {
            $redirect = false;

            $returnUrlParts = explode('?', $returnUrl);
            $returnUrlParameters = explode('&', $returnUrlParts[1]);

            foreach ($returnUrlParameters as $returnUrlKey => $returnUrlParameter) {
                $parameterData = explode('=', $returnUrlParameter);
                if (
                    $parameterData[0] === 'backFromLocalization'
                    && (int)$parameterData[1] === 1
                ) {
                    $redirect = true;
                    unset($returnUrlParameters[$returnUrlKey]);
                }
            }

            $redirectUrlParts = implode('&', $returnUrlParameters);
            $redirectUrl = implode('?', [$returnUrlParts[0], $redirectUrlParts]);

            if (
                $redirect
                && !empty($redirectUrl)
            ) {
                HttpUtility::redirect($redirectUrl);
            }
        }
    }
}