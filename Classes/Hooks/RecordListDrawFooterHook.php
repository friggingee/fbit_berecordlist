<?php

namespace FBIT\BeRecordList\Hooks;

use FBIT\BeRecordList\Utility\ModuleUtility;
use TYPO3\CMS\Backend\Template\Components\Menu\Menu;
use TYPO3\CMS\Core\Messaging\AbstractStandaloneMessage;
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
        $this->adjustBody();
        $this->adjustFooter();
        $this->addFeatureClasses();

        return '';
    }

    /**
     * Checks all available header layout features' state and adjusts header footer accordingly.
     */
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

    /**
     * Wrapper function for all method calls which change the module body (which is not necessarily equal to the content
     * of the body-tag but instead the HTML excluding the module header and footer HTML).
     */
    protected function adjustBody(): void
    {
        $this->adjustHeadline();
        $this->processDisplayFields();
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

    /**
     * Processes the output value of the additional columns shown in each record row by adjusting the rendered RecordList
     * HTML.
     *
     * Have a look at the comments in the method body for more details on what's happening.
     *
     * As to the why: In general, I can think of three options on how to influence the output of the value of a record's
     * column in the RecordList.
     *  First option is to XCLASS (or override via ['SYS']['Objects'], which is basically the same) the RecordList class
     *      and add your own processing. This is the most flexible approach but also the most invasive. As we all know
     *      XCLASS'ed classes can be overwritten by any extension but also are prone to overwrite already existing
     *      XCLASSes, none of which is really a good thing which is why I try really hard to avoid this.
     *  The second option and the best approach IMHO, would be to use Signal Slots. Only issue is: there are literally
     *      none in the whole codebase which is used by EXT:recordlist (yes, including all extension-external classes).
     *  The third approach would be to use hooks. Now, there are no hooks in EXT:recordlist itself but it makes use of
     *      classes and methods which contain hooks which are actually called exactly where we'd need them to affect the
     *      record's column value output.
     *
     *      These hooks are triggered in \TYPO3\CMS\Recordlist\RecordList\DatabaseRecordList::renderListRow() which uses
     *      \TYPO3\CMS\Backend\Utility\BackendUtility::getProcessedValueExtra() which in turn calls
     *      \TYPO3\CMS\Backend\Utility\BackendUtility::getProcessedValue().
     *
     *      This final method contains two hooks which are:
     *      $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_befunc.php']['preProcessValue'] and
     *      $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_befunc.php']['postProcessValue'].
     *
     *      Now, one might assume that this is it and we simply register some generic processing functions in a utility
     *      class to change the column value output to our heart's content but far from it! You see, whoever added
     *      these hooks to the BackendUtility didn't seem to consider that a hook alone is not enough but it also has
     *      to properly convey the context in which it is being called.
     *      Both these hooks fail to do so since they get no reference passed to the field they are being called on
     *      which makes it completely impossible to figure out which output transformation should be applied.
     *      This is also a reported issue on the forge https://forge.typo3.org/issues/32169, 5 years old to date and
     *      still unresolved (not that it would take a lot to fix it, honestly).
     *
     * However, this leaves me with just one option as of now which is to do what I do in this method which is to
     * manually parse and adjust the DOM of the already rendered RecordList HTML.
     *
     * I like this approach as little as the next developer but that's the best I can come up with short of XCLASSing.
     *
     * @throws \Exception
     */
    protected function processDisplayFields(): void
    {
        $html = $this->recordList->body;
        $originalEncoding = mb_detect_encoding($html);

        // Load the current record list body as a DOM Document
        /** @var \DOMDocument $domDocument */
        $domDocument = new \DOMDocument('1.0', $originalEncoding);
        // Avoid warnings for HTML5 tags.
        // @see https://stackoverflow.com/questions/9149180/domdocumentloadhtml-error
        libxml_use_internal_errors(true);
        $domDocument->loadHtml($html);
        $errors = libxml_get_errors();
        libxml_use_internal_errors(false);

        /** @var \LibXMLError $error */
        foreach ($errors as $error) {
            if ($error->level > LIBXML_ERR_ERROR) {
                $exception = new \Exception(
                    'LIBXML_ERR_FATAL (' . $error->level . '): ' . $error->message,
                    $error->code
                );

                throw $exception;
            }
        }
        $domDocument->encoding = $originalEncoding;

        // Process display fields settings
        if (is_array(ModuleUtility::$moduleConfig['tables'][$this->recordList->table]['displayFields'])) {
            foreach (ModuleUtility::$moduleConfig['tables'][$this->recordList->table]['displayFields'] as $field => $configuration) {
                // If the configuration contains an array key of "displayProcFunc", we assume that it's a function
                // reference which shall be used to override the display value (plain text or HTML content) of a field
                // column.
                if (
                    is_array($configuration)
                    && !empty($configuration['displayProcFunc'])
                ) {
                    $procFuncData = explode('->', $configuration['displayProcFunc']);
                    $procFuncObject = GeneralUtility::makeInstance($procFuncData[0]);

                    $fieldTds = $domDocument->getElementsByTagName('td');

                    /** @var \DOMElement $fieldTd */
                    foreach ($fieldTds as $fieldTd) {
                        if (strstr($fieldTd->getAttribute('class'), 'col-displayfield-' . $field)) {
                            // call the user func found earlier
                            $newContent = utf8_encode(
                                call_user_func(
                                    [
                                        $procFuncObject,
                                        $procFuncData[1],
                                    ],
                                    // current table
                                    $this->recordList->table,
                                    // record uid
                                    $fieldTd->parentNode->getAttribute('data-uid'),
                                    // current field
                                    $field,
                                    // current column value
                                    $fieldTd->textContent
                                )
                            );

                            if (!empty($newContent)) {
                                // Load the returned new column value into a DOM Fragment to check if it contains additional
                                // HTML which we need to process differently.
                                /** @var \DOMDocumentFragment $newContentDOM */
                                $domFragment = $domDocument->createDocumentFragment();
                                try {
                                    $domFragment->appendXML(
                                        preg_replace(
                                            '/&(?!#?[a-z0-9]+;)/',
                                            '&amp;',
                                            utf8_encode(
                                                html_entity_decode(
                                                    preg_replace(
                                                        '/(<br \/>)+/',
                                                        '<br />',
                                                        preg_replace(
                                                            '/[\n\r]\s+/m',
                                                            ' ',
                                                            $newContent
                                                        )
                                                    )
                                                )
                                            )
                                        )
                                    );

                                    if (
                                        $domFragment->childNodes->length === 1
                                        && $domFragment->childNodes->item(0)->nodeType === XML_TEXT_NODE
                                    ) {
                                        // Only one child node of type text? This goes directly back into the current td node.
                                        $fieldTd->textContent = $newContent;
                                    } else {
                                        // Additional HTML?
                                        // Remove all current children of the current td node...
                                        foreach ($fieldTd->childNodes as $childNode) {
                                            $fieldTd->removeChild($childNode);
                                        }
                                        // ...and replace them with the DOM Fragment we created.
                                        $fieldTd->appendChild($domFragment);
                                    }
                                } catch (\Exception $exception) {
                                    $this->recordList->getModuleTemplate()->addFlashMessage(
                                        $newContent,
                                        'An encoding error occurred while rendering the following text.',
                                        AbstractStandaloneMessage::INFO
                                    );
                                }
                            }
                        }
                    }
                }
            }
        }

        // str_replace("\xc2\xa0", ' ', $str) removes utf8_decoded &nbsp; characters which would otherwise show up as
        // boxed question marks.
        $this->recordList->body = utf8_decode(
            str_replace(
                "\xc2\xa0",
                ' ',
                $domDocument->saveXML($domDocument, LIBXML_NOEMPTYTAG)
            )
        );
    }

    /**
     * Checks all available footer layout features' state and adjusts the footer accordingly.
     */
    protected function adjustFooter(): void
    {
        if (ModuleUtility::isLayoutFeatureEnabled('footer.enabled')) {
            foreach ([
                'footer.fieldselection',
                'footer.listoptions.extendedview',
                'footer.listoptions.clipboard',
                'footer.listoptions.localization',
            ] as $layoutFeature) {
                $layoutFeatureSetting = ModuleUtility::getModuleSettingByPath('moduleLayout.' . $layoutFeature);
                $layoutFeatureSettingParts = explode('-', $layoutFeatureSetting);

                if (!ModuleUtility::isLayoutFeatureEnabled($layoutFeature) || $layoutFeatureSettingParts[1] === 'invisible') {
                    $this->featureClasses[$layoutFeature] = ($layoutFeatureSettingParts[1] === 'invisible' ? 'invisible' : 'remove');

                    $this->removeLayoutFeature($layoutFeature);
                }
            }

            if (ModuleUtility::$moduleConfig['moduleLayout']['footer']['enabled'] === 'accordion') {
                $this->makeFooterAccordion();
            }
        } else {
            $this->removeFooter();
        }
    }

    /**
     * Add classes to the body tag to manipulate the visibility of disabled module features via CSS before JS can actually
     * remove them from the DOM to avoid flickering.
     */
    protected function addFeatureClasses(): void
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
     * Calls the appropriate methods to disable a specific layout feature.
     *
     * @param $layoutFeaturePath
     */
    protected function removeLayoutFeature($layoutFeaturePath): void
    {
        switch ($layoutFeaturePath) {
            case 'header.enabled':
                $this->recordList->getModuleTemplate()->getDocHeaderComponent()->disable();
                break;
            case 'footer.enabled':
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

    /**
     * Calls all relevant methods to fully hide all footer elements.
     */
    protected function removeFooter(): void
    {
        $this->removeLayoutFeature('footer.enabled');
    }

    /**
     * Generates the dropdown menu used for table or record type selection.
     */
    protected function makeMenu(): void
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
     * Generates the URI for a given menu item from the $actionConfiguration.
     *
     * @param array $actionConfiguration
     * @return string
     */
    protected function getMenuUri(array $actionConfiguration): string
    {
        $moduleUri = $_SERVER['REQUEST_URI'];
        $moduleUriParts = parse_url($moduleUri);
        $moduleUriParameters = [];
        $moduleUriParameterStrings = explode('&', $moduleUriParts['query']);

        foreach ($moduleUriParameterStrings as $parameterString) {
            $parameterParts = explode('=', $parameterString);
            if ($parameterParts[0] === 'table') {
                $parameterParts[1] = $actionConfiguration['table'];
            } else if ($parameterParts[0] === 'id') {
                $table = $actionConfiguration['table'];

                // Retrieve storagePid from tables configuration if set
                if (array_key_exists('tables', ModuleUtility::$moduleConfig)
                    && array_key_exists($table, ModuleUtility::$moduleConfig['tables'])
                    && array_key_exists('storagePid', ModuleUtility::$moduleConfig['tables'][$table])) {
                    $parameterParts[1] = (int)ModuleUtility::$moduleConfig['tables'][$table]['storagePid'];
                }
            }
            $moduleUriParameters[$parameterParts[0]] = $parameterParts[1];

            // If every record type should be shown, enrich the menu URL with the appropriate parameters
            // but be aware that since we're sourcing the URL we're adjusting from $_SERVER['REQUEST_URI']
            // we also need to remove these parameters if they're present for a table which does not require them.
            //
            // @TODO I'm sure there's a better way to get $moduleUri but this is stable for now.
            if (
                ModuleUtility::$moduleConfig['moduleLayout']['header']['menu']['showOneOptionPerRecordType']
                && isset($actionConfiguration['recordtype'])
                && !empty($actionConfiguration['recordtypecolumn'])
            ) {
                $moduleUriParameters['recordtype'] = $actionConfiguration['recordtype'];
                $moduleUriParameters['recordtypecolumn'] = $actionConfiguration['recordtypecolumn'];
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

    protected function makeFooterAccordion(): void
    {
        $this->featureClasses['footer.accordion'] = 'initially-hidden';
        $this->pageRenderer->loadRequireJsModule('TYPO3/CMS/FbitBerecordlist/FooterAccordion');
    }
}