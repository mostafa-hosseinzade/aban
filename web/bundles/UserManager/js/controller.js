'use strict';
var app = angular.module('UserManager', ['ngResource', 'ngRoute', 'angular-loading-bar', 'ngAnimate']);


app.factory('getCountPagination', ['$http', '$resource', function ($http, $resource, tokenHandler) {
        return {
            TableCount: function (url) {
                var countPosts = $resource(url);
                countPosts = tokenHandler.wrapActions(countPosts, ['get']);
                return countPosts;
            }
        };
    }]);

var user;
var userAll;
var userCount;
app.config(['$routeProvider', function ($routeProvider) {
        $routeProvider.when('/', {templateUrl: baseURL + '../../bundles/UserManager/partials/index.html', controller: 'Index'});
        $routeProvider.when('/user/:id', {templateUrl: baseURL + '../../bundles/UserManager/partials/ShowUserInfo.html', controller: 'AnimaleEdit'});
    }]);

app.controller('Index', ['$scope', '$http', 'paginationCreateArray', '$timeout', 'getCountPagination', '$location', function ($scope, $http, paginationCreateArray, $timeout, getCountPagination, $location) {
        $scope.constPageItems = 10;
        $scope.attr = 'id';
        $scope.asc = 'asc';
        $scope.current = 1;
        //init pagination value
        var current = 1;
        var constPageItems = 10;
        var allPage = 0;
        var row = new Array();
        $scope.filter = $scope.searchText;
        if (userAll == undefined) {
            //First Query For Show Info
            var route = baseURL + "users/filters/-1/orders/" + $scope.attr + "/bies/" + $scope.asc + "/offsets/0/limits/" + $scope.constPageItems;
            $http.get(route).success(function (response) {
                if (response.user.msg != undefined) {
                    var msg = response.msg.split(';');
                    console.log(msg);
                    NotifyCation(msg[0], msg[1], msg[2], msg[3]);
                } else {
                    $scope.data = response.user;
                    userAll = response.user;
                    userCount = response.count;
                    $scope.count = response.count;
                    //call Pagination for first
                    $scope.RefreshPagination();
                }
            });
        } else {
            $scope.data = userAll;
            $scope.count = userCount;
        }
        //variable for pagination comments
        var count, orderBy, field;
        orderBy = '';
        field = '';

        $scope.RefreshPagination = function () {
            allPage = Math.floor($scope.count / constPageItems); //Math.floor(CountItems / constPageItems);
            $scope.Allpaginate = paginationCreateArray.array(row, $scope.count, constPageItems, current);
            //function paginate
            $scope.paginate = function (offset) {

                if (orderBy === '') {
                    orderBy = "asc";
                }

                if (field === '') {
                    field = 'id';
                }

                current = offset;
                offset = offset * constPageItems - constPageItems;

                var parameterurlPagination = "";

                parameterurlPagination = baseURL + "users/filters/-1/orders/" + $scope.attr + "/bies/" + $scope.asc + "/offsets/" + offset + "/limits/" + constPageItems;


                $http.get(parameterurlPagination).success(function (response) {

                    $scope.data = response.user;
                    userAll = response.user;
                    userCount = response.count;
                    $scope.count = response.count;
                    $scope.RefreshPagination();
                });
            };

            //next offset
            $scope.pageNext = function () {
                $scope.paginate(current + 1);
            };

            //preview offset
            $scope.pagePreview = function () {
                if (current > 1) {
                    $scope.paginate(current - 1);
                }
            };

            //for print ...
            $scope.checkZero = function (x) {
                if (x == 0)
                {
                    return false;
                } else {
                    return true;
                }
            };

            //checking Visible Next Button
            $scope.checkVisibleNext = function () {

                if ((current) > allPage - 1)
                {
                    return false;
                }
                return true;
            };

            //checking Visible prev Button
            $scope.checkVisiblePrev = function () {
                if (current > 1)
                {
                    return true;
                }
                return false;
            };

            // Sort Order By desc
            $scope.sortDesc = function (field_in) {
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
                        {headers: {'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)}}).success(function (response) {
                    $scope.PostsShow = response;
                });
                current = $scope.CountPaginate;
                $scope.RefreshPagination();
                $('.paginate').last().click();
            };

            //Sort Order By Asc
            $scope.sortAsc = function (field_in) {
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
                        {headers: {'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)}}).success(function (response) {
                    $scope.content = response;
                    current = 1;
                    $scope.RefreshPagination();
                    $('.paginate').first().click();
                });
            };
        };
        $scope.RefreshPagination();

        $scope.UserAll = true;
        $scope.Selected;
        $scope.Msg = {};
        $scope.AddUser = {};
        $scope.SelectedUser;

        //Selected User
        $scope.SelectedItems = function (item) {
            user = item;
            $scope.Selected = item;
            $scope.SelectedUser = angular.copy(item);
        };

        //Show Info User And Animals User
        var IdUserAnimale = 0;
        $scope.Show = function (item) {
            $scope.Selected = item;
            $scope.UserAll = false;
            $scope.ShowUserInfoT = true;

            if ($scope.Selected.photo == undefined) {
                $scope.Selected.photo = "data:image/png;base64,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";
            }
            IdUserAnimale = item.id;
            $location.path('/user/' + item.id);
        };
        //Show All User
        $scope.CloseUserInfo = function () {
            $scope.ShowItem = '';
            $scope.UserAll = true;
            $scope.ShowUserInfoT = false;
        };

        /////////////////////////
        // PrePare Function
        /////////////////////////
        $scope.prepareEdit = function () {
            $scope.EditUserInfo = true;
            $scope.AddUserInfo = false;
            if ($scope.Selected.photo == undefined) {
                $scope.Selected.photo = "data:image/png;base64,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";
            }
            $('#EditUser').modal();
        };

        $scope.prepareCreate = function () {
            $scope.EditUserInfo = false;
            $scope.AddUserInfo = true;
            $scope.Selected = {};
            if ($scope.Selected.photo == undefined) {
                $scope.Selected.photo = "data:image/png;base64,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";
            }
            $('#EditUser').modal();
        };

        $scope.prepareDelete = function () {
            console.log($scope.Selected);
            $('#DeleteUser').modal();
        };

        $scope.prepareSendOneMsg = function () {
            $scope.SendOneMsg = true;
            $scope.SendManyMsg = false;
            $('#SendSMS').modal();
        };

        $scope.prepareSendManyMsg = function () {
            if ($scope.IDSENDSMS.id.length == 0) {
                NotifyCation('کاربران', 'کاربری برای ارسال sms انتخاب نشده است', 'error', true);
            } else {
                $scope.SendManyMsg = true;
                $scope.SendOneMsg = false;
                $('#SendSMS').modal();
            }
        };
        /////////////////////////
        // End PrePare Functions
        /////////////////////////

        ////////////////////////
        // Submit Function
        ////////////////////////
        $scope.EditUser = function () {
            $scope.Selected.expires_at = $('#ExpiresAtEdit').val();
            if ($scope.Selected.name == '' || $scope.Selected.family == ''
                    || $scope.Selected.mobile == '' || $scope.Selected.email == '') {
                NotifyCation('ثبت کاربر', 'فیلد های نام و نام خانوادگی و موبایل وایمیل نمیتواند خالی باشد', 'error', true);
            } else {
                $scope.Selected.expires_at = $('.expires_at_edit').val();
                $http({
                    method: 'post',
                    url: 'EditUser',
                    data: $scope.Selected
                }).success(function (response) {
                    var msg = response.split(';');
                    console.log(msg);
                    NotifyCation(msg[0], msg[1], msg[2], msg[3]);
                });
            }
        };

        $scope.CreateUser = function () {
//            console.log($scope.Selected);
            $scope.Selected.expires_at = $('#ExpiresAtAdd').val();
            if ($scope.Selected.name == '' || $scope.Selected.family == ''
                    || $scope.Selected.mobile == '' || $scope.Selected.email == '') {
                NotifyCation('ثبت کاربر', 'فیلد های نام و نام خانوادگی و موبایل وایمیل نمیتواند خالی باشد', error, true);
            } else {
                $scope.Selected.expires_at = $('.expires_at_add').val();
                $http({
                    method: 'post',
                    url: 'AddUser',
                    data: $scope.Selected
                }).success(function (response) {
                    var msg = response.split(';');
//                    console.log(msg);
                    NotifyCation(msg[0], msg[1], msg[2], msg[3]);
                    $scope.paginate($scope.current);
                    $('#EditUser').modal('hide');
                }).error(function (response) {
                    NotifyCation('حذف حیوانات', 'مشکل در اطلاعات ارسال شده', 'danger', true);
                });
            }
        };

        var filterTextTimeout;
        $scope.search = function () {
            $scope.filter = $scope.searchText;
            if ($scope.filter == '') {
                $scope.filter = -1;
            }
            var route = baseURL + "users/filters/" + $scope.filter + "/orders/" + $scope.attr + "/bies/" + $scope.asc + "/offsets/0/limits/" + $scope.constPageItems;
            if (filterTextTimeout) {
                clearTimeout(filterTextTimeout);
            }
            filterTextTimeout = setTimeout(function () {
                $http.get(route).success(function (response) {
                    console.log(response);
                    if (response.msg != undefined) {
                        var msg = response.msg.split(';');
                        console.log(msg);
                        NotifyCation(msg[0], msg[1], msg[2], msg[3]);
                    } else {
                        $scope.data = response.user;
                        if ($scope.filter == -1) {
                            $scope.count = response.count;
                        } else {
                            $scope.count = response.count_data;
                        }

                        $scope.allPage = Math.floor($scope.count / $scope.constPageItems);
                        if ($scope.count % $scope.constPageItems > 0 && $scope.count > $scope.constPageItems) {
                            $scope.allPage++;
                        }
                        $scope.RefreshPagination();
                    }
                }).error(function (r) {
                    NotifyCation('جستجوی کاربران', 'مشکل در برقراری ارتباط با سرور با پشتیبانی تماس بگیرید', 'error', true);
                });
            }, 2000);
        };

        $scope.SendDeleteUser = function () {
            $http.get(baseURL + 'DeleteUser/' + $scope.Selected.id).success(function (response) {
                var msg = response.split(';');
                $('#DeleteUser').modal('hide');
                $('#' + $scope.Selected.id).hide();
                NotifyCation(msg[0], msg[1], msg[2], msg[3]);

            });
        };

        $scope.SendMsg = function () {
            if ($scope.SendSms == '') {
                NotifyCation('ارسال پیام', 'متن پیام نمی تواند خالی باشد', 'error', true);
            } else {
                var info = {};
                if ($scope.SendOneMsg == true) {
                    info = {
                        One: false,
                        msg: $scope.TextSms,
                        id: [$scope.Selected.id]
                    };
                    $http({
                        method: 'post',
                        url: baseURL + 'SendMsg',
                        data: info
                    }).success(function (response) {
                        var msg = response.split(';');
                        NotifyCation(msg[0], msg[1], msg[2], msg[3]);
                        $('#SendSMS').modal('hide');
                    }).error(function (error) {
                        NotifyCation('کاربران', 'مشکل در برقراری ارتباط با سرور', 'error', true);
                    });
                } else if ($scope.SendManyMsg == true) {
                    info = {
                        One: false,
                        msg: $scope.TextSms,
                        id: $scope.IDSENDSMS.id
                    };
                    $http({
                        method: 'post',
                        url: baseURL + 'SendMsg',
                        data: info
                    }).success(function (response) {
                        var msg = response.split(';');
                        NotifyCation(msg[0], msg[1], msg[2], msg[3]);
                        $('#SendSMS').modal('hide');
                        $scope.IDSENDSMS.id = [];
                    }).error(function (error) {
                        NotifyCation('کاربران', 'مشکل در برقراری ارتباط با سرور', 'error', true);
                    });
                }
            }
        };
        ////////////////////////////
        // End Submit Function
        ////////////////////////////
        $scope.IDSENDSMS = {
            id: []
        };

    }]);

