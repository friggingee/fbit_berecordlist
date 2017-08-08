define(['jquery', 'TYPO3/CMS/FbitBerecordlist/RemoveFooterExtendedview'], function ($) {
    'use strict';

    if (!$('#checkLargeControl').is(':checked') || $('body').hasClass('footer-listoptions-extendedview-invisible')) {
        $('#checkLargeControl').parents('.checkbox').remove();
    }

    if ($('.typo3-listOptions .checkbox').length === 0) {
        $('.typo3-listOptions').remove();
    }
    $('body').removeClass('footer-listoptions-extendedview-invisible');
    $('body').removeClass('footer-listoptions-extendedview-remove');
});