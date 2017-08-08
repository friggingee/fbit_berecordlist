define(['jquery', 'TYPO3/CMS/FbitBerecordlist/RemoveFooterClipboard'], function ($) {
    'use strict';

    if (!$('#checkShowClipBoard').is(':checked') || $('body').hasClass('footer-listoptions-clipboard-invisible')) {
        $('#checkShowClipBoard').parents('.checkbox').remove();
    }

    if ($('.typo3-listOptions .checkbox').length === 0) {
        $('.typo3-listOptions').remove();
    }
    $('body').removeClass('footer-listoptions-clipboard-invisible');
    $('body').removeClass('footer-listoptions-clipboard-remove');
});