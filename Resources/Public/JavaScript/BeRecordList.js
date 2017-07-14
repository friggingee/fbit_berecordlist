define(['jquery', 'TYPO3/CMS/FbitBerecordlist/BeRecordList'], function ($) {
    'use strict';

    var BeRecordList = {
        extension: '',
        requestUrlVars: {},
        initialize: function() {
            BeRecordList.requestUrlVars = BeRecordList.explodeUrlVars(window.location.href);
            if (typeof BeRecordList.requestUrlVars.extension !== 'undefined') {
                BeRecordList.extension = BeRecordList.requestUrlVars.extension;
                BeRecordList.adjustFormActions();
            }
        },
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
        implodeUrlVars: function(urlVars) {
            var url = '';
            $.each(urlVars, function(param, value) {
                url += param + '=' + value + '&';
            });
            return url = 'index.php?' + url.substring(0, url.length - 1);
        },
        adjustFormActions: function() {
            var $forms = $('form');
            $forms.each(function(index, item) {
                var actionVars = BeRecordList.explodeUrlVars($(item).attr('action'));

                if (typeof actionVars.extension === 'undefined') {
                    actionVars.extension = BeRecordList.extension;
                }

                $(item).attr('action', BeRecordList.implodeUrlVars(actionVars));
            });
        }
    };

    BeRecordList.initialize();
});