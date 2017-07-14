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
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $currentPluginName = str_replace('fbit_BeRecordList', '', $this->request->getPluginName());
        $extensionName = GeneralUtility::camelCaseToLowerCaseUnderscored($currentPluginName);
        $config = $GLOBALS['TYPO3_CONF_VARS']['EXT']['fbit_berecordlist']['modules'][$extensionName];
        $storagePid = (int)$config['storagePid'];

        // if no storage pid is set, use the current page.
        if ($storagePid === 0) {
            $storagePid = GeneralUtility::_GP('id');
        }

        $moduleArguments = [
            'id' => $storagePid,
            'table' => (empty(GeneralUtility::_GP('table')) ? reset(array_keys($config['tables'])) : GeneralUtility::_GP('table')),
            'extension' => $extensionName,
        ];
        $url = BackendUtility::getModuleUrl(
            'web_list',
            $moduleArguments
        );
        HttpUtility::redirect($url);
    }
}
