'use strict';
// Declare app level module which depends on filters, and services
angular.module('myApp', ['myApp.directives', 'myApp.controllers', 'infinite-scroll']).
        config(['$routeProvider', function ($routeProvider) {

                $routeProvider.when('/product', {templateUrl: '../bundles/shop_res/admin/partials/product_admin.html', controller: 'product'});
                $routeProvider.when('/category', {templateUrl: '../bundles/shop_res/admin/partials/category_admin.html', controller: 'category'});
            }]);
//angular.module('myApp').filter('pagination', function()
//{
//  return function(input, start) {
//    start = parseInt(start, 10);
//    return input.slice(start);
//  };
//});
