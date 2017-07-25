define(['jquery', 'TYPO3/CMS/FbitBerecordlist/RemoveHeaderPagepath'], function ($) {
    'use strict';

    $('.typo3-docheader-pagePath').remove();
    $('.module-docheader-bar-column-right .t3js-contextmenutrigger').parents('strong').remove();
    $('body').removeClass('header-pagepath-remove');
});