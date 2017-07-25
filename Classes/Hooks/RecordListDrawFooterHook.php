<?php

namespace FBIT\BeRecordList\Hooks;

use FBIT\BeRecordList\Utility\ModuleUtility;
use TYPO3\CMS\Backend\Template\Components\Menu\Menu;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Recordlist\RecordList;

class RecordListDrawFooterHook
{
    /**
     * @var null|RecordList $recordList
     */
    protected $recordList = null;

    /**
     * @var null|PageRenderer
     */
    protected $pageRenderer = null;

    /**
     * @var array
     */
    protected $featureClasses = [];

    /**
     * @param array $params
     * @param RecordList $recordList
     * @return string
     * @throws \Exception
     */
    public function adjustWebListModule(array $params, RecordList &$recordList)
    {
        $extensionKey = GeneralUtility::_GET('extension');
        // No extension key? No processing.
        if (empty($extensionKey)) {
            return '';
        }

        $this->recordList = &$recordList;
        $this->pageRenderer = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            \TYPO3\CMS\Core\Page\PageRenderer::class
        );
        $this->pageRenderer->addCssFile(
            'EXT:fbit_berecordlist/Resources/Public/Stylesheets/Styles.css',
            'stylesheet', 'all', '', true, true, '', false, ''
        );
        ModuleUtility::loadModuleConfigurationForExtension($extensionKey);
        ModuleUtility::loadModuleLabels();
        ModuleUtility::buildAllowedLabels();
        $this->adjustHeader();
        $this->adjustFooter();
        $this->adjustHeadline();
        $this->addFeatureClasses();

