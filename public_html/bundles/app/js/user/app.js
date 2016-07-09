'use strict';


// Declare app level module which depends on filters, and services
angular.module('myApp', ['myApp.filters', 'myApp.services', 'myApp.directives', 'myApp.controllers', 'ngSanitize','ngCookies'])
        .config(['$routeProvider', '$compileProvider', function ($routeProvider, $compileProvider) {

                $routeProvider.when('/userInfo', {templateUrl: '../bundles/app/partials/users/panelUser.html', controller: 'panelUser'});
                $routeProvider.when('/userAnimals', {templateUrl: '../bundles/app/partials/animals/animals.html', controller: 'userAnimals'});
                $routeProvider.when('/reservetime', {templateUrl: '../bundles/app/partials/reservetime/reservetime.html', controller: 'reservetime'});
                $routeProvider.when('/history', {templateUrl: '../bundles/app/partials/savabegh/savabegh.html', controller: 'savabeghInfo'});
                $routeProvider.when('/chip', {templateUrl: '../bundles/app/partials/microchip/microchip.html', controller: 'chipCode'});
                $routeProvider.when('/messages', {templateUrl: '../bundles/app/partials/messages/messages.html', controller: 'messages'});
                $routeProvider.when('/shopInfo', {templateUrl: '../bundles/app/partials/shop/shop.html', controller: 'shopinfo'});
            }]);

angular.module('myApp.controllers', [])
        .controller('panelUser', [function () {

            }])
        .controller('reservetime', [function () {


            }])
        .controller('userAnimals', [function () {
            }])
        .controller('savabeghInfo', [function () {


            }])
        .controller('chipCode', [function () {


            }])
        .controller('messages', [function () {


            }])
        .controller('shopinfo', ['$scope', function ($scope) {
                $scope.flagShowMessageBank = false;

            }]);

angular.module('myApp.services', [])
        .factory('ConvertShamsiToMiladi', function () {
            return {
                Convert: function (datetimeGet) {
                    return moment(datetimeGet, 'jYYYY/jM/jD HH:mm').format('YYYY-M-D HH:mm:ss');
                }
            };
        })
        .factory('ConvertMiladiToShamsi', function () {
            return {
                Convert: function (datetimeGet) {
                    return moment(datetimeGet, 'YYYY-M-D HH:mm:ss').format('jYYYY/jM/jD HH:mm:ss');
                }
            };
        });
angular.module('myApp.filters', []).
        filter('interpolate', ['version', function (version) {
                return function (text) {
                    return String(text).replace(/\%VERSION\%/mg, version);
                };
            }])
        .filter('Rial', function () {
            return function (dateString) {
                return dateString + 'ريال';
            };
        })
        .filter('IfNull', function () {
            return function (value) {
                if (value === null || value === "" || typeof value === "undefined")
                {
                    return ' --- ';
                } else {
                    return value;
                }

            };
        })
        .filter('ConvertedToDateShamsi', function () {
            return function (dateString) {
                if (dateString != null)
                {
                    return moment(dateString, 'YYYY-M-D HH:mm:ss').format('jYYYY/jM/jD');
                } else {
                    return "تاریخ ندارد"
                }
            };
        })
        .filter('FillNullTableField', function () {
            return function (Value) {
                if (Value != null)
                {

                } else {

                }
            };
        })
        .filter('linkRemoveUnsafe', function () {
            return function (text) {
                return  text ? String(text).replace(/^\s*(https?|ftp|file|blob):|data:image\//, '') : '';
            };
        }
        )
        .filter('cut', function () {
            return function (value, wordwise, max, tail) {
                if (!value)
                    return '';
                max = parseInt(max, 10);
                if (!max)
                    return value;
                if (value.length <= max)
                    return value;
                value = value.substr(0, max);
                if (wordwise) {
                    var lastspace = value.lastIndexOf(' ');
                    if (lastspace != -1) {
                        value = value.substr(0, lastspace);
                    }
                }
                return value + (tail || ' …');
            };
        })

        /// persian mony for currency in tables
        .filter('monyCurrency', function () {
            return function (moneyText) {
                moneyText = String(moneyText);
                if (!moneyText) {
                    return '0';
                }
                var separator = ",";
                var int = moneyText.replace(new RegExp(separator, "g"), "");
                var regexp = new RegExp("\\B(\\d{3})(" + separator + "|$)");
                do
                {
                    int = int.replace(regexp, separator + "$1");
                } while (int.search(regexp) >= 0)
                return int + ' ريال';
            };
        })
        .filter('htmlToPlaintext', function () {
            return function (text) {
                return  text ? String(text).replace(/<[^>]+>/gm, '') : '';
            };
        }
        );
