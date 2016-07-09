'use strict';
var app = angular.module('myApp.controllers', ['ngCookies']);
app.controller('ctg', ['$scope', '$http', '$location', '$cookieStore', '$routeParams', function ($scope, $http, $location, $cookieStore, $routeParams) {
        var url;
        $scope.isNull = 1;
        $scope.countBasket = 0;
        $scope.path = [];

        if ($routeParams.id) {
            url = BaseUrl + '' + $routeParams.id + '/ctg_list';
        } else {
            url = BaseUrl + '-1/ctg_list';
        }
        var checkCookie = $cookieStore.get('basket');
        var basket = [];
        if (checkCookie) {
            basket = JSON.parse($cookieStore.get('basket'));
            $scope.countBasket = basket.length;
        }
        $scope.ctg = [];
        $http.get(url).success(function (response) {
            $scope.ctg = response.data;
            if (!$scope.ctg.length || $scope.ctg.length == 0) {
                $scope.isNull = 0;
            }
            $scope.path = response.path;
        });
        $scope.checkSub = function (idCtg, Subctg, name) {
            if (Subctg) {
                $location.path('category/' + idCtg);
            } else {
                $location.path('products/' + idCtg);

            }

        };

    }]);

app.controller('product', ['$scope', '$http', '$window', '$routeParams', '$cookies', '$cookieStore', function ($scope, $http, $window, $routeParams, $cookies, $cookieStore) {


        $scope.product;
        $scope.isNull = 1;
        $scope.rating = 5;
        $scope.loading = false;
        $scope.imageList;
        $scope.commentList;
        $scope.message;
        $scope.mycomment = '';
        $scope.loginUser = isLogin;
        $scope.isreadOnly = false;
        if ($routeParams.show) {
            var checkCookie = $cookieStore.get('rate');
            var rate = {};
            if (checkCookie) {
                rate = JSON.parse($cookieStore.get('rate'));
                if ($.inArray($routeParams.id, rate)) {
                    $scope.isreadOnly = false;
                } else {
                    $scope.isreadOnly = isLogin;
                }
            } else {
                $scope.isreadOnly = isLogin;
            }
        }
        $scope.rateFunction = function (rating) {
            $scope.isreadOnly = false;
            var url = BaseUrl + 'rating/' + $routeParams.id + '/' + rating;
            $http.get(url).success(function (response) {
                var checkCookie = $cookieStore.get('rate');
                var rate = {};
                if (checkCookie) {
                    rate = JSON.parse($cookieStore.get('rate'));
                }
                rate[$routeParams.id] = $routeParams.id;
                console.log(rate);
                $cookieStore.put('rate', JSON.stringify(rate));
                $scope.rating = rating;
            });
        };
        $scope.showComment = function () {

            if (!$scope.commentList) {
                $scope.message = 'در حال بارگیری اطلاعات ...';
                var url = BaseUrl + 'comment_list/' + $routeParams.id;
                $http.get(url).success(function (response) {
                    $scope.commentList = response;
                    if (!response || response.length == 0) {
                        $scope.message = 'اطلاعاتی یافت نشد';
                    } else {
                        $scope.message = null;
                    }
                });
            }
        };

        $scope.showImage = function () {

            if (!$scope.imageList) {
                $scope.message = 'در حال بارگیری اطلاعات ...';
                var url = BaseUrl + 'image_list/' + $routeParams.id;
                $http.get(url).success(function (response) {
                    $scope.imageList = response;
                    if (!response || response.length == 0) {
                        $scope.message = 'اطلاعاتی یافت نشد';
                    } else {
                        $scope.message = null;
                    }
                });
            }
        };


        $scope.addCommentForProduct = function () {
            $scope.loading = true;
            if (isLogin) {
                if (!$scope.mycomment || !$scope.mycomment.length || $scope.mycomment.length < 5 || $scope.mycomment.length > 512) {
                    return;
                }
                var url = BaseUrl + 'add_comment_for_product';
                $http({
                    method: 'POST',
                    url: url,
                    headers: {'Content-Type': 'application/json;charset=utf-8;'},
                    data: {'comment': $scope.mycomment, 'id': $routeParams.id}
                }).then(function successCallback(response) {
                    $scope.mycomment = '';
                    $scope.commentList.splice(0, 0, response.data);
                    $scope.loading = false;
                }, function errorCallback(response) {
                    $scope.loading = false;
                    alert('خطایی موجود است');
                });
            }
        };
        $scope.countBasket = 0;
        $scope.removeEnable = [];
        $scope.pro;
        if ($routeParams.show) {
            if (!$scope.pro) {
                var url = BaseUrl + '' + $routeParams.id + '/pro';
                $http.get(url).success(function (response) {
                    $scope.pro = response;
                    $scope.rating = Math.round(response.rate);
                    var data = {
                        labels: response.labels,
                        datasets: [
                            {
                                label: "نمودار تعداد فروش ",
                                fillColor: "rgba(220,220,220,0.4)",
                                strokeColor: "rgba(220,220,220,1)",
                                pointColor: "rgba(220,220,220,1)",
                                pointStrokeColor: "#fff",
                                pointHighlightFill: "#fff",
                                pointHighlightStroke: "rgba(220,220,220,1)",
                                data: response.data
                            }
                        ]
                    };
                    var ctx = $("#line").get(0).getContext("2d");
                    var myLineChart = new Chart(ctx).Line(data, {
                        responsive: true, scaleFontColor: "#ff0000"
                    });
                    console.log(response.data)
                });
            }
        } else {
            var url = BaseUrl + '' + $routeParams.id + '/pro_list';
            $http.get(url).success(function (response) {
                $scope.product = response.data;
                if (!$scope.product.length || $scope.product.length == 0) {
                    $scope.isNull = 0;
                }
                $scope.path = response.path;
            });
        }
        var checkCookie = $cookieStore.get('basket');
        var basket = [];
        if (checkCookie) {
            basket = JSON.parse($cookieStore.get('basket'));
            var it;
            for (it in basket) {
                $scope.removeEnable[basket[it].i] = true;
            }
            $scope.countBasket = basket.length;
        }
        $scope.removeItem = function (id_product) {
            var checkCookie = $cookieStore.get('basket');
            var basket = [];
            if (checkCookie) {
                basket = JSON.parse($cookieStore.get('basket'));
                var it;
                for (it in basket) {
                    if (basket[it].i === id_product) {
                        basket.splice(it, 1);
                        $cookieStore.put('basket', JSON.stringify(basket));
                        $scope.removeEnable[id_product] = false;
                        $scope.countBasket--;
                        return;
                    }
                }
            }
        };
        $scope.onBasket = function (idFind_product_by_no, name, pricePro, product_no, $event) {
            var basket = [];
            var item = {'i': idFind_product_by_no, 'n': name, 'p': pricePro, 'pn': product_no, 'nu': 1};
            var checkCookie = $cookieStore.get('basket');
            if (checkCookie) {
                basket = JSON.parse($cookieStore.get('basket'));
            }
            var el = $($event.target).parents('.bx_clone');
            var el2 = $($event.target).parents('.bx');
            var ishas = true;
            if (basket.length !== 0) {
                var it;
                for (it in basket) {
                    if (basket[it].i === item.i) {
                        ishas = false;
                    }
                }
                ;
            }
            ;
            if (ishas) {
                basket.push(item);
                if (JSON.stringify(basket) && JSON.stringify(basket).length && JSON.stringify(basket).length < 1000) {
                    $cookieStore.put('basket', JSON.stringify(basket));
                    if ($(el).width()) {
                        var left = ($('#basket').offset().left - $(el).offset().left) - parseFloat($(el).width()) / 3;
                        var top = ($('#basket').offset().top - $(el).offset().top) - parseFloat($(el).height()) / 2;
                        var clone = $(el).clone();
                        $(el2).append(clone);
                        $(clone).animate({
                            transform: 'translate(' + left + 'px,' + top + 'px) scale(0)'
                        }, 'slow');
                    }
                    $scope.removeEnable[item.i] = true;
                    $scope.countBasket = basket.length;
                } else {
                    alert('سبد خرید شما تکمیل شده است ');
                }
            } else {
                alert('این محصول در سبد خرید شما وجود دارد.');
            }
        };
    }]);
