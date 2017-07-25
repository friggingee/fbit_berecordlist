define(['jquery', 'TYPO3/CMS/FbitBerecordlist/RemoveFooterFieldselection'], function ($) {
    'use strict';

    $('.fieldSelectBox').remove();
    $('body').removeClass('footer-fieldselection-remove');
});