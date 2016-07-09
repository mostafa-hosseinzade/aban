'use strict';
angular.module('myApp.controllers', ['ckeditor']).
        controller('fullController', ['$scope', '$http', 'paginationCreateArray', '$routeParams', '$timeout', '$window', function ($scope, $http, paginationCreateArray, $routeParams, $timeout, $window) {
                $scope.options = {
                    language: 'fa',
                    allowedContent: true,
                    entities: false
                };
                $scope.myImage;
                $scope.route = '';
                $scope.entity;
                $scope.filter = -1;
                $scope.attr = 'id';
                $scope.asc = 'ASC';
                $scope.current = 1;
                $scope.message;
                if ($routeParams.entity) {
                    $scope.entity = $routeParams.entity;
                    $scope.route += $scope.entity + "/";
                }

                if ($routeParams.filter) {
                    $scope.filter = $routeParams.filter;
                    $scope.route += "filter/" + $scope.filter + "/";
                    $scope.searchText = $scope.filter;
                }

                if ($routeParams.attr) {
                    $scope.attr = $routeParams.attr;
                    $scope.asc = $routeParams.asc;
                    $scope.route += "order/" + $scope.attr + "/" + $scope.asc + "/";
                }

                if ($routeParams.current) {
                    $scope.current = $routeParams.current;
                }

                $scope.constPageItems = 10;
                $scope.allPage = 0;
                $scope.count = 0;
                $scope.selectedItem;

                $scope.labelPage = entity[$scope.entity].label;
                $scope.search = function () {
                    var filterTextTimeout;
                    if (filterTextTimeout)
                        $timeout.cancel(filterTextTimeout);
                    filterTextTimeout = $timeout(function () {
                        $window.location = '#/' + $scope.entity + '/filter/' + $scope.searchText;
                    }, 1000);
                };

                function myalert(error, msg) {
                    $scope.message = {'error': error, 'msg': msg};
                    var filterTextTimeout;
                    if (filterTextTimeout)
                        $timeout.cancel(filterTextTimeout);
                    filterTextTimeout = $timeout(function () {
                        $scope.message = null;
                    }, 7000);
                }
                ;

                $scope.numberShowChange = function () {
                    $scope.paginate(1);
                };
                $scope.selected;
                $scope.selectedItems = function (item, index) {
                    $scope.selected = item;
                    //console.log($scope.selected);
                    $scope.selectedItem = angular.copy(item);
                };

                $scope.showItem = function (item, index) {
                    $scope.action = {'type': 'SHOW', 'label': 'مشاهده'};
                    $scope.selected = item;
                    $scope.selectedItem = angular.copy(item);
                    $('#showModal').modal('show');
                };

                $scope.action;
                $scope.prepareCreate = function () {
                    $scope.action = {'type': 'CREATE', 'label': 'افزودن'};
                    $('tr').removeClass('selectedItem');
                    $scope.selectedItem = null;
                    $scope.selectedItem = {};
                    var form = entity[$scope.entity]['form'];
                    $.each(form, function (item) {
                        var attr = form[item].name;
                        if (form[item].type === 'boolean') {
                            $scope.selectedItem[attr] = false;
                        } else if (form[item].type === 'number') {
                            $scope.selectedItem[attr] = 0;
                        } else {
                            $scope.selectedItem[attr] = '';
                        }
                    });
                };

                $scope.prepareEdit = function () {
                    $scope.action = {'type': 'EDIT', 'label': 'ویرایش'};
                };

                $scope.SubmitAdd = function () {
                    var en = $scope.entity;
                    var mydata = {};
                    if ($scope.action.type === 'EDIT' && checkChangeForEdit(entity[$scope.entity]['form'], $scope.selectedItem, $scope.selected)) {
                        myalert(true, 'اطلاعات موجودیت تغییری نکرده');
                        return false;
                    }
                    mydata[en.toLowerCase()] = removeExtraAttrFromForm(entity[$scope.entity]['form'], $scope.selectedItem);
                    $http({
                        method: 'PUT',
                        data: mydata,
                        url: BaseUrlAdmin + 'api/entities/' + $scope.entity + "/byids/" + ($scope.action.type === 'EDIT' ? $scope.selected.id : -1) + '.json',
                        headers: {'Content-Type': 'application/json;charset=utf-8'}
                    }).then(function successCallback(response) {
                        $('[aria-labelledby=myModalLabel]').modal('hide');
                        if ($scope.action.type === 'EDIT') {
                            var index = $scope.content.indexOf($scope.selected);
                            $scope.content[index] = response.data;
                        } else {
                            $scope.content.push(response.data);
                        }
                        $scope.selectedItem = null;
                        myalert(false, $scope.action.label + ' به صورت موفق');
                    }, function errorCallback(response) {
                        myalert(true, 'مشکل در ثبت اطلاعات با پشتیبانی تماس بگیرید');
                    });
                };
                function checkChangeForEdit(form, arr1, arr2) {
                    var check = true;

                    $.each(form, function (item) {
                        if (form[item].type === 'entity') {
                            if (typeof arr1[form[item].name] === 'object') {
                                if (arr2[form[item].name].id != arr1[form[item].name].id) {
                                    check = false;
                                }
                            } else {
                                check = false;
                            }
                        } else {
                            if (arr2[form[item].name] != arr1[form[item].name]) {
                                check = false;
                            }
                        }
                    });
                    return check;
                }

                function removeExtraAttrFromForm(form, arr) {
                    var editdata = {};
                    $.each(form, function (item) {
                        if (form[item].type === 'entity') {
                            if (typeof arr[form[item].name] === 'object') {
                                arr[form[item].name] = arr[form[item].name].id;
                            }
                        }
                        if (form[item].type == 'file') {
                            editdata[form[item].name] = arr[form[item].name];
                        } else {
                            editdata[form[item].name] = arr[form[item].name];
                        }
                    });
                    return editdata;
                }

                $scope.SubmitDelete = function () {
                    $('#CloseDeleteBtn').click();
                    $http({
                        method: 'DELETE',
                        url: BaseUrlAdmin + 'api/entities/' + $scope.entity + "/byids/" + $scope.selectedItem.id + '.json'
                    }).then(function successCallback(response) {
                        var index = $scope.content.indexOf($scope.selectedItem);
                        $scope.selectedItem = null;
                        $scope.content.splice(index, 1);
                        myalert(false, 'حذف موفق');
                    }, function errorCallback(response) {
                        myalert(true, 'حذف ناموفق');
                    });
                };

                //function paginate
                $scope.paginate = function (offset) {
                    offset = offset * $scope.constPageItems - $scope.constPageItems;
                    var route = BaseUrlAdmin + "api/entities/" + $scope.entity + "/filters/" + $scope.filter + "/orders/" + $scope.attr + "/bies/" + $scope.asc + "/offsets/" + offset + "/limits/" + $scope.constPageItems + ".json";
                    $http.get(route).success(function (response) {
                        $scope.content = response.data;
                        $scope.count = response.count;
                        $scope.allPage = Math.floor($scope.count / $scope.constPageItems);
                        if ($scope.count % $scope.constPageItems > 0 && $scope.count > $scope.constPageItems) {
                            $scope.allPage++;
                        }
                        $scope.Allpaginate = paginationCreateArray.array($scope.allPage, $scope.current);
                    });
                };

                if ($routeParams.current) {
                    $scope.paginate($routeParams.current);
                    $scope.current = parseInt($routeParams.current);
                } else {
                    $scope.paginate(1);
                    $scope.current = 1;
                }
            }]).
        controller('profile', ['$scope', '$http', 'paginationCreateArray', '$routeParams', '$timeout', '$window', function ($scope, $http, paginationCreateArray, $routeParams, $timeout, $window) {
                $scope.user;
                $scope.isUpdate = true;
                $scope.message;
                $scope.password;
                $scope.repassword;
                $scope.updateLabel = 'تغییر مشخصات';
                $http.get(BaseUrl + 'admin/profile_admin').success(function (res) {
                    $scope.user = res;
                });
                $scope.onUpdate = function () {
                    if ($scope.isUpdate) {
                        $scope.isUpdate = false;
                        $scope.updateLabel = 'بروزرسانی';
                    } else {
                        $http({method: 'POST', data: $scope.user, url: BaseUrl + 'admin/profile_update'}).success(function (res) {
                            if (res) {
                                $scope.isUpdate = true;
                                $scope.updateLabel = 'تغییر مشخصات';
                                $scope.message='اطلاعات شما ویرایش شد';
                            }
                        });
                    }
                };
                $scope.onUpdatePassword = function () {
                    console.log($scope.password.length);
                    if (!$scope.password || !$scope.password.length || $scope.password.length < 5 ) {
                        $scope.message='کلمه عبور وارد شده نباید کمتر از 5 کاراکتر باشد';
                        return false;
                    }
                    if($scope.password != $scope.repassword){
                        $scope.message='دو کلمه عبور وارد شده یکسان نیستند';
                        return false;
                    }
                    $http({method: 'POST', data:{'password': $scope.password}, url: BaseUrl + 'admin/profile_update_password'}).success(function (res) {
                        if (res) {
                            $scope.password=null;
                            $scope.repassword=null;
                            $scope.message='کلمه عبور شما تغییر کرد.';
                        }
                    });
                };
            }]);