'use strict';
/* Controllers */
var app = angular.module('myApp.controllers', []);

app.run(function ($rootScope) {
    $rootScope.$on('scope.stored', function (event, data) {
    });
});
app.controller('gallery', ['$scope', '$http', 'Scopes', '$routeParams', function ($scope, $http, Scopes, $routeParams) {
        Scopes.store('gallery', $scope);
        if ($routeParams.id) {
            var GalleryId = $routeParams.id;
        }
        var after = 0;
        $scope.busyScroll = false;
        $scope.showMessage = "اطلاعاتی جهت نمایش موجود نمی باشد";
        $scope.entities = [];
        $scope.idImage = [];
        $scope.loadMore = function () {
            if ($scope.busyScroll)
            {
                return false;
            }

            $scope.busyScroll = true;
            var url = BaseUrl + "galleries/";
            if ($routeParams.id)
                url += GalleryId + "/" + after + "/8/id/desc/show";
            else
                url += "-1/" + after + "/8/id/desc";
            $http.get(url, {cache: true}).success(function (data) {

                var items = data.length;
                if (items > 0)
                {
                    $('.msg').fadeOut();
                    for (var i = 0; i < items; i++) {
                        $scope.idImage[data[i].id] = after;
                        $scope.entities.push(data[i]);
                        console.log(after);
                        after++;
                    }
                    if (items === 8)
                        $scope.busyScroll = false;
                    else
                        $('.msg').fadeIn();
                } else {
                    $('.msg').fadeIn();
                }
            });
        };
    }]);
app.controller('Page', ['$scope', '$http', 'Scopes', '$routeParams', function ($scope, $http, Scopes, $routeParams) {
        //accept to another controller for this controller Function 

        $('.msg').fadeOut();
        $scope.showMessage = "اطلاعاتی جهت نمایش موجود نمی باشد";
        Scopes.store('page', $scope);
        var id = $routeParams.id;
        //first query
        $http.get(BaseUrl + "pages/" + id, {cache: true}).success(function (data) {
            $scope.content = data;

        });

    }]);
app.constant('place', 'content');
app.controller('Content', ['$scope', '$http', 'Scopes', '$routeParams', function ($scope, $http, Scopes, $routeParams) {
        Scopes.store('content', $scope);
        var id = $routeParams.id;
        $scope.category = id;
        var after = 0;
        $scope.busyScroll = false;
        $scope.showMessage = "اطلاعاتی جهت نمایش موجود نمی باشد";
        $scope.ContentShow = [];
        $scope.categoryName = '';
        $scope.loadMore = function (IdCtg) {
            if ($scope.busyScroll)
            {
                return false;
            }

            $scope.busyScroll = true;
            var url = BaseUrl + "content/" + IdCtg + "/-1/" + after + "/9/id/asc";
            $http.get(url, {cache: true}).success(function (data) {

                var items = data.length;
                $scope.categoryName = data.ctg;
                if (items > 0)
                {
                    $('.msg').fadeOut();
                    for (var i = 0; i < items; i++) {
                        $scope.ContentShow.push(data[i]);
                        after++;
                    }
                    if (items === 9)
                        $scope.busyScroll = false;
                    else
                        $('.msg').fadeIn();
                } else {
                    $('.msg').fadeIn();
                }

            });
        };
    }]);
app.controller('ContentShow', ['$scope', '$http', '$routeParams', function ($scope, $http, $routeParams) {
        var idcontent = $routeParams.id;

        $('.msg').fadeOut();
        $scope.showMessage = "اطلاعاتی جهت نمایش موجود نمی باشد";
        $http.get(BaseUrl + "ContentShow/" + idcontent, {cache: true}).success(function (response) {
            $scope.content = response;

        });
    }])
app.controller('Doctors', ['$scope', '$http', '$routeParams', function ($scope, $http, $routeParams) {

        $http.get(BaseUrl + "expertise/FindAll", {cache: true}).success(function (response) {
            $scope.sections = response.sections;
            $scope.expertise = response.expertise;

        });

        $scope.SubmitSearch = function () {
            var val = $scope.SearchKey;
            $http.get(BaseUrl + "expertise/FindByName/" + val, {cache: true}).success(function (response) {
                $scope.Doctors = response.user;
                $scope.info = response.data;
            });
        };

        $scope.ShowExpertise = function (key, value) {
            $http.get(BaseUrl + "expertise/FindByExpertise/" + key, {cache: true}).success(function (response) {
                $scope.Doctors = response.user;
                $scope.info = response.data;
            });
        };

        $scope.ShowSections = function (key, value) {
            $http.get(BaseUrl + "expertise/FindBySections/" + key, {cache: true}).success(function (response) {
                $scope.Doctors = response.user;
                $scope.info = response.data;
            });
        };

    }]);

