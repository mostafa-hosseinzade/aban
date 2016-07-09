'use strict';


// Declare app level module which depends on filters, and services
angular.module('myApp', ['myApp.controllers', 'myApp.directives']).
        config(['$routeProvider', function ($routeProvider) {
                $routeProvider.when('/', {templateUrl: '../bundles/app/partials/login.html', controller: 'Login'});
                $routeProvider.when('/reset', {templateUrl: '../bundles/app/partials/resset.html', controller: 'resset'});
                $routeProvider.when('/check-mail/:email', {templateUrl: '../bundles/app/partials/check-mail.html', controller: 'checkMail'});
                $routeProvider.when('/reset/:token', {templateUrl: '../bundles/app/partials/resetform.html', controller: 'resetform'});
                $routeProvider.otherwise({redirectTo: '/'});
            }]);
/* Controllers */
angular.module('myApp.controllers', [])
        .controller('Login', ['$scope', '$http', '$window', function ($scope, $http, $window) {
                $scope.token = token;
                $scope.username = '';
                $scope.password = '';
                $scope.errorMsg;
                $scope.login = 'ورود';
                $scope.isLoading = 0;
                $scope.remember = false;
                $scope.onSubmit = function () {
                    $scope.isLoading = 1;
                    $scope.errorMsg = '';
                    $scope.login = '...';
                    $('#username , #password').removeClass('invalid');
                    if (!$scope.username || !$scope.username.length || $scope.username.length < 3) {
                        $('#username').addClass('invalid');
                        $scope.errorMsg = 'اطلاعات وارد شده نامعتبر است.';
                        return;
                    }
                    if (!$scope.password || !$scope.password.length || $scope.password.length < 3) {
                        $('#password').addClass('invalid');
                        $scope.errorMsg = 'اطلاعات وارد شده نامعتبر است.';
                        return;
                    }
                    var data = [];
                    data = {
                        "_csrf_token": $scope.token,
                        "_username": $scope.username,
                        "_password": $scope.password,
                        "_remember_me": $scope.remember
                    };
                    $http({
                        method: 'POST',
                        url: login_url,
                        headers: {'Content-Type': 'application/json;charset=utf-8;'},
                        data: data
                    }).success(function (response) {
                        $scope.username = '';
                        $scope.password = '';
                        if (response.success) {
                            $scope.errorMsg = 'ورود موفق: لطفا منتظر بمانید ...';
                            if (response.role == 'ROLE_USER') {
                                $window.location = BaseUrl + '/cli_panel/';
                            } else if (response.role == 'ROLE_DOCTOR') {
                                $window.location = BaseUrl + '/doc_panel/';
                            } else {
                                $window.location = BaseUrl + '/admin/';
                            }
                        } else {
                            $scope.errorMsg = response.message;
                            $scope.isLoading = 0;
                        }

                        $scope.login = 'ورود';
                    });
                };

            }])
        .controller('resset', ['$scope', '$http', '$window', function ($scope, $http, $window) {
                $scope.username = '';
                $scope.errorMsg;
                $scope.login = 'بازیابی کلمه عبور';
                $scope.isLoading = 0;
                $scope.onSubmit = function () {
                    $scope.isLoading = 1;
                    $scope.errorMsg = '';
                    $('[name=username]').removeClass('invalid');
                    if (!$scope.username || !$scope.username.length || $scope.username.length < 3) {
                        $('[name=username]').addClass('invalid');
                        $scope.errorMsg = 'اطلاعات وارد شده نامعتبر است.';
                        $scope.isLoading = 0;
                        return;
                    }
                    $scope.login = '...';
                    var data = [];
                    data = {
                        "username": $scope.username
                    };
                    $http({
                        method: 'POST',
                        url: resset_password,
                        headers: {'Content-Type': 'application/json;charset=utf-8;'},
                        data: data
                    }).success(function (response) {
                        $scope.username = '';
                        console.log(response);
                        if (response.invalid_username) {
                            $scope.errorMsg = response.message;
                            $scope.isLoading = 0;
                        } else if (response.request_duplicate) {
                            $scope.errorMsg = response.message;
                            $scope.isLoading = 0;
                        } else if (response.email) {
                            $window.location = '#/check-mail/' + response.email;
                        }
                        $scope.isLoading = 0;
                        $scope.login = 'بازیابی کلمه عبور';
                    });
                };

            }])
        .controller('checkMail', ['$scope', '$routeParams', function ($scope, $routeParams) {
                $scope.email = $routeParams.email;
            }])
        .controller('resetform', ['$scope', '$http', '$window', '$routeParams', function ($scope, $http, $window, $routeParams) {
                $scope.pw = '';
                $scope.secoundpassword = '';
                $scope.errorMsg;
                $scope.strong=0;
                $scope.login = 'بازیابی کلمه عبور';
                $scope.isLoading = 0;
                $scope.onSubmit = function () {
                    $scope.isLoading = 1;
                    $scope.errorMsg = '';
                    $('[name=username],[name=_password]').removeClass('invalid');
                    if (!$scope.pw || !$scope.pw.length || $scope.pw.length < 3) {
                        $('[name=pw]').addClass('invalid');
                        $scope.errorMsg = 'کلمه عبور را را وارد کنید.';
                        $scope.isLoading = 0;
                        return;
                    }
                    if (!$scope.secoundpassword || !$scope.secoundpassword.length || $scope.secoundpassword.length < 3) {
                        $('[name=_password]').addClass('invalid');
                        $scope.errorMsg = 'تکرار کلمه عبور را وارد کنید.';
                        $scope.isLoading = 0;
                        return;
                    }
                    if ($scope.secoundpassword !== $scope.pw) {
                        $('[name=_password]').addClass('invalid');
                        $scope.errorMsg = 'دو کلمه عبور یکسان نیست.';
                        $scope.isLoading = 0;
                        return;
                    }
                    console.log($scope.strong);
                    if ($scope.strong <=10) {
                        $('[name=pw]').addClass('invalid');
                        $scope.errorMsg = 'کلمه عبور انتخابی ضعیف است.';
                        $scope.isLoading = 0;
                        return;
                    }
                    $scope.login = '...';
                    var data = [];
                    data = {
                        "plainPassword": $scope.pw
                    };
                    $http({
                        method: 'POST',
                        url: BaseUrl + '/resetting/reset/' + $routeParams.token,
                        headers: {'Content-Type': 'application/json;charset=utf-8;'},
                        data: data
                    }).success(function (response) {
                        console.log(response);
                        if (response.role) {
                            $scope.errorMsg = response.message;
                                $window.location = BaseUrl + '/panel';
                        } else {
                            $scope.errorMsg = response.message;
                            $scope.isLoading = 0;
                        }
                        $scope.isLoading = 0;
                        $scope.login = 'بازیابی کلمه عبور';
                    });
                };

            }]);

