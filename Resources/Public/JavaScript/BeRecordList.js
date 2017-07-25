define(['jquery', 'TYPO3/CMS/FbitBerecordlist/BeRecordList'], function ($) {
    'use strict';

    /**
     * @type {{extension: string, recordtype: string, recordtypecolumn: string, requestUrlVars: {}, elementsUrlToChangeSelector: string, variablesUrlToChange: [*], initialize: initialize, explodeUrlVars: explodeUrlVars, implodeUrlVars: implodeUrlVars, adjustElementUrls: adjustElementUrls, adjustVariableUrls: adjustVariableUrls, adjustActionVars: adjustActionVars}}
     */
    var BeRecordList = {
        extension: '',
        recordtype: '',
        recordtypecolumn: '',
        id: '',
        table: '',
        requestUrlVars: {},
        elementsUrlToChangeSelector: 'form, a[href*="M=web_list"], input[onclick*="M=web_list"]',
        variablesUrlToChange: ['T3_THIS_LOCATION', 'T3_RETURN_URL', 'FBIT_LOCALIZATION_RETURN_URL'],

        /**
         * @return void
         */
        initialize: function() {
            BeRecordList.requestUrlVars = BeRecordList.explodeUrlVars(window.location.href);

            ['id', 'table', 'extension', 'recordtype', 'recordtypecolumn'].forEach(function(paramname) {
                BeRecordList.overloadObjectPropertyWithUrlParam(paramname);
            });

            BeRecordList.addDefValsIfPresent();
            BeRecordList.adjustElementUrls();
            BeRecordList.adjustVariableUrls();
            BeRecordList.adjustLocalizationUrls();
        },

        adjustLocalizationUrls: function() {
            var $elements = $('a[href*="[localize]=1"]');

            $elements.each(function(index, item) {
                var attr = 'href';
                var url = $(item).attr(attr);

                if (url.length > 1 && attr.length > 1) {
                    var actionVars = BeRecordList.explodeUrlVars(url);

                    actionVars = BeRecordList.adjustActionVars(actionVars);

                    var redirect = actionVars.redirect
                        + encodeURIComponent(
                            '&returnUrl=' + FBIT_LOCALIZATION_RETURN_URL + encodeURIComponent('&backFromLocalization=1')
                        );
                    $(item).attr(attr, $(item).attr(attr).replace(/redirect=.*?(&|$)/, 'redirect=' + redirect));
                }
            });
        },

        /**
         * @param paramname
         */
        overloadObjectPropertyWithUrlParam: function(paramname) {
            if (typeof BeRecordList.requestUrlVars[paramname] !== 'undefined') {
                BeRecordList[paramname] = BeRecordList.requestUrlVars[paramname].replace('#', '');
            }
        },

        addDefValsIfPresent: function() {
            var editParam = 'defVals[' + BeRecordList.table + '][type]';
            var newRecordParam = 'edit[' + BeRecordList.table + '][' + BeRecordList.id + ']';
            var encodedEditParam = encodeURIComponent(editParam);
            var $typeNewRecordButton = $('a[href*="' + encodedEditParam + '=' + BeRecordList.recordtype + '"]');

            if ($typeNewRecordButton.length > 0) {
                var defValsMatch = $typeNewRecordButton.attr('href').match(/(defVals.*?=.*?(&|$))/);
                var defValsString = defValsMatch[1];
                var decodedDefValsString = decodeURIComponent(defValsString);

                var searchString = newRecordParam + '=new';

                $('a[onclick*="' + newRecordParam + '=new"]').each(function(index, item) {
                    var replaceString = searchString + '\u0026' + decodedDefValsString;
                    var newOnclickValue = $(item).attr('onclick').replace(searchString, replaceString);

                    $(item).attr('onclick', newOnclickValue);
                });
            }
        },

        /**
         * Explodes the parameters of a url string into values and keys and returns an object holding them as parameters.
         * Considers encoding of the url with unicode codes \u0026 for & and \u003D for =.
         * Also, handles eval-able parts in a url string.
         *
         * @param url
         * @returns {{}}
         */
        explodeUrlVars: function(url) {
            url = url || window.location.href;
            var vars = {}, hash;
            var hashes = url.slice(url.search(/\?|\\u003F/) + 1).split(url.match(/&|\\u0026/));
            for (var i = 0; i < hashes.length; i++) {
                var hasEval = false;

                if (hashes[i].match(/(=|\\u003D)'\+/) !== null) {
                    hasEval = (hashes[i].split(hashes[i].match(/(=|\\u003D)'\+/)[0])[1] !== undefined);
                }

                var splitMatchRegex = new RegExp(hasEval ? /(=|\\u003D)'\+/ : /=|\\u003D/ );

                hash = hashes[i].split(hashes[i].match(splitMatchRegex)[0]);
                vars[hash[0]] = (hasEval ? '\' + ' + hash[1] + ' + \'' : hash[1]);
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
         * based on their node type to include the required parameters.
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
                    case 'INPUT':
                        attr = 'onclick';
                        url = $(item).attr(attr);
                        break;
                    default:
                        break;
                }

                if (url.length > 1 && attr.length > 1) {
                    var jumpToUrl = false;
                    if (url.match(/jumpToUrl\(.*?\),/)) {
                        jumpToUrl = true;
                        url = url.match(/jumpToUrl\((.*?),/)[1];
                    }
                    var actionVars = BeRecordList.explodeUrlVars(url);

                    actionVars = BeRecordList.adjustActionVars(actionVars);

                    var attrValue = BeRecordList.implodeUrlVars(actionVars);

                    if (jumpToUrl) {
                        attrValue = 'jumpToUrl(\'' + (attrValue.replace('/', '\/')) + '\', this);';
                    }

                    $(item).attr(attr, attrValue);
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

                actionVars = BeRecordList.adjustActionVars(actionVars);

                var targetUrl = encodeURIComponent(BeRecordList.implodeUrlVars(actionVars));

                window[variablename] = targetUrl;
            });
        },

        /**
         * @param actionVars
         * @returns {*}
         */
        adjustActionVars: function(actionVars) {
            if (typeof actionVars.extension === 'undefined') {
                actionVars.extension = BeRecordList.extension;
            }
            if (typeof actionVars.recordtype === 'undefined') {
                actionVars.recordtype = BeRecordList.recordtype;
            }
            if (typeof actionVars.recordtypecolumn === 'undefined') {
                actionVars.recordtypecolumn = BeRecordList.recordtypecolumn;
            }

            return actionVars;
        }
    };

    BeRecordList.initialize();
});