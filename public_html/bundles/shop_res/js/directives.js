'use strict';
angular.module('myApp.directives', [])
        .directive('appVersion', ['version', function (version) {
                return function (scope) {
                };
            }])
        .directive('loading', ['$http', function ($http)
            {
                return {
                    restrict: 'A',
                    link: function (scope, elm, attrs)
                    {
                        $(elm).find('img').attr('src', '/bundles/public/img/loading.gif');
                        scope.isLoading = function () {
                            return $http.pendingRequests.length > 0;
                        };
                        scope.$watch(scope.isLoading, function (v)
                        {
                            if (v) {
                                elm.show();
                            } else {
                                elm.hide();
                            }
                        });
                    }
                };
            }])
        .directive('starRating',
                function () {
                    return {
                        restrict: 'A',
                        template: '<ul class="rating">'
                                + '	<li ng-repeat="star in stars" ng-class="star" ng-click="toggle($index)">'
                                + '\u2605'
                                + '</li>'
                                + '</ul>',
                        scope: {
                            ratingValue: '=',
                            max: '=',
                            readOnly: '=',
                            onRatingSelected: '&'
                        },
                        link: function (scope, elem, attrs) {
                            var updateStars = function () {
                                scope.stars = [];
                                for (var i = 0; i < scope.max; i++) {
                                    scope.stars.push({
                                        filled: i < scope.ratingValue
                                    });
                                }
                            };

                            scope.toggle = function (index) {
                                if (scope.readOnly) {
                                    scope.ratingValue = index + 1;
                                    scope.onRatingSelected({
                                        rating: index + 1
                                    });
                                }
                            };

                            scope.$watch('ratingValue',
                                    function (oldVal, newVal) {
                                        if (newVal) {
                                            updateStars();
                                        }
                                    }
                            );
                        }
                    };
                }
        );