        return '';
    }

    /**
     * Changes the module headline (and disables the inline edit functionality for the current page title) depending on
     * the availability of the required headline label and the module settings.
     */
    protected function adjustHeadline(): void
    {
        $moduleHeadlineKey = 'backend.module.headline';
        if (ModuleUtility::isLayoutFeatureEnabled('header.menu.showOneOptionPerTable')) {
            $moduleHeadlineKey = 'backend.module.headline.' . $this->recordList->table;
        }
        if (ModuleUtility::isLayoutFeatureEnabled('header.menu.showOneOptionPerRecordType')
            && null !== GeneralUtility::_GET('recordtype')
            && null !== GeneralUtility::_GET('recordtypecolumn')
        ) {
            $moduleHeadlineKey = 'backend.module.headline.' . $this->recordList->table . '.type.' . GeneralUtility::_GET('recordtype');
        }

        if (ModuleUtility::moduleLabelExists($moduleHeadlineKey)) {
            $contentHTML = $this->recordList->body;

            $newHeadline = ModuleUtility::translate($moduleHeadlineKey, ModuleUtility::$extensionName);

            $this->recordList->body = preg_replace(
                '/<h1 class="t3js-title-inlineedit">(.*?)<\/h1>/',
                '<h1>' . $newHeadline . '</h1>',
                $contentHTML
            );
        }
    }

    protected function adjustHeader(): void
    {
        if (ModuleUtility::isLayoutFeatureEnabled('header.enabled')) {
            if (
                ModuleUtility::isLayoutFeatureEnabled('header.menu.showOneOptionPerTable')
                || ModuleUtility::isLayoutFeatureEnabled('header.menu.showOneOptionPerRecordType')
            ) {
                $this->makeMenu();
            }
            if (!ModuleUtility::isLayoutFeatureEnabled('header.pagepath')) {
                $this->removeLayoutFeature('header.pagepath');
            }
        } else {
            $this->removeLayoutFeature('header.enabled');
        }
    }

    protected function adjustFooter(): void
    {
        if (ModuleUtility::isLayoutFeatureEnabled('footer.enabled')) {
            foreach ([
                'footer.fieldselection',
                'footer.listoptions.extendedview',
                'footer.listoptions.clipboard',
                'footer.listoptions.localization',
            ] as $layoutFeature) {
                if (!ModuleUtility::isLayoutFeatureEnabled($layoutFeature)) {
                    $this->removeLayoutFeature($layoutFeature);
                }
            }
        } else {
            $this->removeFooter();
        }
    }

    /**
     * Add classes to the body tag to manipulate the visibility of disabled module features via CSS before JS can actually
     * remove them from the DOM to avoid flickering.
     */
    public function addFeatureClasses(): void
    {
        foreach ($this->featureClasses as $baseClass => $status) {
            $baseClass = str_replace('.', '-', $baseClass);
            $block = '$(\'body\').addClass(\'' . $baseClass . '-' . $status . '\');' . PHP_EOL;
            $this->pageRenderer->addJsInlineCode(
                'RecordListFeatureClass-' . $baseClass . '-' . $status,
                '(function($) {
                    document.addEventListener("DOMContentLoaded", function() {
                        ' . $block . '
                    });
                })(TYPO3.jQuery);',
                true,
                true
            );
        }
    }

    /**
     * @param $layoutFeaturePath
     */
    protected function removeLayoutFeature($layoutFeaturePath)
    {
        $this->featureClasses[$layoutFeaturePath] = 'remove';

        switch ($layoutFeaturePath) {
            case 'header.enabled':
                $this->recordList->getModuleTemplate()->getDocHeaderComponent()->disable();
                break;
            case 'footer.enabled':
                $this->removeFooter();
                break;
            case 'header.pagepath':
            case 'footer.fieldselection':
            case 'footer.listoptions.extendedview':
            case 'footer.listoptions.clipboard':
            case 'footer.listoptions.localization':
                $layoutFeaturePath = str_replace('.listoptions', '', $layoutFeaturePath);
                $layoutFeaturePath = str_replace('.', '_', $layoutFeaturePath);
                $layoutFeaturePath = GeneralUtility::underscoredToUpperCamelCase($layoutFeaturePath);
                $requireJsModuleSignature = 'TYPO3/CMS/FbitBerecordlist/Remove' . $layoutFeaturePath;
                $this->pageRenderer->loadRequireJsModule($requireJsModuleSignature);
                break;
        }
    }

    protected function removeFooter()
    {
        $this->removeLayoutFeature('footer.fieldselection');
        $this->removeLayoutFeature('footer.listoptions.extendedview');
        $this->removeLayoutFeature('footer.listoptions.clipboard');
        $this->removeLayoutFeature('footer.listoptions.localization');
    }

    protected function makeMenu()
    {
        /** @var Menu $menu */
        $menu = $this->recordList->getModuleTemplate()->getDocHeaderComponent()->getMenuRegistry()->makeMenu();
        $menu->setIdentifier('berecordlist');

        $this->pageRenderer->loadRequireJsModule('TYPO3/CMS/FbitBerecordlist/BeRecordList');

        $actions = [];

        foreach (ModuleUtility::$moduleConfig['tables'] as $tableName => $tableConfiguration) {
            if ((bool)$tableConfiguration || is_array($tableConfiguration)) {
                $actionOption = [
                    'table' => $tableName,
                    'title' => ModuleUtility::getModuleLL('backend.module.select.prefix')
                        . ' "' . ModuleUtility::getTableLabel($tableName) . '"',
                ];
                if (ModuleUtility::moduleLabelExists('backend.module.select.' . $tableName)) {
                    $actionOption['title'] = ModuleUtility::translate('backend.module.select.' . $tableName, ModuleUtility::$extensionName);
                }

                if (ModuleUtility::$moduleConfig['moduleLayout']['header']['menu']['showOneOptionPerRecordType']) {
                    $typeIconColumnName = $GLOBALS['TCA'][$tableName]['ctrl']['typeicon_column'];
                    $typeIconColumnDefinition = $GLOBALS['TCA'][$tableName]['columns'][$typeIconColumnName];
                    $typeIconItems = $typeIconColumnDefinition['config']['items'];

                    if (is_array($typeIconItems)) {
                        foreach ($typeIconItems as $key => $typeIconItem) {
                            if (
                                is_array(ModuleUtility::$moduleConfig['tables'][$tableName]['allowedRecordTypes'])
                                && !in_array(
                                    $typeIconItem[1],
                                    ModuleUtility::$moduleConfig['tables'][$tableName]['allowedRecordTypes']
                                )
                            ) {
                                continue;
                            } else {
                                $title = ModuleUtility::getModuleLL('backend.module.select.prefix')
                                    . ' ' . ModuleUtility::getTableLabel($tableName)
                                    . ' ' . ModuleUtility::getModuleLL('backend.module.select.type')
                                    . ' "' . LocalizationUtility::translate($typeIconItem[0], '') . '"';

                                if (ModuleUtility::moduleLabelExists('backend.module.select.' . $tableName . '.type.' . $typeIconItem[1])) {
                                    $title = ModuleUtility::translate('backend.module.select.' . $tableName . '.type.' . $typeIconItem[1], ModuleUtility::$extensionName);
                                }

                                $actionTypeOption = $actionOption;
                                $actionTypeOption['title'] = $title;
                                $actionTypeOption['recordtype'] = $typeIconItem[1];
                                $actionTypeOption['recordtypecolumn'] = $typeIconColumnName;

                                $actions[] = $actionTypeOption;
                            }
                        }
                    } else {
                        $actions[] = $actionOption;
                    }
                } else {
                    $actions[] = $actionOption;
                }
            }
        }

        foreach ($actions as $actionTitle => $action) {
            $menuUri = $this->getMenuUri($action);
            $active = ($this->recordList->table === $action['table']);

            if (
                ModuleUtility::$moduleConfig['moduleLayout']['header']['menu']['showOneOptionPerRecordType']
                && isset($action['recordtype'])
            ) {
                $recordType = GeneralUtility::_GET('recordtype') !== null ? GeneralUtility::_GET('recordtype') : 0;
                $active = ((int)$recordType === (int)$action['recordtype']);
            }

            $item = $menu->makeMenuItem()
                ->setTitle($action['title'])
                ->setHref($menuUri)
                ->setActive($active);
            $menu->addMenuItem($item);
        }

        $this->recordList->getModuleTemplate()->getDocHeaderComponent()->getMenuRegistry()->addMenu($menu);
    }

    /**
     * @param array $action
     * @return string
     */
    protected function getMenuUri(array $action)
    {
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

            // If every record type should be shown, enrich the menu URL with the appropriate parameters
            // but be aware that since we're sourcing the URL we're adjusting from $_SERVER['REQUEST_URI']
            // we also need to remove these parameters if they're present for a table which does not require them.
            //
            // @TODO I'm sure there's a better way to get $moduleUri but this is stable for now.
            if (
                ModuleUtility::$moduleConfig['moduleLayout']['header']['menu']['showOneOptionPerRecordType']
                && isset($action['recordtype'])
                && !empty($action['recordtypecolumn'])
            ) {
                $moduleUriParameters['recordtype'] = $action['recordtype'];
                $moduleUriParameters['recordtypecolumn'] = $action['recordtypecolumn'];
            } elseif (
                $parameterParts[0] === 'recordtype'
                || $parameterParts[0] === 'recordtypecolumn'
            ) {
                unset($moduleUriParameters[$parameterParts[0]]);
            }
        }

        $menuUriParameterStrings = [];

        foreach ($moduleUriParameters as $paramKey => $paramValue) {
            $menuUriParameterStrings[] = $paramKey . '=' . $paramValue;
        }

        $menuUriQuery = implode('&', $menuUriParameterStrings);

        return $moduleUriParts[0] . '?' . $menuUriQuery;
    }
}