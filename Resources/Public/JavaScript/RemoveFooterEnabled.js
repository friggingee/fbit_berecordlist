define(['jquery', 'TYPO3/CMS/FbitBerecordlist/RemoveFooterEnabled'], function ($) {
    'use strict';

    $('.typo3-listOptions, .db_list-dashboard, .fieldSelectBox').remove();

    $('body').removeClass('footer-enabled-remove');
    $('body').removeClass('footer-fieldselection-remove');
    $('body').removeClass('footer-listoptions-extendedview-remove');
    $('body').removeClass('footer-listoptions-clipboard-remove');
    $('body').removeClass('footer-listoptions-localization-remove');
});