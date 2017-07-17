define(['jquery', 'TYPO3/CMS/FbitBerecordlist/BeRecordList'], function ($) {
    'use strict';

    /**
     * @type {{extension: string, requestUrlVars: {}, elementsToChangeSelector: string, initialize: initialize, explodeUrlVars: explodeUrlVars, implodeUrlVars: implodeUrlVars, adjustModuleUrls: adjustModuleUrls}}
     */
    var BeRecordList = {
        extension: '',
        requestUrlVars: {},
        elementsUrlToChangeSelector: 'form, a[href*="M=web_list"]',
        variablesUrlToChange: [
            'T3_THIS_LOCATION'
        ],

        /**
         * @return void
         */
        initialize: function() {
            BeRecordList.requestUrlVars = BeRecordList.explodeUrlVars(window.location.href);
            if (typeof BeRecordList.requestUrlVars.extension !== 'undefined') {
                BeRecordList.extension = BeRecordList.requestUrlVars.extension.replace('#', '');
                BeRecordList.adjustElementUrls();
                BeRecordList.adjustVariableUrls();
            }
        },

        /**
         * Explodes the parameters of a url into values and keys and returns an object holding them
         *
         * @param url
         * @returns {{}}
         */
        explodeUrlVars: function(url) {
            url = url || window.location.href;
            var vars = {}, hash;
            var hashes = url.slice(url.indexOf('?') + 1).split('&');
            for (var i = 0; i < hashes.length; i++) {
                hash = hashes[i].split('=');
                vars[hash[0]] = hash[1];
            }
            return vars;
        },

        /**
         * Implodes an object containing named parameters to a url
         *
         * @param urlVars
         * @returns {string}
         */
        implodeUrlVars: function(urlVars) {
            var url = '';
            $.each(urlVars, function(param, value) {
                url += param + '=' + value + '&';
            });
            return url = window.location.pathname + '?' + url.substring(0, url.length - 1);
        },

        /**
         * Takes the element selector defined in BeRecordList.elementsToChangeSelector and adjusts their target URL
         * based on their node type to include the required "extension" parameter.
         *
         * @return void
         */
        adjustElementUrls: function() {
            var $elements = $(BeRecordList.elementsUrlToChangeSelector);

            $elements.each(function(index, item) {
                var attr = '';
                var url = '';

                switch (item.nodeName) {
                    case 'FORM':
                        attr = 'action';
                        url = $(item).attr(attr);
                        break;
                    case 'A':
                        attr = 'href';
                        url = $(item).attr(attr);
                        break;
                    default:
                        break;
                }

                if (url.length > 1 && attr.length > 1) {
                    var actionVars = BeRecordList.explodeUrlVars(url);

                    if (typeof actionVars.extension === 'undefined') {
                        actionVars.extension = BeRecordList.extension;
                    }

                    $(item).attr(attr, BeRecordList.implodeUrlVars(actionVars));
                }
            });
        },

        /**
         * @return void
         */
        adjustVariableUrls: function() {
            var variables = BeRecordList.variablesUrlToChange;

            $.each(variables, function(index, variablename) {
                var urlParts = decodeURIComponent(window[variablename]).split('?');
                var parameters = urlParts[1];

                var actionVars = BeRecordList.explodeUrlVars(parameters);

                if (typeof actionVars.extension === 'undefined') {
                    actionVars.extension = BeRecordList.extension;
                }

                window[variablename] = encodeURIComponent(BeRecordList.implodeUrlVars(actionVars));
            });
        }
    };

    BeRecordList.initialize();
});