app.controller('DoctorsShow', ['$scope', '$http', '$routeParams', function ($scope, $http, $routeParams) {
        var IdDoctor = $routeParams.id;
        var time;
        $http.get(BaseUrl + "expertise/FindDoctorById/" + IdDoctor, {cache: true}).success(function (response) {
            $scope.Time = response.time;
            $scope.Certificate = response.certificate;
            $scope.user = response.user;
            $scope.expertise = response.expertise;
            for (var i = 0; i < response.LengthTime; i++) {
                if (response.time[i]['day'] == 'شنبه') {
                    var time = response.time[i]['hour'].split(':');
                    time[0] = 'صبح : ' + time[0];
                    time[1] = ' عصر: ' + time[1];
                    $scope.one = time[0] + '<br>' + time[1];
                }

                if (response.time[i]['day'] == 'یکشنبه') {
                    var time = response.time[i]['hour'].split(':');
                    time[0] = 'صبح : ' + time[0];
                    time[1] = ' عصر: ' + time[1];
                    $scope.tow = time[0] + '<br>' + time[1];
                }

                if (response.time[i]['day'] == 'دوشنبه') {
                    var time = response.time[i]['hour'].split(':');
                    time[0] = 'صبح : ' + time[0];
                    time[1] = ' عصر: ' + time[1];
                    $scope.three = time[0] + '<br>' + time[1];
                }

                if (response.time[i]['day'] == 'سه شنبه') {
                    var time = response.time[i]['hour'].split(':');
                    time[0] = 'صبح : ' + time[0];
                    time[1] = ' عصر: ' + time[1];
                    $scope.four = time[0] + '<br>' + time[1];
                }

                if (response.time[i]['day'] == 'چهارشنبه') {
                    var time = response.time[i]['hour'].split(':');
                    time[0] = 'صبح : ' + time[0];
                    time[1] = ' عصر: ' + time[1];
                    $scope.five = time[0] + '<br>' + time[1];
                }

                if (response.time[i]['day'] == 'پنج شنبه') {
                    var time = response.time[i]['hour'].split(':');
                    time[0] = 'صبح : ' + time[0];
                    time[1] = ' عصر: ' + time[1];
                    $scope.six = time[0] + '<br>' + time[1];
                }
            }
        });

    }])

app.controller('DoctorsReserve', ['$scope', '$http', '$routeParams', function ($scope, $http, $routeParams) {
        var IdDoctor = $routeParams.id;
        var time;
        $http.get(BaseUrl + "expertise/FindDoctorByIdReserve/" + IdDoctor, {cache: true}).success(function (response) {
            $scope.Time = response.time;
            $scope.user = response.user;
            console.log(response.user);
            for (var i = 0; i < response.LengthTime; i++) {
                if (response.time[i]['day'] == 'شنبه') {
                    var time = response.time[i]['hour'].split(':');
                    time[0] = 'صبح : ' + time[0];
                    time[1] = ' عصر: ' + time[1];
                    $scope.one = time[0] + '<br>' + time[1];
                }

                if (response.time[i]['day'] == 'یکشنبه') {
                    var time = response.time[i]['hour'].split(':');
                    time[0] = 'صبح : ' + time[0];
                    time[1] = ' عصر: ' + time[1];
                    $scope.tow = time[0] + '<br>' + time[1];
                }

                if (response.time[i]['day'] == 'دوشنبه') {
                    var time = response.time[i]['hour'].split(':');
                    time[0] = 'صبح : ' + time[0];
                    time[1] = ' عصر: ' + time[1];
                    $scope.three = time[0] + '<br>' + time[1];
                }

                if (response.time[i]['day'] == 'سه شنبه') {
                    var time = response.time[i]['hour'].split(':');
                    time[0] = 'صبح : ' + time[0];
                    time[1] = ' عصر: ' + time[1];
                    $scope.four = time[0] + '<br>' + time[1];
                }

                if (response.time[i]['day'] == 'چهارشنبه') {
                    var time = response.time[i]['hour'].split(':');
                    time[0] = 'صبح : ' + time[0];
                    time[1] = ' عصر: ' + time[1];
                    $scope.five = time[0] + '<br>' + time[1];
                }

                if (response.time[i]['day'] == 'پنج شنبه') {
                    var time = response.time[i]['hour'].split(':');
                    time[0] = 'صبح : ' + time[0];
                    time[1] = ' عصر: ' + time[1];
                    $scope.six = time[0] + '<br>' + time[1];
                }
            }
        });

    }])
