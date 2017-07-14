<?php

namespace FBIT\BeRecordList\Hooks;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Recordlist\RecordList;

class RecordListDrawFooterHook {
    /**
     * @param array $params
     * @param RecordList $recordList
     * @return string
     */
    public function getDocHeaderMenu(array $params, RecordList &$recordList) {
        $extensionName = GeneralUtility::_GET('extension');

        if (!is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['fbit_berecordlist']['modules'][$extensionName])) {
            throw new \Exception('There seems to be no configuration available for EXT:' . $extensionName . '. Please refer to the README for more details.', 1500032366);
        }

        $config = $GLOBALS['TYPO3_CONF_VARS']['EXT']['fbit_berecordlist']['modules'][$extensionName];

        $menu = $recordList->getModuleTemplate()->getDocHeaderComponent()->getMenuRegistry()->makeMenu();
        $menu->setIdentifier('berecordlist');

        $actions = [];

        foreach ($config['tables'] as $tableName => $active) {
            if ((bool)$active) {
                $actions[] = ['table' => $tableName];
            }
        }

        foreach ($actions as $action) {
            $moduleUri = $_SERVER['REQUEST_URI'];
            $moduleUriParts = parse_url($moduleUri);
            $moduleUriParameters = [];
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
                ->setTitle(
                    $this->getLabel('backend.module.select.prefix')
                    . ' "' . $this->getTableLabel($action['table']) . '"'
                )
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

    protected function getLabel($key) {
        return LocalizationUtility::translate(
            'LLL:EXT:fbit_berecordlist/Resources/Private/Language/locallang_mod_module.xlf:' . $key,
            'fbit_berecordlist'
        );
    }
}