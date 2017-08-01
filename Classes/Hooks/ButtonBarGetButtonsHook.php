<?php

namespace FBIT\BeRecordList\Hooks;

use FBIT\BeRecordList\Utility\ModuleUtility;
use GeorgRinger\News\Utility\Page;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\Template\Components\Buttons\Action\HelpButton;
use TYPO3\CMS\Backend\Template\Components\Buttons\Action\ShortcutButton;
use TYPO3\CMS\Backend\Template\Components\Buttons\LinkButton;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\FormProtection\FormProtectionFactory;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class ButtonBarGetButtonsHook
{
    /**
     * @var IconFactory $iconFactory
     */
    protected $iconFactory = null;

    /**
     * @param array $params
     * @param ButtonBar $buttonBar
     * @return array $buttons
     */
    public function getButtons(array $params, ButtonBar $buttonBar): array
    {
        $buttons = $params['buttons'];
        $extensionKey = GeneralUtility::_GET('extension');

        // No extension key? No processing.
        if (empty($extensionKey)) {
            return $buttons;
        }

        ModuleUtility::loadModuleConfigurationForExtension($extensionKey);

        if (ModuleUtility::isLayoutFeatureEnabled('header.buttons.enabled')) {
            $this->iconFactory = GeneralUtility::makeInstance(IconFactory::class);
            // $this->triggerClipboard($buttons);
            $this->createNewRecordButtons($buttons, $extensionKey, $buttonBar);
            $this->removeDisabledDefaultButtons($buttons);
            $this->overrideDefaultIcons($buttons);
            $buttons = $this->sortButtons($buttons);
        } else {
            $buttons = [];
        }

        return $buttons;
    }

    protected function triggerClipboard(&$buttons): void
    {
        if (ModuleUtility::isButtonAvailable('actions-document-paste-into', $buttons)) {
            /** @var PageRenderer $pageRenderer */
            $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
            $jsInlineReflectionProperty = new \ReflectionProperty(PageRenderer::class, 'jsInline');
            $jsInlineReflectionProperty->setAccessible(true);
            $jsInline = $jsInlineReflectionProperty->getValue($pageRenderer);

            unset($jsInline['RequireJS-Module-TYPO3/CMS/FbitBerecordlist/RemoveFooterClipboard']);
            unset($jsInline['RecordListFeatureClass-footer-listoptions-clipboard-remove']);

            $jsInlineReflectionProperty->setValue($pageRenderer, $jsInline);
        }

    }

    /**
     * @param string $iconIdentifier
     * @param string|null $overlay
     * @return null|Icon
     */
    protected function getOverrideIcon(string $iconIdentifier, ?string $overlay = ''): ?Icon
    {
        $icon = null;

        if (
            is_array(ModuleUtility::$moduleConfig['moduleLayout']['header']['buttons']['left'][$iconIdentifier])
            && isset(ModuleUtility::$moduleConfig['moduleLayout']['header']['buttons']['left'][$iconIdentifier]['icon'])
        ) {
            try {
                $icon = $this->iconFactory->getIcon(
                    ModuleUtility::$moduleConfig['moduleLayout']['header']['buttons']['left'][$iconIdentifier]['icon'],
                    Icon::SIZE_SMALL,
                    (($overlay !== '') ? $overlay : null)
                );
            } catch (\Exception $exception) {}
        }

        return $icon;
    }

    /**
     * @param array $buttons
     * @param string $extensionName
     * @param ButtonBar $buttonBar
     */
    protected function createNewRecordButtons(array &$buttons, string $extensionName, ButtonBar $buttonBar): void
    {
        if (
            ModuleUtility::$moduleConfig['moduleLayout']['header']['showOneNewRecordButtonPerTable']
            || ModuleUtility::$moduleConfig['moduleLayout']['header']['showOneNewRecordButtonPerRecordType']
        ) {
            /** @var BackendUserAuthentication $beUser */
            $beUser = $GLOBALS['BE_USER'];

            $newRecordButtons = [];
            $buttonLabelPrefix = ModuleUtility::getModuleLL('backend.module.actions.new.prefix');
            $buttonLabelSuffix = ModuleUtility::getModuleLL('backend.module.actions.new.suffix');

            foreach (ModuleUtility::$moduleConfig['tables'] as $tableName => $tableConfiguration) {
                if ((bool)$tableConfiguration || is_array($tableConfiguration)) {
                    $label = $buttonLabelPrefix . ModuleUtility::getTableLabel($tableName) . $buttonLabelSuffix;

                    if (ModuleUtility::moduleLabelExists('backend.module.actions.new.' . $tableName)) {
                        $label = ModuleUtility::translate('backend.module.actions.new.' . $tableName, ModuleUtility::$extensionName);
                    }

                    $newRecordButton = [
                        'table' => $tableName,
                        'label' => $label,
                        'controller' => 'Module',
                        'action' => 'new'
                    ];
                    if (
                        ModuleUtility::$moduleConfig['moduleLayout']['header']['showOneNewRecordButtonPerRecordType']
                        && is_array($GLOBALS['TCA'][$tableName]['ctrl']['typeicon_classes'])
                    ) {
                        $typeIconColumnName = $GLOBALS['TCA'][$tableName]['ctrl']['typeicon_column'];
                        $typeIconColumnDefinition = $GLOBALS['TCA'][$tableName]['columns'][$typeIconColumnName];
                        $typeIconItems = $typeIconColumnDefinition['config']['items'];

                        if (is_array($typeIconItems)) {
                            foreach ($typeIconItems as $key => $typeIconItem) {
                                // You may create "Create new record" buttons for each type of record which is available
                                // even if you don't show a menu option for each record type since then you will simply
                                // see all record types in a single table instead of having a table for each record type
                                // but only if you did not also restrict the allowed record types.
                                if (
                                    is_array(ModuleUtility::$moduleConfig['tables'][$tableName]['allowedRecordTypes'])
                                    && !in_array($typeIconItem[1], ModuleUtility::$moduleConfig['tables'][$tableName]['allowedRecordTypes'])
                                ) {
                                    continue;
                                }

                                $label = $buttonLabelPrefix
                                    . LocalizationUtility::translate($typeIconItem[0], '')
                                    . $buttonLabelSuffix;

                                if (ModuleUtility::moduleLabelExists('backend.module.actions.new.' . $tableName . '.type.' . $typeIconItem[1])) {
                                    $label = ModuleUtility::translate('backend.module.actions.new.' . $tableName . '.type.' . $typeIconItem[1], ModuleUtility::$extensionName);
                                }

                                $newRecordTypeButton = $newRecordButton;
                                $newRecordTypeButton['label'] = $label;
                                $newRecordTypeButton['icon'] = $typeIconItem[2];
                                $newRecordTypeButton['type'] = $typeIconItem[1];
                                $newRecordTypeButton['type_column'] = $typeIconColumnName;

                                $newRecordButtons[] = $newRecordTypeButton;
                            }
                        }
                    } else {
                        $newRecordButtons[] = $newRecordButton;
                    }
                }
            }

            foreach ($newRecordButtons as $key => $buttonConfiguration) {
                if (
                    $beUser->isAdmin()
                    || GeneralUtility::inList($beUser->groupData['tables_modify'], $buttonConfiguration['table'])
                ) {
                    $buttonTable = $buttonConfiguration['table'];
                    $buttonStoragePid = ModuleUtility::$moduleConfig['storagePid'];
                    $buttonType = (isset($buttonConfiguration['type']) ? $buttonConfiguration['type'] : null);
                    $buttonTypeColumn = (isset($buttonConfiguration['type_column']) ? $buttonConfiguration['type_column'] : null);
                    $buttonTitle = $buttonConfiguration['label'];

                    $moduleName = 'web_list';
                    $moduleToken = FormProtectionFactory::get()->generateToken(
                        'moduleCall',
                        $moduleName
                    );

                    $returnUrl = 'index.php?M=' . $moduleName
                        . '&moduleToken=' . $moduleToken
                        . '&extension=' . ModuleUtility::$extensionName
                        . '&table=' . $buttonTable
                        . '&id=' . $buttonStoragePid;

                    if ($buttonType !== null) {
                        $returnUrl .= '&recordtype=' . $buttonType;
                    }
                    if ($buttonTypeColumn !== null) {
                        $returnUrl .= '&recordtypecolumn=' . $buttonTypeColumn;
                    }

                    $moduleArguments = [
                        'edit[' . $buttonTable . '][' . $buttonStoragePid . ']' => 'new',
                        'returnUrl' => $returnUrl,
                    ];

                    $recordIcon = $this->iconFactory->getIconForRecord(
                        $buttonTable,
                        [],
                        Icon::SIZE_SMALL
                    );
                    $icon = $this->iconFactory->getIcon(
                        $recordIcon->getIdentifier(),
                        Icon::SIZE_SMALL,
                        'overlay-new'
                    );

                    // Override icon button if a valid icon identifier is provided in the configuration.
                    $overrideIcon = $this->getOverrideIcon($recordIcon->getIdentifier(), 'overlay-new');
                    if ($overrideIcon !== null) {
                        $icon = $overrideIcon;
                    }

                    if (
                        ModuleUtility::$moduleConfig['moduleLayout']['header']['showOneNewRecordButtonPerRecordType']
                        && $buttonType !== null
                        && $buttonTypeColumn !== null
                    ) {
                        $moduleArguments['defVals[' . $buttonTable . '][' . $buttonTypeColumn . ']'] = $buttonType;
                        $icon = $this->iconFactory->getIcon($buttonConfiguration['icon'], Icon::SIZE_SMALL, 'overlay-new');

                        // Override icon button if a valid icon identifier is provided in the configuration.
                        $overrideIcon = $this->getOverrideIcon($recordIcon->getIdentifier(), 'overlay-new');
                        if ($overrideIcon !== null) {
                            $icon = $overrideIcon;
                        }
                    }

                    $url = BackendUtility::getModuleUrl(
                        'record_edit',
                        $moduleArguments
                    );

                    $viewButton = $buttonBar->makeLinkButton()
                        ->setHref($url)
                        ->setDataAttributes([
                            'toggle' => 'tooltip',
                            'placement' => 'bottom',
                            'title' => $buttonTitle,
                        ])
                        ->setTitle($buttonTitle)
                        ->setIcon($icon);
                    $buttons[ButtonBar::BUTTON_POSITION_LEFT][10][] = $viewButton;
                }
            }
        }
    }

    /**
     * @param array $buttons
     */
    protected function removeDisabledDefaultButtons(array &$buttons): void
    {
        if (
            is_array(ModuleUtility::$moduleConfig['moduleLayout']['header']['buttons']['left'])
            || is_array(ModuleUtility::$moduleConfig['moduleLayout']['header']['buttons']['right'])
        ) {

            $buttonConfig = [
                'left' => ModuleUtility::$moduleConfig['moduleLayout']['header']['buttons']['left'],
                'right' => ModuleUtility::$moduleConfig['moduleLayout']['header']['buttons']['right']
            ];

            foreach ($buttons as $headerSide => $headerSideButtons) {
                foreach ($headerSideButtons as $groupIndex => $groupButtons) {
                    foreach ($groupButtons as $buttonIndex => $buttonObject) {
                        $removeButton = false;

                        switch (get_class($buttonObject)) {
                            case LinkButton::class:
                                /** @var LinkButton $buttonObject*/
                                $buttonIdentifier = $buttonObject->getIcon()->getIdentifier();
                                if (
                                    array_key_exists($buttonIdentifier, $buttonConfig[$headerSide])
                                    && is_bool($buttonConfig[$headerSide][$buttonIdentifier])
                                ) {
                                    $removeButton = !$buttonConfig[$headerSide][$buttonIdentifier];
                                }
                                break;
                            case ShortcutButton::class:
                                if (
                                    array_key_exists('shortcut', $buttonConfig[$headerSide])
                                    || !$buttonConfig[$headerSide]['actions-system-shortcut-new']
                                ) {
                                    $removeButton = !$buttonConfig[$headerSide]['shortcut'];
                                }
                                break;
                            case HelpButton::class:
                                if (
                                    array_key_exists('csh', $buttonConfig[$headerSide])
                                    || !$buttonConfig[$headerSide]['actions-system-help-open']
                                ) {
                                    $removeButton = !$buttonConfig[$headerSide]['csh'];
                                }
                                break;
                        }

                        if ($removeButton) {
                            unset($buttons[$headerSide][$groupIndex][$buttonIndex]);
                        }
                    }
                    if (count($buttons[$headerSide][$groupIndex]) === 0) {
                        unset($buttons[$headerSide][$groupIndex]);
                    }
                }
            }
        }
    }

    protected function overrideDefaultIcons(&$buttons): void
    {
        if (is_array(ModuleUtility::$moduleConfig['moduleLayout']['header']['buttons'])) {

            foreach ($buttons as $headerSide => $headerSideButtons) {
                foreach ($headerSideButtons as $groupIndex => $groupButtons) {
                    foreach ($groupButtons as $buttonIndex => $buttonObject) {
                        $overrideIcon = null;

                        switch (get_class($buttonObject)) {
                            case LinkButton::class:
                                /** @var LinkButton $buttonObject */
                                $buttonIdentifier = $buttonObject->getIcon()->getIdentifier();

                                $overrideIcon = $this->getOverrideIcon($buttonIdentifier);
                                if ($overrideIcon instanceof Icon) {
                                    $buttonObject->setIcon($overrideIcon);
                                }

                                if ($overrideIcon !== null) {
                                    $buttons[$headerSide][$groupIndex][$buttonIndex] = $buttonObject;
                                }
                                break;
                            case ShortcutButton::class:
                            case HelpButton::class:
                                break;
                        }
                    }
                }
            }
        }
    }

    protected function sortButtons(array $unsortedButtons): array
    {
        $returnButtons['left'] = $unsortedButtons['left'];
        $returnButtons['right'] = $unsortedButtons['right'];

        if (is_array(ModuleUtility::$moduleConfig['moduleLayout']['header']['buttons']['sorting'])) {

            $buttonOrderArray = ModuleUtility::$moduleConfig['moduleLayout']['header']['buttons']['sorting'];
            $sortedButtons = [];

            $this->normalizeButtonSorting($unsortedButtons);
            $this->normalizeButtonSorting($buttonOrderArray);

            foreach ($buttonOrderArray as $headerSide => $headerSideButtons) {
                foreach ($headerSideButtons as $groupIndex => $groupButtons) {
                    foreach ($groupButtons as $buttonIndex => $buttonIdentifier) {

                        foreach ($unsortedButtons as $unsortedHeaderSide => $unsortedHeaderSideButtons) {
                            foreach ($unsortedHeaderSideButtons as $unsortedGroupIndex => $unsortedGroupButtons) {
                                foreach ($unsortedGroupButtons as $unsortedButtonIndex => $unsortedButtonObject) {

                                    switch (get_class($unsortedButtonObject)) {
                                        case LinkButton::class:
                                            /** @var LinkButton $unsortedButtonObject */
                                            $unsortedButtonIdentifier = $unsortedButtonObject->getIcon()->getIdentifier();
                                            break;
                                        case ShortcutButton::class:
                                            $unsortedButtonIdentifier = 'shortcut';
                                            break;
                                        case HelpButton::class:
                                            $unsortedButtonIdentifier = 'csh';
                                            break;
                                        default:
                                            $unsortedButtonIdentifier = '';
                                            break;
                                    }
                                    if ($buttonIdentifier === $unsortedButtonIdentifier) {
                                        $sortedButtons[$headerSide][$groupIndex][$buttonIndex] = $unsortedButtonObject;
                                    }
                                }
                            }
                        }

                    }
                }

                if (
                    count($sortedButtons[$headerSide], COUNT_RECURSIVE) === count($unsortedButtons[$headerSide], COUNT_RECURSIVE)
                    // @TODO this is quite a nasty "hack" since it implies that you could provide less buttons in the
                    // sorting order array than there are buttons available which would lead to an unknown state for the
                    // additional buttons.
                    || ModuleUtility::isLayoutFeatureEnabled('moduleFromRecord')
                ) {
                    $returnButtons[$headerSide] = $sortedButtons[$headerSide];
                }
            }

        }

        return $returnButtons;
    }

    protected function normalizeButtonSorting(&$sortingArray): void
    {
        foreach ($sortingArray as $headerSide => $headerSideButtons) {
            foreach ($headerSideButtons as $groupIndex => $groupButtons) {
                $sortingArray[$headerSide][$groupIndex] = array_values($groupButtons);
            }

            $sortingArray[$headerSide] = array_values($sortingArray[$headerSide]);
        }
    }
}