angular.module('myApp.filters', []).
        filter('htmlToPlaintext', function () {
            return function (text) {
                return  text ? String(text).replace(/<[^>]+>/gm, '') : '';
            };
        }
        );

function loadScript(url, callback)
{
    // Adding the script tag to the head as suggested before
    var head = document.getElementsByTagName('head')[0];
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = url;

    // Then bind the event to the callback function.
    // There are several events for cross browser compatibility.
    script.onreadystatechange = callback;
    script.onload = callback;

    // Fire the loading
    head.appendChild(script);
}

app.factory('Scopes', function ($rootScope) {
    var mem = {};

    return {
        store: function (key, value) {
            $rootScope.$emit('scope.stored', key);
            mem[key] = value;
        },
        get: function (key) {
            return mem[key];
        }
    };
});

app.controller('MicroChip', ['$http', '$scope', function ($http, $scope) {
        $scope.UserShow = true;
        $scope.AnimalShow = false;
        var data;
        $scope.UserInfo = {};
        $scope.AnimalInfo = {};
        $scope.UserInfoSend = function () {
            $scope.UserInfo = $scope.user;
            console.log($scope.UserInfo);
            $http.get(BaseUrl + "MicroChip/getAllCtg").success(function (response) {
                if (response != "No Data") {
                    $scope.ctg = response.ctg;
                }
            }).error(function () {

            });
            $scope.UserShow = false;
            $scope.AnimalShow = true;
        }

        $scope.AnimalInfoSend = function () {
            $scope.AnimalInfo = $scope.animal;
            $scope.AnimalInfo.age = $('.age').val();
            if ($scope.AnimalInfo.age != undefined) {
                var da = $scope.AnimalInfo.age;
                da = da.split("/");
                if (da[0].length == 2)
                {
                    $scope.AnimalInfo.age = da[2] + "/" + da[1] + "/" + da[0];
                }
            }
            console.log($scope.AnimalInfo);
            if ($('.file_send').val() != '') {
                base64($('.file_send'), function (data) {
                    $scope.animal.photo = "data:image/jpeg;base64," + data.base64;
                });
            }
            $scope.UserShow = false;
            $scope.AnimalShow = false;

        }

        $scope.ShowUser = function () {
            $scope.UserShow = true;
            $scope.AnimalShow = false;
        };

        $scope.ShowAnimal = function () {
            $scope.UserShow = false;
            $scope.AnimalShow = true;
        }

        $scope.SendAllInfo = function () {
            data = {
                'user': $scope.UserInfo,
                'animal': $scope.AnimalInfo
            };
            $http({
                method: 'post',
                url: BaseUrl + 'MicroChip/InsertMicroChip',
                data: data
            }).success(function (response) {
                $scope.error = true;
                $scope.ErrorMsg = response.msg;
                $scope.ErrorMsg = $scope.ErrorMsg.split(';');
                $scope.TypeError = $scope.ErrorMsg[0];
                if ($scope.TypeError == "success") {
                    $scope.SendOk = true;
                }
                $scope.ErrorMsg = $scope.ErrorMsg[1];
            }).error(function () {

            })
        }
    }]);

app.directive('angularMask', function () {
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

function convertImgToDataURLviaCanvas(url, callback, outputFormat) {
    var img = new Image();
    img.crossOrigin = 'Anonymous';
    img.onload = function () {
        var canvas = document.createElement('CANVAS');
        var ctx = canvas.getContext('2d');
        var dataURL;
        canvas.height = this.height;
        canvas.width = this.width;
        ctx.drawImage(this, 0, 0);
        dataURL = canvas.toDataURL(outputFormat);
        callback(dataURL);
        canvas = null;
    };
    img.src = url;
}

function convertFileToDataURLviaFileReader(url, callback) {
    var xhr = new XMLHttpRequest();
    xhr.responseType = 'blob';
    xhr.onload = function () {
        var reader = new FileReader();
        reader.onloadend = function () {
            callback(reader.result);
        }
        reader.readAsDataURL(xhr.response);
    };
    xhr.open('GET', url);
    xhr.send();
}

//Author James Harrington 2014
function base64(file, callback) {
    var coolFile = {};
    function readerOnload(e) {
        var base64 = btoa(e.target.result);
        coolFile.base64 = base64;
        callback(coolFile)
    }
    ;

    var reader = new FileReader();
    reader.onload = readerOnload;

    var file = file[0].files[0];
    coolFile.filetype = file.type;
    coolFile.size = file.size;
    coolFile.filename = file.name;
    reader.readAsBinaryString(file);
}