app.controller('AnimaleEdit', ['$scope', '$http', '$rootScope', '$routeParams', function ($scope, $http, $rootScope, $routeParams) {
        var id = $routeParams.id;
        if (id != undefined) {
            if (user == undefined) {
                $http.get(baseURL + 'ShowUser/' + id).success(function (response) {
                    $scope.Selected = response.user;
                    $scope.SelectedUser = response.user;
                })
            } else {
                $scope.Selected = user;
                $scope.SelectedUser = user;
            }
            $scope.FindAnimal = function () {
                $http.get(baseURL + 'UserShowAnimale/' + id).success(function (response) {
                    try {
                        if (response.animale.msg != undefined) {
                            var msg = response.animale.msg.split(';');
                            NotifyCation(msg[0], msg[1], msg[2], msg[3]);
                            $scope.DataUserAnimale = {};
                        } else {
                            $scope.DataUserAnimale = response.animale;
                        }
                    } catch (e) {

                    }
                });
            };

            //First Query
            $scope.FindAnimal();
            $scope.DataAnimaleActivity = {};
            $scope.AddItem = {};
            $scope.Selected;
            var PicDefault = "data:image/png;base64,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";

            $scope.ShowInfoUserUpload = function () {
                $http.get(baseURL + 'UserShowInfoUpload/' + id).success(function (response) {
                    if (response.msg != undefined) {
                        var msg = response.msg.split(";");
                        NotifyCation(msg[0], msg[1], msg[2], msg[3]);
                    } else {
                        $scope.UserShowInfoUploadData = response.ShowInfoUserUpload;
                    }
                }).error(function () {
                    NotifyCation("نمایش اطلاعات کاربر", "مشکل در برقراری ارتباط با سرور", "error", true);
                });

                $scope.ShowAllHistory = true;
                $('#ShowUserInfoUpload').modal();
            };

            $scope.SelectedItemsUpload = function (item) {
                $scope.SelectedUpload = item;
            }

            $scope.ShowUpload = function () {
                $scope.ShowAllHistory = false;
            };
            $scope.download = function () {
                var type = $scope.SelectedUpload.photo.split(";");
                download($scope.SelectedUpload.photo, "scan.jpeg", type[0]);
            };
            $scope.HideShowUpload = function () {
                $scope.ShowAllHistory = true;
            }

            $scope.SelectedItemAnimale;
            $scope.SelectedItems = function (item) {
                $scope.ShowItem = item;
                $scope.AddItem = item;
                if ($scope.AddItem.photo == undefined) {
                    $scope.AddItem.photo = PicDefault;
                }
                $scope.SelectedItemAnimale = angular.copy(item);
            };

            $scope.Show = function (item) {
                $scope.AddItem = item;
                $scope.EditAnimaleShow = false;
                $scope.AddAnimaleShow = false;
                $scope.AnimaleShow = true;
                $('#AnimaleShowInfo').modal();
            };



            $scope.prepareEdit = function () {
                $http.get(baseURL + 'CategoryAnimaleAll').success(function (response) {
                    if (response != 'Not Info') {
                        $scope.categoryAll = response;
                    }
//                console.log(response);
                });
                $scope.AddAnimaleShow = false;
                $scope.EditAnimaleShow = true;
                $('#AnimaleShowInfo').modal();
            };

            $scope.EditAnimale = function () {
                if ($scope.AddItem.name == '' || $scope.AddItem.goneh == ''
                        || $scope.AddItem.nezahad == '' || $scope.AddItem.animalsTypeId == '') {
                    NotifyCation('ویرایش اطلاعات حیوان ', 'فیلد های نام و نژاد و گونه و دسته بندی نمی تواند خالی باشد', error, true);
                } else {
                    if (PicDefault == $scope.AddItem.photo) {
                        $scope.AddItem.photo = '';
                    }
                    var da = $('.EditAge').val();
                    if (da != undefined) {
                        da = da.split("/");
                        if (da[0].length == 2) {
                            $scope.AddItem.age = da[2] + "/" + da[1] + "/" + da[0];
                        }
                    }
                    $http({
                        method: 'post',
                        url: 'EditAnimale',
                        data: $scope.AddItem
                    }).success(function (response) {
                        var msg = response.msg.split(';');
                        console.log(msg);
                        NotifyCation(msg[0], msg[1], msg[2], msg[3]);
                    }).error(function (error) {
                        NotifyCation('کاربران', 'مشکل در برقراری ارتباط با سرور', 'error', true);
                    });
                }
            };

            $scope.prepareCreate = function () {
                $scope.AddItem = {};
                $scope.AddItem.photo = "data:image/png;base64,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";
                $http.get(baseURL + 'CategoryAnimaleAll').success(function (response) {
                    if (response != 'Not Info') {
                        $scope.categoryAll = response;
                    }
//                console.log(response);
                });
                $scope.EditAnimaleShow = false;
                $scope.AddAnimaleShow = true;
                $('#AnimaleShowInfo').modal();
            };

            $scope.prepareDelete = function () {
                $('#DeleteModalAnimaleID').modal();
            };

            $scope.AddAnimale = function () {
                var da = $('.age').val();
                if (da != undefined) {
                    da = da.split("/");
                    if (da[0].length == 2) {

                        var y = da[0];
                        da[0] = da[2];
                        da[2] = y;
                        $scope.AddItem.age = da[0] + "/" + da[1] + "/" + da[2];
                        console.log($scope.AddItem.age);
                    }
                }
//            console.log($scope.AddItem);
                var id = $scope.SelectedUser.id;
                if ($scope.AddItem.active == '' || $scope.AddItem.active == undefined) {
                    $scope.AddItem.active = true;
                }
                if ($scope.AddItem.name == ''
                        || $scope.AddItem.nezahad == '' || $scope.AddItem.animalsTypeId == '') {
                    NotifyCation('ویرایش اطلاعات حیوان ', 'فیلد های نام و نژاد و گونه و دسته بندی نمی تواند خالی باشد', error, true);
                } else {
                    $scope.AddItem.user_id = id;
                    $http({
                        method: 'post',
                        url: 'AddAnimale',
                        data: $scope.AddItem
                    }).success(function (response) {
                        try {
                            $scope.DataUserAnimale.push($scope.AddItem);
                            $scope.FindAnimal();
                        } catch (error) {
                            $scope.DataUserAnimale = {
                                0: $scope.AddItem
                            };
                            $scope.FindAnimal();
                        }
                        var msg = response.animale.msg.split(';');
                        NotifyCation(msg[0], msg[1], msg[2], msg[3]);
                    }).error(function (error) {
                        NotifyCation('افزودن حیوانات', 'مشکل در برقراری ارتباط با سرور', 'error', true);
                    });
                }
            };

            $scope.DeleteAnimale = function () {
                console.log($scope.ShowItem);
                $http.get('DeleteAnimale/' + $scope.ShowItem.id).success(function (response) {
                    $('#DeleteModalAnimaleID').modal('hide');
                    $('#' + $scope.ShowItem.id).hide();
                    var msg = response.split(';');
                    NotifyCation(msg[0], msg[1], msg[2], msg[3]);
                }).error(function () {
                    NotifyCation('حذف حیوانات', 'مشکل در اطلاعات ارسال شده', 'danger', true);
                });
            };
            ///////////////////////
            // End Animale
            ///////////////////////



            ///////////////////////
            // Activity Animale
            ///////////////////////
            $scope.prepareActivity = function () {
                $http.get(baseURL + 'Activity/' + $scope.ShowItem.id).success(function (response) {

                    if (response.activity.msg != undefined) {
                        $scope.SelectedActivity = {};
                        $scope.EditActivityAnimale = false;
                        $scope.AddActivityAnimale = false;
                        $scope.AllActivity = true;
                        $scope.ShowActivityInfo = false;
                        $scope.SelectedActivityAnimale = false;
                        $scope.DataAnimaleActivity = {};
                        var msg = response.activity.msg.split(';');
                        NotifyCation(msg[0], msg[1], msg[2], msg[3]);
                    } else {
                        $scope.DataAnimaleActivity = {};
                        $scope.DataAnimaleActivity = response.activity;
                        $scope.SelectedActivity = {};
                        $scope.EditActivityAnimale = false;
                        $scope.AddActivityAnimale = false;
                        $scope.AllActivity = true;
                        $scope.ShowActivityInfo = false;
                        $scope.SelectedActivityAnimale = false;
                    }
                });
                $('#ShowActivity').modal();
            };

            //push new info to all activity
            $scope.pushActivity = function (value) {
                try {
                    $scope.DataAnimaleActivity.push(value);
                } catch (error) {
                    $scope.DataAnimaleActivity = {};
                    $scope.DataAnimaleActivity = {0: value};
                }
            };

            $scope.SelectedActivity = {};
            $scope.ShowItem = {};
            $scope.SelectedActivityAnimale = false;

            // select activity
            $scope.SelectedActivityItems = function (item) {
                $scope.SelectedActivity = item;
                $scope.SelectedActivityAnimale = true;
            };

            //show activity
            $scope.ShowActivity = function () {
                $scope.EditActivityAnimale = false;
                $scope.AddActivityAnimale = false;
                $scope.AllActivity = false;
                $scope.ShowActivityInfo = true;
            };

            //show all activity and close show form
            $scope.prepareActivityHideModal = function () {
                $scope.EditActivityAnimale = false;
                $scope.AddActivityAnimale = false;
                $scope.AllActivity = true;
                $scope.ShowActivityInfo = false;
            };

            //show form create activity
            $scope.prepareActivityCreate = function () {
                $http.get(baseURL + 'AllActivityType').success(function (response) {
                    if (response.activity_name.msg != undefined) {
                        var msg = response.activity_name.msg.split(';');
                        NotifyCation(msg[0], msg[1], msg[2], msg[3]);
                        $scope.AllActivityType = '';
                    } else {
                        $scope.AllActivityType = response.activity_name;
                    }
                }).error(function (response) {

                });

                $scope.SelectedActivity = {};
                $scope.SelectedActivityAnimale = false;
                $scope.EditActivityAnimale = false;
                $scope.AddActivityAnimale = true;
                $scope.AllActivity = false;
                $scope.ShowActivityInfo = false;
            };

            //show modal comfirm delete
            $scope.prepareActivityDelete = function () {
                $('#DeleteActivityAnimale').modal();
            };

            //show modal edit form
            $scope.prepareActivityEdit = function () {
                $http.get(baseURL + 'AllActivityType').success(function (response) {
                    if (response.activity_name.msg != undefined) {
                        var msg = response.activity_name.msg.split(';');
                        NotifyCation(msg[0], msg[1], msg[2], msg[3]);
                        $scope.AllActivityType = '';
                    } else {
                        $scope.AllActivityType = response.activity_name;
                    }
                }).error(function (response) {

                });
                $scope.AddActivityAnimale = false;
                $scope.EditActivityAnimale = true;
                $scope.AllActivity = false;
                $scope.ShowActivityInfo = false;
            };

            //send info to server create a activity
            $scope.AddActivity = function () {
                console.log($scope.SelectedActivity);
                if ($('#date').val() == '') {
                    NotifyCation('افزودن فعالیت ها', 'زمان نمی تواند خالی باشد', 'error', true);
                } else {
                    var id = $scope.SelectedUser.id;
                    $scope.SelectedActivity.animale_id = $scope.SelectedItemAnimale.id;
                    $scope.SelectedActivity.date = $('#date').val();
                    console.log($scope.SelectedActivity);
                    $http({
                        method: 'post',
                        url: baseURL + 'AddActivity',
                        data: $scope.SelectedActivity
                    }).success(function (response) {
                        $scope.pushActivity(response.activity.add);
                        $scope.AddActivityAnimale = false;
                        $scope.AllActivity = true;
                        var msg = response.activity.msg.split(';');
                        NotifyCation(msg[0], msg[1], msg[2], msg[3]);
                    }).error(function (error) {
                        NotifyCation('فعالیت ها', 'مشکل در برقراری ارتباط با سرور', 'error', true);
                    });
                }
            };

            //send info edit activity
            $scope.EditActivity = function () {
                if ($('#DateEdit').val() == '') {
                    NotifyCation('ویرایش فعالیت ها', 'زمان نمی تواند خالی باشد', 'error', true);
                } else {
                    $scope.SelectedActivity.animale_id = $scope.SelectedItemAnimale.id;
                    $scope.SelectedActivity.date = $('#DateEdit').val();
                    console.log($scope.SelectedActivity);
                    $http({
                        method: 'post',
                        url: baseURL + 'EditActivity',
                        data: $scope.SelectedActivity
                    }).success(function (response) {
                        var msg = response.split(';');
                        console.log(msg);
                        NotifyCation(msg[0], msg[1], msg[2], msg[3]);
                    }).error(function (error) {
                        NotifyCation('فعالیت ها', 'مشکل در برقراری ارتباط با سرور', 'error', true);
                    });
                }
            };

            //send id activity for delete 
            $scope.DeleteActivity = function () {
                $http.get(baseURL + 'DeleteActivity/' + $scope.SelectedActivity.id).success(function (response) {
                    var msg = response.activity.msg.split(';');
                    console.log(msg);
                    NotifyCation(msg[0], msg[1], msg[2], msg[3]);
                    $('#DeleteActivityAnimale').modal('hide');
                    $('#Activity' + $scope.SelectedActivity.id).hide();
                });
            };
            //show modal comfirm delete
            $scope.prepareActivityDelete = function () {
                $('#DeleteActivityAnimale').modal();
            };
            ////////////////////////
            //end activity
            ////////////////////////
            $scope.ShowHistory = function () {
                $scope.$broadcast("History", [$scope.Selected]);
                $('#AnimaleShowHistory').modal();
            };
        }
    }]);

