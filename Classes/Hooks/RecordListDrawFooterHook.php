<?php

namespace SBB\SbbPois\Hooks;

use SBB\SbbPois\Domain\Model\News;
use SBB\SbbPois\Domain\Model\Poi;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Recordlist\RecordList;

class RecordListDrawFooterHook {
    /**
     * @param array $params
     * @param RecordList $recordList
     * @return string
     */
    public function getDocHeaderMenu(array $params, RecordList &$recordList) {
        $configurationManager = GeneralUtility::makeInstance(ConfigurationManager::class);
        $config = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
var_dump($config);die();
        $menu = $recordList->getModuleTemplate()->getDocHeaderComponent()->getMenuRegistry()->makeMenu();
        $menu->setIdentifier('berecordlist');

        $actions = [
            [
                'table' => News::table,
            ],
            [
                'table' => Poi::table,
            ],
        ];

        foreach ($actions as $action) {
            $moduleUri = $_SERVER['REQUEST_URI'];
            $moduleUriParts = parse_url($moduleUri);
            $moduleUriParameterStrings = explode('&', $moduleUriParts['query']);

            foreach ($moduleUriParameterStrings as $parameterString) {
                $parameterParts = explode('=', $parameterString);
                if ($parameterParts[0] === 'table') {
                    $parameterParts[1] = $action['table'];
                }

                $moduleUriParameters[$parameterParts[0]] = $parameterParts[1];
            }

            $menuUriParameterStrings = [];

            foreach ($moduleUriParameters as $paramKey => $paramValue) {
                $menuUriParameterStrings[] = $paramKey . '=' . $paramValue;
            }

            $menuUriQuery = implode('&', $menuUriParameterStrings);

            $menuUri = $moduleUriParts[0] . '?' . $menuUriQuery;

            $active = ($recordList->table === $action['table']);

            $item = $menu->makeMenuItem()
                ->setTitle('List ' . $this->getTableLabel($action['table']))
                ->setHref($menuUri)
                ->setActive($active);
            $menu->addMenuItem($item);
        }

        $recordList->getModuleTemplate()->getDocHeaderComponent()->getMenuRegistry()->addMenu($menu);

        return '';
    }

    protected function getTableLabel($table) {
        preg_match('/EXT:(.*?)\//', $GLOBALS['TCA'][$table]['ctrl']['title'], $matches);

        if (!empty($matches[1])) {
            $label = LocalizationUtility::translate($GLOBALS['TCA'][$table]['ctrl']['title'], $matches[1]);
        } else {
            $label = LocalizationUtility::translate($GLOBALS['TCA'][$table]['ctrl']['title'], '');
        }

        return $label;
    }
}