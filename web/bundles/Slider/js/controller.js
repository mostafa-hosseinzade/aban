'use strict';

var app = angular.module('Slider', ['ngRoute', 'angular-loading-bar', 'ngAnimate']);

app.config(['$routeProvider', function($routeProvider) {
        $routeProvider.when('/', {templateUrl: '../../bundles/Slider/partials/index.html', controller: 'Index'});
        $routeProvider.when('/keyvalue', {templateUrl: '../../bundles/Slider/partials/KeyValue.html', controller: 'KeyValue'});
    }]);

app.controller('Index', ['$scope', '$http', 'paginationCreateArray', function($scope, $http, paginationCreateArray) {
        $scope.AddItem = {};
        $scope.AddItem.photo = '';
        ////////////////////////
        //Controller Method
        ///////////////////////
        $scope.SelectedItems = function(item) {
            $scope.Selected = item;
        };

        $scope.Show = function() {
            $scope.AddSliderInfo = false;
            $scope.ShowSliderInfo = true;
            $scope.EditSliderInfo = false;
            $('#Sliders').modal();
        };



        $scope.prepareEdit = function() {
            $scope.AddItem = {};
            $scope.AddItem.photo = '../../bundles/public/img/slider/' + $scope.Selected.file;
            $scope.EditSliderInfo = true;
            $scope.AddSliderInfo = false;
            $scope.ShowSliderInfo = false;
            $('#Sliders').modal();
        };

        $scope.prepareDelete = function() {
            $('#DeleteSlider').modal();
        };



        $scope.prepareCreate = function() {
            $scope.AddItem = {};
            $scope.AddItem.photo = '';
            $scope.AddSliderInfo = true;
            $scope.ShowSliderInfo = false;
            $scope.EditSliderInfo = false;
            $('#Sliders').modal();
        };
        //////////////////////////
        // End Controller Method
        /////////////////////////

        ///////////////////////////////
        // Request Method
        //////////////////////////////
        $http.get(baseURL + 'ShowAll').success(function(response) {
            if (response.slider.msg != undefined) {
                var msg = response.slider.msg.split(';');
                NotifyCation(msg[0], msg[1], msg[2], msg[3]);
            } else {
                $scope.data = response.slider;
            }
        }).error(function(response) {
            NotifyCation('نمایش اطلاعات اسلایدر', 'مشکل در برقراری ارتباط با سرور', 'error', true);
        });
        $scope.SendFile = function() {
            var uploadUrl = baseURL + 'AddPic';
//            console.log($scope.AddItem);
            if ($scope.AddItem.photo == '') {
                NotifyCation('افزودن اطلاعات اسلایدر', 'لطفا تصویر را انتخاب نمائید', 'error', true);
            } else {
                $http({
                    method: 'post',
                    url: uploadUrl,
                    data: $scope.AddItem
                }).success(function(response) {
                    var msg = response.slider.msg.split(';');
                    NotifyCation(msg[0], msg[1], msg[2], msg[3]);
                    $scope.data.push(response.slider.new);
                    $('#Sliders').modal('hide');
                    $scope.AddItem = {};
                }).error(function(response) {
                    NotifyCation('افزودن اطلاعات اسلایدر', 'مشکل در برقراری ارتباط با سرور', 'error', true);
                });
            }
        };

        $scope.EditSlider = function() {
            var uploadUrl = baseURL + 'EditSlider';
            var file_new = $scope.AddItem.photo;
            file_new = file_new.split(';');
            if (file_new[1] != undefined) {
                $scope.Selected.photo = $scope.AddItem.photo;
            }
            $http({
                method: 'post',
                url: uploadUrl,
                data: $scope.Selected
            }).success(function(response) {
                var msg = response.slider.msg.split(';');
                NotifyCation(msg[0], msg[1], msg[2], msg[3]);
                $('#Sliders').modal('hide');
                $scope.AddItem = {};
                if (response.slider.img !== undefined) {
                    $scope.Selected.file = response.slider.img;
                }
            }).error(function(response) {
                NotifyCation('ویرایش اطلاعات اسلایدر', 'مشکل در برقراری ارتباط با سرور', 'error', true);
            });
        };

        $scope.SendDeleteSlider = function() {
            $http.get(baseURL + 'DeleteSlider/' + $scope.Selected.id).success(function(response) {
                var msg = response.slider.msg.split(';');
                NotifyCation(msg[0], msg[1], msg[2], msg[3]);
                $('#DeleteSlider').modal('hide');
                $('#' + $scope.Selected.id).hide();
            }).error(function() {
                NotifyCation('حذف اطلاعات اسلایدر', 'مشکل در برقراری ارتباط با سرور', 'error', true);
            });
        };

    }]);
