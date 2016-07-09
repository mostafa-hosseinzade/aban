'use strict';

/* Directives */


angular.module('myApp.directives', []).
        directive('appVersion', ['version', function (version) {
                return function (scope, elm, attrs) {
                    elm.text(version);
                };
            }])
        .directive('user', function () {
            return {
                restrict: 'E',
                scope: false,
                templateUrl: BaseUrl + 'bundles/clientBlog/partials/client/MicroChip/user.html'
            }
        }).directive('animal',function (){
            return {
                restrict: 'E',
                scope: false,
                templateUrl: BaseUrl + 'bundles/clientBlog/partials/client/MicroChip/animal.html'
            }            
        }).directive('result',function(){
            return {
                restrict: 'E',
                scope: false,
                templateUrl: BaseUrl + 'bundles/clientBlog/partials/client/MicroChip/result.html'
            }            
        })

