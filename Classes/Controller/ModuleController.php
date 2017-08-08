<?php
namespace FBIT\BeRecordList\Controller;

/***
 *
 * This file is part of the "BE Record Lists" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2017 Grigori Prokhorov <hello@feinberg.it>, feinberg.it
 *
 ***/

use FBIT\BeRecordList\Utility\ModuleUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * ModuleController
 */
class ModuleController extends ActionController
{
    /**
     * @var array
     */
    protected $requestArguments = [];

    /**
     * @var int
     */
    protected $storagePid = 0;

    /**
     * @var string
     */
    protected $extensionName = '';

    /**
     * @var string
     */
    protected $table = '';

    /**
     * @var
     */
    protected $recordType;

    /**
     * @var string
     */
    protected $recordTypeColumn = '';

    /**
     * initialize action
     */
    public function initializeAction() {
        $this->requestArguments = $this->request->getArguments();
    }

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $this->loadModuleConfiguration();
        $this->setStoragePid();
        $this->setTable();

        $moduleArguments = [
            'id' => $this->storagePid,
            'table' => $this->table,
            'extension' => $this->extensionName,
        ];

        if (ModuleUtility::isLayoutFeatureEnabled('header.menu.showOneOptionPerRecordType')) {
            $this->setRecordType();
            $this->setRecordTypeColumn();

            if (!empty($this->recordTypeColumn)) {
                $moduleArguments['recordtype'] = $this->recordType;
                $moduleArguments['recordtypecolumn'] = $this->recordTypeColumn;
            }
        }

        $url = BackendUtility::getModuleUrl(
            'web_list',
            $moduleArguments
        );
        HttpUtility::redirect($url);
    }

    protected function loadModuleConfiguration(): void
    {
        $currentPluginName = substr(
            $this->request->getPluginName(),
            strpos($this->request->getPluginName(), 'BeRecordList') + strlen('BeRecordList'),
            strlen($this->request->getPluginName())
        );
        $this->extensionName = GeneralUtility::camelCaseToLowerCaseUnderscored($currentPluginName);
        ModuleUtility::loadModuleConfigurationForExtension($this->extensionName);
    }

    protected function setStoragePid(): void
    {
        $this->storagePid = (int)ModuleUtility::$moduleConfig['storagePid'];

        // if no storage pid is set, use the current page.
        if ($this->storagePid === 0) {
            $this->storagePid = GeneralUtility::_GP('id');
        }
    }

    protected function setTable(): void
    {
        $this->table = GeneralUtility::_GP('table');
        if (empty($this->table)) {
            $this->table = reset(array_keys(ModuleUtility::$moduleConfig['tables']));
        }
    }

    protected function setRecordType(): void
    {
        $this->recordType = 0;

        if (GeneralUtility::_GP('recordtype') !== null) {
            $this->recordType = GeneralUtility::_GP('recordtype');
        } elseif (isset(ModuleUtility::$moduleConfig['tables'][$this->table]['allowedRecordTypes'][0])) {
            $this->recordType = ModuleUtility::$moduleConfig['tables'][$this->table]['allowedRecordTypes'][0];
        }
    }

    protected function setRecordTypeColumn(): void
    {
        $this->recordTypeColumn = $GLOBALS['TCA'][$this->table]['ctrl']['typeicon_column'];

        if (GeneralUtility::_GP('recordtypecolumn')) {
            $this->recordTypeColumn = GeneralUtility::_GP('recordtypecolumn');
        }
    }
}