app.controller('KeyValue', ['$scope', '$http', function($scope, $http) {

        var data = {};

        $scope.SelectedItems = function(key, value) {
            $scope.Selected = {};
            $scope.Selected.key = key;
            $scope.Selected.value = value;
        };

        $scope.Show = function() {
            var ser = $scope.Selected.key;
            var n = ser.search('img');
            if (n != -1) {
                $scope.ImageShow = true;
            } else {
                $scope.ImageShow = false;
            }
            if (ser == 'Sections') {
                $scope.SelectedInput = true;
            } else {
                $scope.SelectedInput = false;
            }
            $scope.ShowKeyValue = true;
            $scope.EditKeyValue = false;
            $('#KeyValue').modal();
        };

        $scope.prepareEdit = function() {
            var ser = $scope.Selected.key;
            var n = ser.search('img');
            if (n != -1) {
                $scope.AddItem = {};
                $scope.AddItem.photo = baseURL + '../../' + $scope.Selected.value[0];
                $scope.FileInputShow = true;
            } else {
                $scope.FileInputShow = false;
                $scope.AddItem = {};
                $scope.AddItem.photo = '';
            }
            if (ser == 'Sections') {
                $scope.SelectedInput = true;
            } else {
                $scope.SelectedInput = false;
            }
            $scope.Key = $scope.Selected.key;
            $scope.Edit = $scope.Selected.value;
            $scope.ShowKeyValue = false;
            $scope.EditKeyValue = true;
            $('#KeyValue').modal();
        };

        ///////////////////////////
        // Request Method
        //////////////////////////
        $http.get(baseURL + 'ReadYaml').success(function(response) {
            $scope.data = response;
            data = response;
        }).error(function(response) {
            NotifyCation('جدول مقدارها', 'مشکل در برقراری ارتباط با سرور', 'error', true);
        });

        $scope.SendEditKeyValue = function() {
            var attr = $scope.Selected.value;

            var length = 0;
            for (var property in attr) {
                if (property !== '$$hashKey')
                {
                    if ($scope.Selected.key == 'Sections') {
                        if (property != 'label') {
                            $scope.Selected.value[property] = $('.edits' + property).val();
                        }
                    } else {
                        $scope.Selected.value[property] = $('.edit' + property).val();
                    }
                }
                if (attr.hasOwnProperty(property)) {
                    length++;
                }
            }
            console.log($scope.Selected.value);

            var send = {
                data: $scope.data
            };

            $http({
                method: 'post',
                url: baseURL + 'WriteYaml',
                data: send
            }).success(function(response) {
                console.log(send);
                var msg = response.msg.split(';');
                NotifyCation(msg[0], msg[1], msg[2], msg[3]);
//                console.log($scope.myFile);
                if ($scope.AddItem.photo == undefined || $scope.AddItem.photo == '') {
//                alert('undefined');
                } else {
                    $scope.Selected.photo = $scope.AddItem.photo;
                    $http({
                        method: 'post',
                        url: baseURL + 'WriteYaml',
                        data: $scope.Selected
                    }).success(function(response) {
                        $scope.data = response;
                        $scope.myFile = '';
                    }).error(function(response) {
                        NotifyCation('جدول مقدارها', 'مشکل در ارسال تصویر', 'error', true)
                    });
                }
                $('#KeyValue').modal('hide');
            }).error(function(response) {
                NotifyCation('جدول مقدارها', 'مشکل در برقراری ارتباط با سرور', 'error', true);
            });
        };

    }]);







