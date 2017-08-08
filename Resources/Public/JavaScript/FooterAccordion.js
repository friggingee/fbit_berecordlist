define(['jquery', 'TYPO3/CMS/FbitBerecordlist/FooterAccordion'], function ($) {
    'use strict';

    var toggleButtonHTML =
        '<button class="btn btn-default" id="footer-accordion-toggle">' +
        '   Footer <span class="caret"></span>' +
        '</button>';

    $('.typo3-listOptions, .db_list-dashboard, .fieldSelectBox').wrapAll(
        '<div class="footer-accordion">' +
        '   <div class="footer-accordion-content"></div>' +
        '</div>');

    $('.footer-accordion').prepend($(toggleButtonHTML));

    $('body').removeClass('footer-accordion-initially-hidden');

    $('#footer-accordion-toggle').on('click', function(event) {
        var $footerAccordionContent = $('.footer-accordion-content');

        $footerAccordionContent.is(':visible') ? $footerAccordionContent.slideUp() : $footerAccordionContent.slideDown();
    });
});