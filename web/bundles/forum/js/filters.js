'use strict';

/* Filters */

angular.module('myApp.filters', []).
        filter('interpolate', ['version', function (version) {
                return function (text) {
                    return String(text).replace(/\%VERSION\%/mg, version);
                }
            }])
        .filter('Rial', function () {
            return function (dateString) {
                return dateString + 'Ø±ÙŠØ§Ù„';
            };
        })
        .filter('ConvertedToDateShamsi', function () {
            return function (dateString) {
                if (dateString != null)
                {
                    return moment(dateString, 'YYYY-M-D HH:mm:ss').format('jYYYY/jM/jD');
                } else {
                    return "ØªØ§Ø±ÛŒØ® Ù†Ø¯Ø§Ø±Ø¯"
                }
            };
        });