app.controller('basket', ['$rootScope', '$scope', '$http', '$cookies', '$routeParams', '$location', '$cookieStore', function ($rootScope, $scope, $http, $cookies, $routeParams, $location, $cookieStore) {
        $scope.items = [];
        if ($cookieStore.get('basket')) {
            $scope.items = JSON.parse($cookieStore.get('basket'));
        }
        $scope.removeItem = function (index) {
            $scope.items.splice(index, 1);
            $cookieStore.put('basket', JSON.stringify($scope.items));
        };
        $scope.total = function () {
            var total = 0;
            angular.forEach($scope.items, function (item) {
                total += item.nu * item.p;
            });
            return total;
        };
        $scope.disableFactor = function () {
            if ($scope.items && $scope.items.length && $scope.items.length > 0) {
                return false;
            } else {
                return true;
            }
        };
        $scope.checkLogIn = function () {

            if (isLogin === 1) {
                $location.path('order');
            } else {
                $('#loginmodal').fadeIn();
            }
        };
        $scope.update = function (index) {
            $cookieStore.put('basket', JSON.stringify($scope.items));
        };
        $scope.isLoading = false;
        $scope.onCheckLogin = function () {

            $scope.errorMsg = '';
            $('#username , #password').removeClass('invalid');
            if (!$scope.username || !$scope.username.length || $scope.username.length === 0) {
                $('#username').addClass('invalid');
                $scope.errorMsg = 'نام کاربری را وارد کنید.';
                return;
            }
            if (!$scope.password || !$scope.password.length || $scope.password.length === 0) {
                $('#password').addClass('invalid');
                $scope.errorMsg = 'کلمه عبور را وارد کنید.';
                return;
            }
            $scope.isLoading = true;
            var data = [];
            data = {
                "_csrf_token": token,
                "_username": $scope.username,
                "_password": $scope.password,
                "_remember_me": $scope.remember_me
            };
            $http({
                method: 'post',
                url: login_url,
                data: data
            }).success(function (response) {
                if (response.role) {
                    $scope.errorMsg = response.message;
                    if (response.role) {
                        $location.path('order');
                    }
                } else {
                    $scope.errorMsg = response.message;
                    $scope.isLoading = false;
                }
            });
        };
    }]);
