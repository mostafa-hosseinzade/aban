'use strict';


// Declare app level module which depends on filters, and services
angular.module('appLeft', ['appLeft.filters', 'appLeft.controllers', 'appLeft.directives']);

//
/* Controllers */
angular.module('appLeft.controllers', [])
        .controller('microchip', ['$scope', '$http', '$location', function ($scope, $http, $location) {
                $scope.animal;
                $scope.animalId;
                $scope.notFind;
                $scope.islogin = false;
                $scope.isloading = false;
                $scope.search = 'جست و جو';
                $scope.animalSearch = function (event) {
                    $scope.notFind = false;
                    $(event.target).prev().removeClass('invalid');
                    if (!$scope.animalId || $scope.animalId.length != 15) {
                        $(event.target).prev().addClass('invalid');
                        return;
                    }
                    $(event.target).addClass('searchloadin');
                    $scope.isloading = true;
                    $scope.search = '...';
                    $http.get('/micro_search/' + $scope.animalId).success(function (res) {
                        if (res.status) {
                            $scope.animal = res.data;
                        } else {
                            $scope.notFind = true;
                        }
                        $scope.search = 'جست و جو';
                        $scope.isloading = false;
                        $(event.target).removeClass('searchloadin');
                    });
                };
                $scope.searchAgain = function () {
                    $scope.notFind = false;
                    $scope.animal = null;
                    $scope.animalId = null;
                };
                $scope.addAnimals = function () {
                    if (isLogin) {
                        window.location = '/panel';
                    } else {
                        window.location = '/partial#/MicroChip';
                    }
                };
            }])
        .controller('search', ['$scope', '$http', '$location', function ($scope, $http, $location) {
                $scope.notFind;
                $scope.searchword;
                $scope.ctg = 1;
                $scope.offset = 0;
                $scope.hasSearch = false;
                $scope.resultSearch;
                $scope.isloading = false;
                $scope.search = 'جست و جو';
                $scope.onSearch = function () {
                    $scope.notFind = false;
                    if (!$scope.searchword || $scope.searchword.length < 3) {
                        return false;
                    }
                    if ($scope.searchword.includes('گمانه') || $scope.searchword.toLowerCase().includes('gomaneh') || $scope.searchword.toLowerCase().includes('\'lhki') || $scope.searchword.includes('لخئشدثا')) {
                        $('.gomaneh').css('display', 'inline-block');
                        $('.logo_gomaneh').addClass('gomaneh_rotate');
                        return false;
                    }
                    $http.get('/search/' + $scope.ctg + "/" + $scope.searchword + "/" + $scope.offset).success(function (res) {
                        $scope.hasSearch = true;
                        $scope.resultSearch = res;
                        console.log($scope.resultSearch);
                    });
                };
            }])
        .controller('Register', ['$scope', '$http', '$window', function ($scope, $http, $window) {
                $scope.user = {};
                $scope.repw = '';
                $scope.reset;
                $scope.pw = '';
                $scope.success;
                $scope.errorMsg;
                $scope.login = 'ثبت نام';
                $scope.isLoading = 0;
                $scope.onSubmit = function (event) {
                    $scope.errorMsg = '';
                    $(event.target).closest('form').find('*').removeClass('invalid');
                    if (!$scope.user.name || !$scope.user.name.length || $scope.user.name.length < 3) {
                        $(event.target).closest('form').find('#name').addClass('invalid');
                        $scope.errorMsg = 'اطلاعات وارد شده نامعتبر است.';
                        return;
                    }
                    if (!$scope.user.family || !$scope.user.family.length || $scope.user.family.length < 3) {
                        $(event.target).closest('form').find('#family').addClass('invalid');
                        $scope.errorMsg = 'اطلاعات وارد شده نامعتبر است.';
                        return;
                    }
                    if (!$scope.user.sex || !$scope.user.sex.length) {
                        $(event.target).closest('form').find('#sex').addClass('invalid');
                        $scope.errorMsg = 'اطلاعات وارد شده نامعتبر است.';
                        return;
                    }
                    if (!$scope.user.email || !$scope.user.email.length || !validateEmail($scope.user.email)) {
                        $(event.target).closest('form').find('#email').addClass('invalid');
                        $scope.errorMsg = 'اطلاعات وارد شده نامعتبر است.';
                        return;
                    }
                    if (!$scope.user.mobile || !$scope.user.mobile.length || !isValidPhone($scope.user.mobile)) {
                        $(event.target).closest('form').find('#mobile').addClass('invalid');
                        $scope.errorMsg = 'اطلاعات وارد شده نامعتبر است.';
                        return;
                    }
                    if (!$scope.pw || !$scope.repw || !$scope.pw.length || !$scope.repw.length || $scope.pw.length < 8) {
                        $(event.target).closest('form').find('#repassword , #password').addClass('invalid');
                        $scope.errorMsg = 'اطلاعات وارد شده نامعتبر است.';
                        return;
                    } else {
                        if ($scope.pw != $scope.repw) {
                            $(event.target).closest('form').find('#repassword').addClass('invalid');
                            $scope.errorMsg = 'دو کلمه عبور وارد شده یکسان نیستند.';
                            return;
                        }
                        $scope.user.plainPassword = $scope.pw;
                    }
                    if (!$scope.user.address || !$scope.user.address.length) {
                        $scope.user.address = '';
                    }
                    $scope.isLoading = 1;
                    $scope.login = '...';
                    console.log($scope.user)
                    $http({
                        method: 'POST',
                        url: registration,
                        headers: {'Content-Type': 'application/json;charset=utf-8;'},
                        data: {'user': $scope.user}
                    }).success(function (response) {
                        $scope.user = null;
                        if (response.success) {
                            $scope.errorMsg = response.message;
                            $scope.success = true;
                            $scope.reset = false;
                        } else {
                            if (response.type) {
                                $scope.reset = true;
                            } else {
                                $scope.reset = false;
                            }
                            $scope.errorMsg = response.message;
                            $scope.isLoading = 0;
                        }
                        $scope.login = 'ثبت نام';
                    });
                };
                function validateEmail(email) {
                    var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                    return re.test(email);
                }
                function isValidPhone(phone) {
                    var p = /^[0]?\d{10}$/i;
                    return p.test(phone);
                }
                $scope.reseting = function () {
                    $window.location = '/login#/reset';
                };
            }])
        .controller('Login', ['$scope', '$http', '$window', function ($scope, $http, $window) {
                $scope.token = token;
                $scope.username = '';
                $scope.password = '';
                $scope.errorMsg;
                $scope.login = 'ورود';
                $scope.isLoading = 0;
                $scope.remember = false;
                $scope.onSubmit = function (event) {
                    $scope.errorMsg = '';
                    $(event.target).closest('form').find('#username , #password').removeClass('invalid');
                    //$('#username , #password').removeClass('invalid');
                    if (!$scope.username || !$scope.username.length || $scope.username.length < 3) {
                        $(event.target).closest('form').find('#username').addClass('invalid');
                        $scope.errorMsg = 'اطلاعات وارد شده نامعتبر است.';
                        return;
                    }
                    if (!$scope.password || !$scope.password.length || $scope.password.length < 3) {
                        $(event.target).closest('form').find('#password').addClass('invalid');
                        $scope.errorMsg = 'اطلاعات وارد شده نامعتبر است.';
                        return;
                    }
                    $scope.isLoading = 1;
                    $scope.login = '...';
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
                            $window.location = '/panel';

                        } else {
                            $scope.errorMsg = response.message;
                            $scope.isLoading = 0;
                        }

                        $scope.login = 'ورود';
                    });
                };
                $scope.reseting = function () {
                    $window.location = '/login#/reset';
                };
            }]).controller('DownloadApp', ['$http', '$scope', '$compile', function ($http, $scope, $compile) {
        $scope.AppIos = function () {
            window.location = "https://itunes.apple.com/us/app/abanpet/id1116486590?ls=1&mt=8";
        }
        $scope.AppAnd = function () {
            window.location = "https://cafebazaar.ir/app/com.gomaneh.abanpetclinic/?l=fa";
        }
    }]);