app.controller('AnimalsHistory', ['$scope', '$http', 'paginationCreateArray', function ($scope, $http, paginationCreateArray) {
        $scope.$on('History', function (event, item) {
            $scope.DataHistory = {};
            $scope.count = 0;
            $scope.Allpaginate = 0;
            $scope.ShowAllHistory = true;
            $scope.id = item[0].id;
            $scope.constPageItems = 10;
            $scope.current = 1;
            //init pagination value
            var current = 1;
            var constPageItems = 10;
            var allPage = 0;
            var row = new Array();
            var route = baseURL + "History/" + $scope.id + "/id/-1/filter/0/offsets/" + constPageItems + "/limits";
            $http.get(route).success(function (response) {
                console.log(response);
                if (response.history.msg != undefined) {
                    var msg = response.history.msg.split(';');
                    NotifyCation(msg[0], msg[1], msg[2], msg[3]);
                } else {
                    $scope.DataHistory = response.history;
                    $scope.count = response.count;
                    //call Pagination for first
                    $scope.RefreshPagination();
                }
            });

            $scope.filter = $scope.searchText;
            $scope.RefreshPagination = function () {
                allPage = Math.floor($scope.count / constPageItems); //Math.floor(CountItems / constPageItems);
                $scope.Allpaginate = paginationCreateArray.array(row, $scope.count, constPageItems, current);
                //function paginate
                $scope.paginate = function (offset) {

                    current = offset;
                    offset = offset * constPageItems - constPageItems;

                    var parameterurlPagination = "";
                    //History/{id}/id/{filter}/filter/{offset}/offsets/{limit}/limits
                    parameterurlPagination =
                            baseURL + "History/" + $scope.id + "/id/-1/filter/" + offset + "/offsets/" + constPageItems + "/limits";


                    $http.get(parameterurlPagination).success(function (response) {

                        $scope.DataHistory = response.history;
                        $scope.count = response.count;
                        $scope.RefreshPagination();
                    });
                };

                //next offset
                $scope.pageNext = function () {
                    $scope.paginate(current + 1);
                };

                //preview offset
                $scope.pagePreview = function () {
                    if (current > 1) {
                        $scope.paginate(current - 1);
                    }
                };

                //for print ...
                $scope.checkZero = function (x) {
                    if (x == 0)
                    {
                        return false;
                    } else {
                        return true;
                    }
                };

                //checking Visible Next Button
                $scope.checkVisibleNext = function () {

                    if ((current) > allPage - 1)
                    {
                        return false;
                    }
                    return true;
                };

                //checking Visible prev Button
                $scope.checkVisiblePrev = function () {
                    if (current > 1)
                    {
                        return true;
                    }
                    return false;
                };

                // Sort Order By desc
                $scope.sortDesc = function (field_in) {
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
                            {headers: {'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)}}).success(function (response) {
                        $scope.PostsShow = response;
                    });
                    current = $scope.CountPaginate;
                    $scope.RefreshPagination();
                    $('.paginate').last().click();
                };

                //Sort Order By Asc
                $scope.sortAsc = function (field_in) {
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
                            {headers: {'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)}}).success(function (response) {
                        $scope.content = response;
                        current = 1;
                        $scope.RefreshPagination();
                        $('.paginate').first().click();
                    });
                };
            };
            $scope.RefreshPagination();


            var FileDefault;
            $scope.DataAnimaleActivity = {};
            $scope.AddItem = {};
            $scope.Selected;
            var PicDefault = "data:image/png;base64,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";

            $scope.SelectedItemAnimale;
            $scope.SelectedItems = function (item) {
                $scope.AddItem = item;
                FileDefault = item.file;
                $scope.Selected = item;
                $scope.SelectedItemHistory = angular.copy(item);
            };

            $scope.Show = function (item) {
                $scope.AddItem = item;
                $scope.EditHistoryShow = false;
                $scope.AddHistoryShow = false;
                $scope.ShowAllHistory = false;
                $scope.HistoryShow = true;
            };

            $scope.prepareEdit = function () {
                $scope.AddHistoryShow = false;
                $scope.ShowAllHistory = false;
                $scope.EditHistoryShow = true;
            };

            $scope.EditHistory = function () {
                if (($scope.AddItem.file == "" || $scope.AddItem.file == undefined) && ($scope.AddItem.Describ != null || $scope.AddItem.dateHistory != "")) {
                    NotifyCation("ویرایش اطلاعات کاربر", "برای ثبت سوابق پزشکی اسکن مدارک الزامی می باشد", "error", true);
                } else {
                    if (FileDefault == $scope.AddItem.file) {
                        $scope.AddItem.file = '';
                    }
                    $scope.AddItem.animale_id = $scope.id;
                    $http({
                        method: 'post',
                        url: 'EditHistory',
                        data: $scope.AddItem
                    }).success(function (response) {
                        var msg = response.msg.split(';');
                        NotifyCation(msg[0], msg[1], msg[2], msg[3]);
                        $scope.hide();
                    }).error(function (error) {
                        NotifyCation('کاربران', 'مشکل در برقراری ارتباط با سرور', 'error', true);
                    });
                    if (FileDefault == $scope.AddItem.file) {
                        $scope.AddItem.file = FileDefault;
                    }
                }
            };

            $scope.prepareCreate = function () {
                $scope.AddItem = {};
                $scope.AddItem.photo = "data:image/png;base64,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";
                $scope.EditHistoryShow = false;
                $scope.AddHistoryShow = true;
                $scope.ShowAllHistory = false;
            };
            $scope.hide = function () {
                $scope.ShowAllHistory = true;
                $scope.EditHistoryShow = false;
                $scope.AddHistoryShow = false;
            };
            $scope.prepareDelete = function () {
                $('#DeleteModalHistoryID').modal();
            };

            $scope.AddHistory = function () {
                var file = $scope.AddItem.file;
                if (file != undefined) {
                    file = file.split(';');
                    if (file[0] != 'data:image/jpeg' && file[0] != 'data:image/png') {
                        NotifyCation('ثبت حیوانات', 'فرمت ها مجاز انتخاب نشده است فرمت های مجاز pdf,png,jpg می باشد', 'error', true);
                    } else {
                        if ($scope.AddItem.dateHistory == '' || $scope.AddItem.file == undefined) {
                            NotifyCation('افزودن سوابق ', 'فیلد های اسکن و تاریخ ایجاد پرونده نمی تواند خالی باشد', 'error', true);
                        } else {
                            $scope.AddItem.animale_id = $scope.id;
                            $http({
                                method: 'post',
                                url: 'AddHistory',
                                data: $scope.AddItem
                            }).success(function (response) {
                                try {
                                    $scope.DataHistory.push($scope.AddItem);
                                } catch (error) {
                                    $scope.DataHistory = {
                                        0: $scope.AddItem
                                    };
                                }
                                var msg = response.msg.split(';');
                                NotifyCation(msg[0], msg[1], msg[2], msg[3]);
                                $scope.hide();
                            }).error(function (error) {
                                NotifyCation('افزودن سوابق', 'مشکل در برقراری ارتباط با سرور', 'error', true);
                            });
                        }
                    }
                } else {
                    NotifyCation('ثبت حیوانات', 'فرمت ها مجاز انتخاب نشده است فرمت های مجاز pdf,png,jpg می باشد', 'error', true);
                }
            };

            $scope.DeleteHistory = function () {
//                console.log($scope.Selected);
                $http.get(baseURL + 'DeleteHistory/' + $scope.Selected.id).success(function (response) {
                    $('#DeleteModalHistoryID').modal('hide');
                    $('#h' + $scope.Selected.id).hide();
                    var msg = response.msg.split(';');
                    NotifyCation(msg[0], msg[1], msg[2], msg[3]);
                }).error(function () {
                    NotifyCation('حذف حیوانات', 'مشکل در اطلاعات ارسال شده', 'danger', true);
                });
            };

            $scope.download = function () {
                var type = $scope.Selected.file.split(";");
                download($scope.Selected.file, "scan.jpeg", type[0]);
            };
        });
    }]);