app.controller('order', ['$rootScope', '$scope', '$http', '$cookies', '$routeParams', '$cookieStore', function ($rootScope, $scope, $http, $cookies, $routeParams, $cookieStore) {
        $scope.user;
        $scope.taxation = 0.09;
        $scope.discount = 0.1;
        $scope.other = 10000;
        $scope.message;
        $scope.ref;
        $scope.isLoading = false;
        $scope.payment_success = false;
        $scope.payment_error;
        $http.get(BaseUrl + 'get_user').success(function (response) {
            if (response.valid_user) {
                $window.location = BaseUrl + '#/login';
            } else {
                $scope.user = response.user;
                console.log(response.user)
            }
        });
        $scope.items = [];
        if ($cookieStore.get('basket')) {
            $scope.items = JSON.parse($cookieStore.get('basket'));
        }
        $scope.total = function () {
            var total = 0;
            angular.forEach($scope.items, function (item) {
                total += item.nu * item.p;
            });
            return total;
        };
        $scope.totalFinal = function () {
            return $scope.total() * (1 + $scope.taxation - $scope.discount) + $scope.other;
        };
        $scope.payment = function () {
            $scope.isLoading = true;
            var data = [];
            if ($cookieStore.get('basket')) {
                data = JSON.parse($cookieStore.get('basket'));
                $http({
                    method: 'post',
                    url: BaseUrl + 'payment',
                    data: data
                }).success(function (response) {
                    $scope.payment_success = true;
                    if (response.error) {
                        $scope.message = response.message;
                        $scope.payment_error = true;
                    } else if (response.success) {
                        $scope.message = 'خرید موفق';
                        $scope.user.money = response.total;
                        $scope.ref = response.ref;
                        $scope.payment_error = false;
                        $cookieStore.remove('basket');
                    }
                });
            }
        };
    }]);

