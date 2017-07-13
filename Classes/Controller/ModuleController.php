<?php
namespace SBB\SbbBeRecordList\Controller;

/***
 *
 * This file is part of the "BE Record Lists" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2017 Grigori Prokhorov <hello@feinberg.it>, feinberg.it for familie redlich digital GmbH
 *
 ***/

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * ModuleController
 */
class ModuleController extends ActionController
{
    public function initializeAction() {
        if (empty(GeneralUtility::_GP('table'))) {
            GeneralUtility::_GETset('table', 'tx_news_domain_model_news');
        }
    }

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $config = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $storagePid = (int)$config['persistence']['storagePid'];

        // if no storage pid is set, use the current page.
        if ($storagePid === 0) {
            $storagePid = GeneralUtility::_GP('id');
        }

        $moduleArguments = [
            'id' => $storagePid,
            'table' => (empty(GeneralUtility::_GP('table')) ? 'tx_news_domain_model_news' : GeneralUtility::_GP('table'))
        ];
        $url = BackendUtility::getModuleUrl(
            'web_list',
            $moduleArguments
        );
        HttpUtility::redirect($url);
    }
}