angular.module('appLeft.directives', [])
        .directive('leftpage', ['$http', '$compile', function ($http, $compile)
            {
                return {
                    restrict: 'A',
                    required: 'ngClick',
                    scope: false,
                    controller: 'search',
                    link: function ($scope, elm, attrs)
                    {
                        $http.get('/bundles/clientBlog/partials/search.html').success(function (res) {
                            elm.html($compile(res)($scope));
                        });
                        $(document.getElementById("reg2")).on('click', function (e) {

                            $(document.getElementById("#reg")).click(e);


                            var elment = document.getElementById("loginpanel");
                            console.log(elment.classList);
                            console.log(elment.classList);
                            $http.get('/bundles/clientBlog/partials/reg.html').success(function (res) {
                                elm.html($compile(res)($scope));
                            });
                        });
                        $('.microchip').click(function (e) {
                            $http.get('/bundles/clientBlog/partials/micro.html').success(function (res) {
                                elm.html($compile(res)($scope));
                            });
                        });
                        $('#FullAddress').click(function (e) {
                            e.preventDefault();
                            $('.wrapper').addClass('openmicrochip');
                            $('.leftpanel').addClass('showleftpanel');
                            elm.html($compile($('#FullAddressContent').html())($scope));
                        });
                        $('#searchform').click(function (e) {
                            e.preventDefault();
                            var $val = $(this).find('.textbox').val();

                            if ($val && $val.length && $val.length > 2) {
                                $('.wrapper').addClass('openmicrochip');
                                $('.leftpanel').addClass('showleftpanel');

                                $http.get('/bundles/clientBlog/partials/search.html').success(function (res) {
                                    elm.html($compile(res)($scope));
                                    $scope.searchword = $val;
                                    $scope.onSearch();
                                });
                            }
                        });
                        $scope.gotoLogin = function () {
                            $http.get('/bundles/clientBlog/partials/login.html').success(function (res) {
                                elm.html($compile(res)($scope));
                            });
                        };
                        $scope.gotoReg = function () {
                            $http.get('/bundles/clientBlog/partials/reg.html').success(function (res) {
                                elm.html($compile(res)($scope));
                            });
                        };
                        $scope.gotoDow = function () {
                            $http.get('/bundles/clientBlog/partials/App.html').success(function (res) {
                                elm.html($compile(res)($scope));
                            });
                        };
                        $scope.searchpanel = function () {
                            $('.wrapper').addClass('openmicrochip');
                            $('.leftpanel').addClass('showleftpanel');
                            $http.get('/bundles/clientBlog/partials/search.html').success(function (res) {
                                elm.html($compile(res)($scope));
                            });
                        };
                    }
                };
            }])
        .directive('angularMask', function () {
            return {
                restrict: 'A',
                require: 'ngModel',
                link: function ($scope, el, attrs, model) {
                    var format = attrs.angularMask,
                            arrFormat = format.split('|');

                    if (arrFormat.length > 1) {
                        arrFormat.sort(function (a, b) {
                            return a.length - b.length;
                        });
                    }

                    model.$formatters.push(function (value) {
                        return value === null ? '' : mask(String(value).replace(/\D/g, ''));
                    });

                    model.$parsers.push(function (value) {
                        model.$viewValue = mask(value);
                        var modelValue = String(value).replace(/\D/g, '');
                        el.val(model.$viewValue);
                        return modelValue;
                    });

                    function mask(val) {
                        if (val === null) {
                            return '';
                        }
                        var value = String(val).replace(/\D/g, '');
                        if (arrFormat.length > 1) {
                            for (var a in arrFormat) {
                                if (value.replace(/\D/g, '').length <= arrFormat[a].replace(/\D/g, '').length) {
                                    format = arrFormat[a];
                                    break;
                                }
                            }
                        }
                        var newValue = '';
                        for (var nmI = 0, mI = 0; mI < format.length; ) {
                            if (format[mI].match(/\D/)) {
                                newValue += format[mI];
                            } else {
                                if (value[nmI] != undefined) {
                                    newValue += value[nmI];
                                    nmI++;
                                } else {
                                    break;
                                }
                            }
                            mI++;
                        }
                        return newValue;
                    }
                }
            };
        })
        .directive('qrcode', function () {
            return {
                template: '<div style="width:128px;float:left;magin:5px"></div>',
                restrict: 'E',
                replace: true,
                scope: false,
                link: function ($scope, el)
                {
                    $scope.$watch("animal", function (v)
                    {
                        if ($scope.animal) {
                            var d = $scope.animal;
                            el.html('<div id="qrcode"></div>');
                            console.log($(el).find("#qrcode"));
                            var text = 'Animal Id:' + $scope.animalId + 'Name:' + d.name + "-Species:" + d.sp + "-Breed:" + d.br + "-Sex:" + (d.sex ? 'Female' : 'Male') + "Color:" + d.color;
                            new QRCode(document.getElementById("qrcode"), {
                                text: text,
                                width: 128,
                                height: 128,
                                colorDark: "#000000",
                                colorLight: "#ffffff",
                                correctLevel: QRCode.CorrectLevel.H
                            });
                        }
                    });
                }
            };
        })
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
                            scope.strong = strength.mesureStrength(scope.pw);
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

angular.module('appLeft.filters', [])
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
        .filter('htmlToPlaintext', function () {
            return function (text) {
                return  text ? String(text).replace(/<[^>]+>/gm, '') : '';
            };
        }
        )
angular.bootstrap(document.getElementById("appLeft"), ['appLeft']);
