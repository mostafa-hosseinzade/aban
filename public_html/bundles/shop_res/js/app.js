'use strict';
// Declare app level module which depends on filters, and services
angular.module('myApp', ['myApp.filters', 'myApp.directives', 'myApp.controllers']).
        config(['$routeProvider', function ($routeProvider) {
                $routeProvider.when('/products/:id', {templateUrl: '../bundles/shop_res/partials/product.html', controller: 'product'});
                $routeProvider.when('/', {templateUrl: '../bundles/shop_res/partials/ctg.html', controller: 'ctg'});
                $routeProvider.when('/category/:id', {templateUrl: '../bundles/shop_res/partials/ctg.html', controller: 'ctg'});
                $routeProvider.when('/basket', {templateUrl: '../bundles/shop_res/partials/basket.html', controller: 'basket'});
                $routeProvider.when('/login', {templateUrl: '../bundles/shop_res/partials/logIn.html', controller: 'login'});
                $routeProvider.when('/order', {templateUrl: '../bundles/shop_res/partials/order.html', controller: 'order'});
                $routeProvider.when('/products/:show/:id', {templateUrl: '../bundles/shop_res/partials/descriptionProduct.html', controller: 'product'});
            }]);

angular.module('myApp.filters', []).
        filter('interpolate', ['version', function (version) {
                return function (text) {
                    return String(text).replace(/\%VERSION\%/mg, version);
                }
            }])
        .filter('Rial', function () {
            return function (dateString) {
                return dateString + 'ریال';
            };
        })
        .filter('ConvertedToDateShamsi', function () {
            return function (dateString) {
                if (dateString != null)
                {
                    return moment(dateString, 'YYYY-M-D HH:mm:ss').format('jYYYY/jM/jD');
                } else {
                    return "بدون تاریخ";
                }
            };
        }).filter('nameNull', function () {
    return function (dateString) {
        if (dateString != null)
        {
            return dateString;
        } else {
            return "بی نام";
        }
    };
}).filter('html', function ($sce) {
    return function (input) {
        return $sce.trustAsHtml(input);
    }
})