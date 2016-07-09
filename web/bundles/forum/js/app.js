'use strict';
// Declare app level module which depends on filters, and services
angular.module('myApp', ['myApp.filters', 'myApp.services', 'ui.tinymce', 'myApp.directives', 'myApp.controllers', 'ngSanitize', 'ngCookies', 'infinite-scroll']).
        config(['$routeProvider', function ($routeProvider) {

                $routeProvider.when('/login', {templateUrl: '../bundles/forum/partials/login.html', controller: 'Login'});
                //admin route
                $routeProvider.when('/Comments', {templateUrl: '../bundles/forum/partials/Comments.html', controller: 'Comments'});
                $routeProvider.when('/Comments/:id', {templateUrl: '../bundles/forum/partials/Comments.html', controller: 'Comments'});
                $routeProvider.when('/ForumGroup', {templateUrl: '../bundles/forum/partials/GroupForum.html', controller: 'ForumGroup'});
                $routeProvider.when('/Posts', {templateUrl: '../bundles/forum/partials/Post.html', controller: 'Posts'});
                $routeProvider.when('/Posts/:id', {templateUrl: '../bundles/forum/partials/Post.html', controller: 'Posts'});
                //Client Route
                $routeProvider.when('/', {templateUrl: '../bundles/forum/partials/client/GroupForum.html', controller: 'ListForum'});
                //http://127.0.0.1:8000/forum/#/ListForum/showpost/8
                $routeProvider.when('/showpost/:id', {templateUrl: '../bundles/forum/partials/client/PostComment.html', controller: 'ListPosts'});
            }])
        // This Function for delete Confirm()
        .directive('ngConfirmClick', [
            function () {
                return {
                    link: function (scope, element, attr) {
                        var msg = attr.ngConfirmClick || "آیا مطمئن هستید؟";
                        var clickAction = attr.confirmedClick;
                        element.bind('click', function (event) {
                            if (window.confirm(msg)) {
                                scope.$eval(clickAction);
                            }
                        });
                    }
                };
            }])