//app.controller('AnimaleActivity', ['$scope', '$http', '$rootScope', function($scope, $http, $rootScope) {
//
//    }]);


function download(data, strFileName, strMimeType) {

    var self = window, // this script is only for browsers anyway...
            u = "application/octet-stream", // this default mime also triggers iframe downloads
            m = strMimeType || u,
            x = data,
            D = document,
            a = D.createElement("a"),
            z = function (a) {
                return String(a);
            },
            B = self.Blob || self.MozBlob || self.WebKitBlob || z,
            BB = self.MSBlobBuilder || self.WebKitBlobBuilder || self.BlobBuilder,
            fn = strFileName || "download",
            blob,
            b,
            ua,
            fr;

    //if(typeof B.bind === 'function' ){ B=B.bind(self); }

    if (String(this) === "true") { //reverse arguments, allowing download.bind(true, "text/xml", "export.xml") to act as a callback
        x = [x, m];
        m = x[0];
        x = x[1];
    }
    //go ahead and download dataURLs right away
    if (String(x).match(/^data\:[\w+\-]+\/[\w+\-]+[,;]/)) {
        return navigator.msSaveBlob ? // IE10 can't do a[download], only Blobs:
                navigator.msSaveBlob(d2b(x), fn) :
                saver(x); // everyone else can save dataURLs un-processed
    }//end if dataURL passed?

    try {

        blob = x instanceof B ?
                x :
                new B([x], {type: m});
    } catch (y) {
        if (BB) {
            b = new BB();
            b.append([x]);
            blob = b.getBlob(m); // the blob
        }

    }



    function d2b(u) {
        var p = u.split(/[:;,]/),
                t = p[1],
                dec = p[2] == "base64" ? atob : decodeURIComponent,
                bin = dec(p.pop()),
                mx = bin.length,
                i = 0,
                uia = new Uint8Array(mx);

        for (i; i < mx; ++i)
            uia[i] = bin.charCodeAt(i);

        return new B([uia], {type: t});
    }

    function saver(url, winMode) {


        if ('download' in a) { //html5 A[download] 			
            a.href = url;
            a.setAttribute("download", fn);
            a.innerHTML = "downloading...";
            D.body.appendChild(a);
            setTimeout(function () {
                a.click();
                D.body.removeChild(a);
                if (winMode === true) {
                    setTimeout(function () {
                        self.URL.revokeObjectURL(a.href);
                    }, 250);
                }
            }, 66);
            return true;
        }

        //do iframe dataURL download (old ch+FF):
        var f = D.createElement("iframe");
        D.body.appendChild(f);
        if (!winMode) { // force a mime that will download:
            url = "data:" + url.replace(/^data:([\w\/\-\+]+)/, u);
        }


        f.src = url;
        setTimeout(function () {
            D.body.removeChild(f);
        }, 333);

    }//end saver 


    if (navigator.msSaveBlob) { // IE10+ : (has Blob, but not a[download] or URL)
        return navigator.msSaveBlob(blob, fn);
    }

    if (self.URL) { // simple fast and modern way using Blob and URL:
        saver(self.URL.createObjectURL(blob), true);
    } else {
        // handle non-Blob()+non-URL browsers:
        if (typeof blob === "string" || blob.constructor === z) {
            try {
                return saver("data:" + m + ";base64," + self.btoa(blob));
            } catch (y) {
                return saver("data:" + m + "," + encodeURIComponent(blob));
            }
        }

        // Blob but not URL:
        fr = new FileReader();
        fr.onload = function (e) {
            saver(this.result);
        };
        fr.readAsDataURL(blob);
    }
    return true;
} 