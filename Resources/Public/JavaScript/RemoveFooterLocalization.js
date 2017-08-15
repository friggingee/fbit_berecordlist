define(['jquery', 'TYPO3/CMS/FbitBerecordlist/RemoveFooterLocalization'], function ($) {
    'use strict';

    if (!$('#checkLocalization').is(':checked') || $('body').hasClass('footer-listoptions-localization-invisible')) {
        $('#checkLocalization').parents('.checkbox').remove();
    }

    if ($('.typo3-listOptions .checkbox').length === 0) {
        $('.typo3-listOptions').remove();
    }
    $('body').removeClass('footer-listoptions-localization-invisible');
    $('body').removeClass('footer-listoptions-localization-remove');
});