//change element value
        .directive('testChange', function () {
            return function (scope, element, attrs) {
                element.bind('change', function () {
                    console.log('value changed');
                });
            };
        })
        //show loading page for all ajax (angularJS) request
        .directive('loading', ['$http', function ($http)
            {
                return {
                    restrict: 'A',
                    link: function (scope, elm, attrs)
                    {
                        $(elm).find('img').attr('src',BaseUrl+'/bundles/public/img/loading.gif');
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




//        .directive('getTranslatedText', function ($http) {
//            return {
//                scope: {
//                    categoryID: '@catId',
//                    categoryDetailID: '@catDetailId',
//                    languageID: '@langId'
//                },
//                link: function (scope, element, attrs) {
//                    $http.get('api/datacategorydetailtranslations' + '/' + scope.categoryID + '/' + scope.categoryDetailID + '/' + scope.languageID)
//                            .success(function (data) {
//                                scope.translated = data.datacategorydetailtranslations;
//                            });
//                },
//                template: '<span itemprop="location">{{translated}}</span>'
//            }
//        });


        .directive('x', ['TokenHandler', '$http', '$rootScope', function (TokenHandler, $http, $rootScope) {
                //var parameterUrlCount = BaseUrl + "/api/posts/-1/filters/" + parameter + "/group/count.json";
                var cc;
                return {
                    replace: true,
                    restrict: "A",
                    link: function (scope, element, arrts)
                    {
                        scope.$watch('gid', function (value) {
                            console.log(arrts['gid']);
                            $http.get(BaseUrl + "/grouppostscounts/" + arrts['gid'] + "/client.json",
                                    {headers: {'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)}}).success(function (response) {
                                scope.cc = response.Count;
                                // cc = response.Count;
                            });

                        });


                    }
                }
            }])
                .directive("formPost", function ($compile, $http) {

    var title = '<input ng-model="title" ng-disabled="disable" style="color:rgba(0,0,0,0.7)" class="input textbox" data-toggle="tooltip" data-placement="bottom" title="عنوان" type="text" name="title" placeholder="عنوان" />';
    var submit = '<button ng-model="submit" ng-disabled="disable" type="submit" class="input btnprimary" ng-click="newPost()" >{{submit}}</button>';
    var editor = '<textarea ng-disabled="disable" data-ui-tinymce id="tinymce2" data-ng-model="mypost"></textarea>';
    return{
        controller: 'ListPosts',
        link: function (scope, element) {
            $(document).on('click', '#addpostform', function () {
                $('.insert').fadeIn();
                $(element).html("<div style='text-align:center;' loading><img width='60px' src='"+BaseUrl+"/bundles/public/img/loading.gif'/></div>");
                $http({
                    method: 'GET',
                    url: BaseUrl + '/aut/client.json',
                    headers: {'Content-Type': 'application/json;charset=utf-8;'}
                }).then(function successCallback(res) {
                    if (res.data==='false') {
                        $(element).html("<h5 style='text-align:center'>برای ایجاد پست جدید باید لاگین کنید.</h5>");
                        return;
                    } else {
                        var div = document.createElement('div');
                        $(div).addClass("row");
                        var div2 = document.createElement('div');
                        $(div2).addClass("col-lg-2 col-md-3 col-sm-4 col-xs-12");
                        var inputSubmit = $compile(submit)(scope);
                        $(div2).append(inputSubmit);
                        var div3 = document.createElement('div');
                        $(div3).addClass("col-lg-10 col-md-9 col-sm-8 col-xs-12");
                        var inputTitle = $compile(title)(scope);
                        $(div3).append(inputTitle);
                        $(div).append(div2);
                        $(div).append(div3);
                        var message = $compile(editor)(scope);
                        $(element).html('');
                        $(element).append(div).append(message);
                    }
                }, function errorCallback(response) {
                    alert('خطایی موجود است');
                });
            });
        }
    };
});
//darash Comment
// this filter for content cut 

angular.module('ng').filter('cut', function () {
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
});
//darash Comment
//This For set text checkbox
angular.module('ng').filter('active', function () {
    return function (value) {
        if (value) {
            return 'فعال';
        } else
        {
            return 'غیر فعال';
        }
    };
});
//darash Comment  
// This Filter For Remove Tag HTML  
angular.module('ng').filter('htmlToPlaintext', function () {
    return function (text) {
        return  text ? String(text).replace(/<[^>]+>/gm, '') : '';
    };
}
);
// This Filter For Remove Tag HTML  
angular.module('ng').filter('ConvertedToDateShamsi', function () {
    return function (dateString) {
        if (dateString != null)
        {
            return moment(dateString, 'YYYY-M-D HH:mm:ss').format('jYYYY/jM/jD HH:mm:ss');
        } else {
            return "تاریخ ندارد"
        }
    };
}
);
//this filter for check null value
angular.module('ng').filter('CheckNullValue', function () {
    return function (text) {
        if (text == null)
        {
            return '';
        } else
        {
            return text;
        }
    };
});
angular.module('ui.tinymce', [])
        .value('uiTinymceConfig', {
            directionality: 'rtl',
            language: 'fa_IR',
            statusbar: false,
            hidden_input: false,
            theme: 'modern',
            width: $('.ItemForum').width(),
            height: 300,
            menubar: false,
            element_format: 'html',
            plugins: [
                'advlist autolink link lists hr anchor pagebreak',
                'visualchars insertdatetime nonbreaking',
                'directionality emoticons textcolor'
            ],
            toolbar: 'styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link | forecolor backcolor emoticons'
        })
        .directive('uiTinymce', ['uiTinymceConfig', function (uiTinymceConfig) {
                uiTinymceConfig = uiTinymceConfig || {};
                var generatedIds = 0;
                return {
                    require: 'ngModel',
                    link: function (scope, elm, attrs, ngModel) {
                        var expression, options, tinyInstance;
                        // generate an ID if not present
                        if (!attrs.id) {
                            attrs.$set('id', 'uiTinymce' + generatedIds++);
                        }
                        options = {
                            // Update model when calling setContent (such as from the source editor popup)
                            setup: function (ed) {
                                ed.on('init', function (args) {
                                    ngModel.$render();
                                });
                                // Update model on button click
                                ed.on('ExecCommand', function (e) {
                                    ed.save();
                                    ngModel.$setViewValue(elm.val());
                                    if (!scope.$$phase) {
                                        scope.$apply();
                                    }
                                });
                                // Update model on keypress
                                ed.on('KeyUp', function (e) {
                                    ed.save();
                                    ngModel.$setViewValue(elm.val());
                                    if (!scope.$$phase) {
                                        scope.$apply();
                                    }
                                });
                            },
                            mode: 'exact',
                            elements: attrs.id
                        };
                        if (attrs.uiTinymce) {
                            expression = scope.$eval(attrs.uiTinymce);
                        } else {
                            expression = {};
                        }
                        angular.extend(options, uiTinymceConfig, expression);
                        setTimeout(function () {
                            tinymce.init(options);
                        });


                        ngModel.$render = function () {
                            if (!tinyInstance) {
                                tinyInstance = tinymce.get(attrs.id);
                            }
                            if (tinyInstance) {
                                tinyInstance.setContent(ngModel.$viewValue || '');
                            }
                        };
                    }
                };
            }]); 