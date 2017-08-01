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

use Composer\Autoload\ClassLoader;
use FBIT\BeRecordList\Utility\ModuleUtility;
use TYPO3\ClassAliasLoader\ClassAliasLoader;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\FormProtection\FormProtectionFactory;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
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
        $currentPluginName = substr($this->request->getPluginName(), strpos($this->request->getPluginName(), 'BeRecordList') + strlen('BeRecordList'), strlen($this->request->getPluginName()));
        $extensionName = GeneralUtility::camelCaseToLowerCaseUnderscored($currentPluginName);
        ModuleUtility::loadModuleConfigurationForExtension($extensionName);
        $this->storagePid = (int)ModuleUtility::$moduleConfig['storagePid'];

        // if no storage pid is set, use the current page.
        if ($this->storagePid === 0) {
            $this->storagePid = GeneralUtility::_GP('id');
        }
        $table = (empty(GeneralUtility::_GP('table')) ? reset(array_keys(ModuleUtility::$moduleConfig['tables'])) : GeneralUtility::_GP('table'));

        ModuleUtility::setDisplayFields($table);

        $moduleArguments = [
            'id' => $this->storagePid,
            'table' => $table,
            'extension' => $extensionName,
        ];

        if (ModuleUtility::$moduleConfig['moduleLayout']['header']['menu']['showOneOptionPerRecordType']) {
            if (GeneralUtility::_GP('recordtype') !== null) {
                $recordType = GeneralUtility::_GP('recordtype');
            } elseif (isset(ModuleUtility::$moduleConfig['tables'][$table]['allowedRecordTypes'][0])) {
                $recordType = ModuleUtility::$moduleConfig['tables'][$table]['allowedRecordTypes'][0];
            } else {
                $recordType = 0;
            }

            if (GeneralUtility::_GP('recordtypecolumn')) {
                $recordTypeColumn = GeneralUtility::_GP('recordtypecolumn');
            } else {
                $recordTypeColumn = $GLOBALS['TCA'][$table]['ctrl']['typeicon_column'];
            }

            if (!empty($recordTypeColumn)) {
                $moduleArguments['recordtype'] = $recordType;
                $moduleArguments['recordtypecolumn'] = $recordTypeColumn;
            }
        }

        $url = BackendUtility::getModuleUrl(
            'web_list',
            $moduleArguments
        );
        HttpUtility::redirect($url);
    }
}