angular.module('myApp.directives', [])
        .directive('appVersion', ['version', function (version) {
                return function (scope, elm, attrs) {
                    elm.text(version);
                };
            }])
        //show loading page for all ajax (angularJS) request
        .directive('loading', ['$http', function ($http)
            {
                return {
                    restrict: 'A',
                    link: function (scope, elm, attrs)
                    {
                        $(elm).find('img').attr('src', BaseUrl + '/bundles/public/img/loading.gif');
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
        .directive('checkStrength', function () {

            return {
                replace: false,
                restrict: 'EACM',
                link: function (scope, iElement, iAttrs) {

                    var strength = {
                        colors: ['#F00', '#F90', '#FF0', '#9F0', '#0F0'],
                        mesureStrength: function (p) {

                            var _force = 0;
                            var _regex = /[$-/:-?{-~!"^_`\[\]]/g;

                            var _lowerLetters = /[a-z]+/.test(p);
                            var _upperLetters = /[A-Z]+/.test(p);
                            var _numbers = /[0-9]+/.test(p);
                            var _symbols = _regex.test(p);

                            var _flags = [_lowerLetters, _upperLetters, _numbers, _symbols];
                            var _passedMatches = $.grep(_flags, function (el) {
                                return el === true;
                            }).length;

                            _force += 2 * p.length + ((p.length >= 10) ? 1 : 0);
                            _force += _passedMatches * 10;

                            // penality (short password)
                            _force = (p.length <= 6) ? Math.min(_force, 10) : _force;

                            // penality (poor variety of characters)
                            _force = (_passedMatches == 1) ? Math.min(_force, 10) : _force;
                            _force = (_passedMatches == 2) ? Math.min(_force, 20) : _force;
                            _force = (_passedMatches == 3) ? Math.min(_force, 40) : _force;

                            return _force;

                        },
                        getColor: function (s) {

                            var idx = 0;
                            if (s <= 10) {
                                idx = 0;
                            } else if (s <= 20) {
                                idx = 1;
                            } else if (s <= 30) {
                                idx = 2;
                            } else if (s <= 40) {
                                idx = 3;
                            } else {
                                idx = 4;
                            }

                            return {idx: idx + 1, col: this.colors[idx]};

                        }
                    };

                    scope.$watch(iAttrs.checkStrength, function () {
                        if (scope.pw === '') {
                            iElement.css({"display": "none"});
                        } else {
                            scope.strong=strength.mesureStrength(scope.pw);
                            var c = strength.getColor(scope.strong);
                            iElement.css({"display": "inline"});
                            iElement.children('li')
                                    .css({"background": "#DDD"})
                                    .slice(0, c.idx)
                                    .css({"background": c.col});
                        }
                    });
                },
                template: '<li class="point"></li><li class="point"></li><li class="point"></li><li class="point"></li><li class="point"></li>'
            };

        });