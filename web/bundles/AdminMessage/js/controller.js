'use strict';
var app = angular.module('AdminMessage', ['ngResource', 'ngRoute', 'angular-loading-bar', 'ngAnimate']);


app.factory('getCountPagination', ['$http', '$resource', function($http, $resource, tokenHandler) {
        return {
            TableCount: function(url) {
                var countPosts = $resource(url);
                countPosts = tokenHandler.wrapActions(countPosts, ['get']);
                return countPosts;
            }
        };
    }]);
//
app.config(['$routeProvider', function($routeProvider) {
        $routeProvider.when('/', {templateUrl: baseURL + '../../bundles/AdminMessage/partials/index.html', controller: 'Index'});
        $routeProvider.when('/user/:id', {templateUrl: baseURL + '../../bundles/AdminMessage/partials/ShowUserInfo.html', controller: 'AnimaleEdit'});
    }]);
//
app.controller('Index', ['$scope', '$http', 'paginationCreateArray', '$timeout', 'getCountPagination', '$location', function($scope, $http, paginationCreateArray, $timeout, getCountPagination, $location) {
        $scope.constPageItems = 10;
        $scope.attr = 'id';
        $scope.asc = 'asc';
        $scope.current = 1;
        var count = 0;
        //init pagination value
        var orderBy = '';
        var field = '';
        var current = 1;
        var constPageItems = 10;
        var allPage = 0;
        var row = new Array();
//        $scope.offset = 0;
        $scope.filter = -1;
        //First Query For Show Info
        ///offset/0/attr/id/order/asc/search/-1/limit/10
        var route = baseURL + "offset/0/attr/" + $scope.attr + "/order/" + $scope.asc + "/search/" + $scope.filter + "/limits/" + $scope.constPageItems;
        $http.get(route).success(function(response) {
            if (response.data.msg != undefined) {
                var msg = response.msg.split(';');
                console.log(msg);
                NotifyCation(msg[0], msg[1], msg[2], msg[3]);
            } else {
                $scope.data = response.data;
                count = response.count;
                $scope.count = response.count;
                $scope.Allpaginate = paginationCreateArray.array(row, count, constPageItems, current);
            }
        });


        $scope.filter = -1;
        $scope.RefreshPagination = function() {
            //alert(parameterUrl);
            console.log($scope.count);
            allPage = Math.floor($scope.count / constPageItems); //Math.floor(CountItems / constPageItems);
            $scope.Allpaginate = paginationCreateArray.array(row, count, constPageItems, current);
            //function paginate
            $scope.paginate = function(offset) {
                if (orderBy === '') {
                    orderBy = "asc";
                }

                if (field === '') {
                    field = 'id';
                }

                current = offset;
                offset = offset * constPageItems - constPageItems;

                var parameterurlPagination = "";
                //baseURL + "offset/0/attr/" + $scope.attr + "/order/" + $scope.asc + "/search/"+$scope.filter+"/limits/" + $scope.constPageItems                                          
                parameterurlPagination = baseURL + "offset/" + offset + "/attr/" + field + "/order/" + orderBy + "/search/" + $scope.filter + "/limits/" + constPageItems;

                $http.get(parameterurlPagination).success(function(response) {
                    $scope.RefreshPagination();
                    $scope.data = response.data;

                });
            };

            //next offset
            $scope.pageNext = function() {
                $scope.paginate(current + 1);
            };

            //preview offset
            $scope.pagePreview = function() {
                if (current > 1) {
                    $scope.paginate(current - 1);
                }
            };

            //for print ...
            $scope.checkZero = function(x) {
                if (x == 0)
                {
                    return false;
                } else {
                    return true;
                }
            };

            //checking Visible Next Button
            $scope.checkVisibleNext = function() {

                if ((current) > allPage - 1)
                {
                    return false;
                }
                return true;
            };

            //checking Visible prev Button
            $scope.checkVisiblePrev = function() {

                if (current > 1)
                {
                    return true;
                }
                return false;
            };

            // Sort Order By desc
            $scope.sortDesc = function(field_in) {
                field = field_in;
                orderBy = "desc";
                $scope.desc = true;
                $scope.asc = false;
                var offset = current * constPageItems - constPageItems;

                var parameterurlPagination = "";
                if (typeof parameterRoot == "undefined")
                {
                    parameterurlPagination = BaseUrl + "/api/comments/-1/filters/" + offset + "/offsets/" + constPageItems + "/limits/" + field + "/orders/" + orderBy + ".json";
                } else
                {   //api/comments/{filter}/filters/{offset}/offsets/{limit}/limits/{attr}/orders/{asc}/posts/{itemGroup}.{_format} 
                    parameterurlPagination = BaseUrl + "/api/comments/-1/filters/" + offset + "/offsets/" + constPageItems + "/limits/" + field + "/orders/" + orderBy + "/posts/" + parameterRoot + ".json";
                }


                $http.get(parameterurlPagination,
                        {headers: {'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)}}).success(function(response) {
                    $scope.PostsShow = response;
                });
                current = $scope.CountPaginate;
                $scope.RefreshPagination();
                $('.paginate').last().click();
            };

            //Sort Order By Asc
            $scope.sortAsc = function(field_in) {
                field = field_in;
                orderBy = "asc";
                var offset = current * constPageItems - constPageItems;
                $scope.asc = true;
                $scope.desc = false;

                var parameterurlPagination = "";
                if (typeof parameterRoot == "undefined")
                {
                    parameterurlPagination = BaseUrl + "/api/comments/-1/filters/" + offset + "/offsets/" + constPageItems + "/limits/" + field + "/orders/" + orderBy + ".json";
                } else
                {   //api/comments/{filter}/filters/{offset}/offsets/{limit}/limits/{attr}/orders/{asc}/posts/{itemGroup}.{_format} 
                    parameterurlPagination = BaseUrl + "/api/comments/-1/filters/" + offset + "/offsets/" + constPageItems + "/limits/" + field + "/orders/" + orderBy + "/posts/" + parameterRoot + ".json";
                }

                $http.get(parameterurlPagination,
                        {headers: {'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)}}).success(function(response) {
                    $scope.content = response;
                    current = 1;
                    $scope.RefreshPagination();
                    $('.paginate').first().click();
                });
            };

        };

        //call Pagination for first
        $scope.RefreshPagination();


        $scope.UserAll = true;
        $scope.Selected;
        $scope.Msg = {};
        $scope.AddUser = {};
        $scope.SelectedUser;

        //Selected User
        $scope.SelectedItems = function(item) {
            $scope.Selected = item;
            $scope.SelectedUser = angular.copy(item);
        };

        $scope.getCssClass = function(value) {
            return "active";
        };

        $scope.reply = function(user_id) {
            $scope.user_id = user_id;
            $scope.AddInfoMessage = true;
            $scope.ShowInfoMessage = false;
        };

        //Show Info User And Animals User
        $scope.Show = function() {
            $scope.AddInfoMessage = false;
            $scope.ShowInfoMessage = true;
            $('#AddMessage').modal();
        };

        /////////////////////////
        // PrePare Function
        /////////////////////////

        $scope.prepareCreate = function() {
            $scope.user_id = false;
            $scope.AddInfoMessage = true;
            $scope.ShowInfoMessage = false;
            $scope.AddItem = {};
            $scope.AddItem.message = '';
            $http.get(baseURL + "UserAll").success(function(response) {
                if (response.msg != undefined) {
                    var msg = response.msg.split(";");
                    NotifyCation(msg[0], msg[1], msg[2], msg[3]);
                } else {
                    $scope.UserAllData = response.data;
                }
            }).error(function() {
                NotifyCation('پیام ها ', 'مشکل در برقراری ارتباط با سرور', 'error', true);
            });
            $('#AddMessage').modal();
        };

        $scope.prepareDelete = function() {
            console.log($scope.Selected);
            $('#DeleteMessage').modal();
        };
        /////////////////////////
        // End PrePare Functions
        /////////////////////////

        ////////////////////////
        // Submit Function
        ////////////////////////

        $scope.CreateMessage = function() {
            console.log($scope.AddItem);
            if ($scope.user_id != false) {
                $scope.AddItem.user_id = $scope.user_id;
                $scope.AddItem.AllUserSend = false;
            }
            console.log($scope.AddItem);
            if (($scope.AddItem.user_id == undefined && $scope.AddItem.AllUserSend == undefined) || ($scope.AddItem.user_id == 0 && $scope.AddItem.AllUserSend == undefined)) {
                NotifyCation('افزودن پیام ', 'لطفا کاربر را انتخاب نمائید', 'error', true);
            } else {
                $http({
                    method: 'post',
                    url: baseURL + 'AddMessage',
                    data: $scope.AddItem
                }).success(function(response) {
                    var msg = response.msg.split(';');
                    console.log(msg);
                    NotifyCation(msg[0], msg[1], msg[2], msg[3]);
                    $scope.paginate($scope.current);
                    $('#AddMessage').modal('hide');
                }).error(function(response) {
                    NotifyCation('افزودن پیام ', 'مشکل در برقراری ارتباط با سرور', 'error', true);
                });
            }
        };

        var filterTextTimeout;
        $scope.search = function() {
            $scope.filter = $scope.searchText;
            if ($scope.filter == '') {
                $scope.filter = -1;
            }
            var route = baseURL + "users/filters/" + $scope.filter + "/orders/" + $scope.attr + "/bies/" + $scope.asc + "/offsets/0/limits/" + $scope.constPageItems;
            if (filterTextTimeout) {
                clearTimeout(filterTextTimeout);
            }
            filterTextTimeout = setTimeout(function() {
                $http.get(route).success(function(response) {
                    console.log(response);
                    if (response.msg != undefined) {
                        var msg = response.msg.split(';');
                        console.log(msg);
                        NotifyCation(msg[0], msg[1], msg[2], msg[3]);
                    } else {
                        $scope.data = response.user;
                        $scope.count = response.count;
                        $scope.allPage = Math.floor($scope.count / $scope.constPageItems);
                        if ($scope.count % $scope.constPageItems > 0 && $scope.count > $scope.constPageItems) {
                            $scope.allPage++;
                        }
                        $scope.Allpaginate = paginationCreateArray.array($scope.allPage, $scope.current);
                    }
                }).error(function(r) {
                    NotifyCation('جستجوی کاربران', 'مشکل در برقراری ارتباط با سرور با پشتیبانی تماس بگیرید', 'error', true);
                });
            }, 2000);
        };

        $scope.SendDeleteMessage = function() {
            $http.get(baseURL + 'DeleteMessage/' + $scope.Selected.id).success(function(response) {
                var msg = response.msg.split(';');
                $('#DeleteMessage').modal('hide');
                $('#' + $scope.Selected.id).hide();
                NotifyCation(msg[0], msg[1], msg[2], msg[3]);

            });
        };

        ////////////////////////////
        // End Submit Function
        ////////////////////////////

    }]);




