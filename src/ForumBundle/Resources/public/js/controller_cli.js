'use strict';


// Declare app level module which depends on filters, and services
angular.module('myApp', ['myApp.filters', 'ui.tinymce', 'myApp.directives', 'myApp.controllers', 'ngCookies', 'infinite-scroll']).
        config(['$routeProvider', function ($routeProvider) {
                $routeProvider.when('/', {templateUrl: '../bundles/forum/partials/client/GroupForum.html', controller: 'ListForum'});
                $routeProvider.when('/showpost/:id', {templateUrl: '../bundles/forum/partials/client/PostComment.html', controller: 'ListPosts'});
                $routeProvider.when('/showpost/:id/:offset', {templateUrl: '../bundles/forum/partials/client/PostComment.html', controller: 'ListPosts'});
            }]);

/* Directives */

angular.module('myApp.directives', []).
        directive('loading', ['$http', function ($http)
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
        .directive("formPost", function ($compile, $http) {

            var title = '<input ng-model="title" ng-disabled="disable" style="color:rgba(0,0,0,0.7)" class="input textbox" data-toggle="tooltip" data-placement="bottom" title="عنوان" type="text" name="title" placeholder="عنوان" />';
            var submit = '<button ng-model="submit" ng-disabled="disable" type="submit" class="input btnprimary" ng-click="newPost()" >{{submit}}</button>';
            var editor = '<textarea ng-disabled="disable" data-ui-tinymce id="tinymce2" data-ng-model="mypost"></textarea>';
            return{
                controller: 'ListPosts',
                link: function (scope, element) {
                    $(document).on('click', '#addpostform', function () {
                        $('.insert').fadeIn();
                        $(element).html("<div style='text-align:center;' loading><img width='60px' src='" + BaseUrl + "/bundles/public/img/loading.gif'/></div>");
                        $http({
                            method: 'GET',
                            url: BaseUrl + '/aut/client.json',
                            headers: {'Content-Type': 'application/json;charset=utf-8;'}
                        }).then(function successCallback(res) {
                            if (res.data === 'false') {
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

// defined ng-model tinymce editor
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

/* Filters */

angular.module('myApp.filters', []).
        filter('interpolate', ['version', function (version) {
                return function (text) {
                    return String(text).replace(/\%VERSION\%/mg, version);
                }
            }]);
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
/* Controllers */
angular.module('myApp.controllers', ['ngCookies'])
        // THIS CONTROLLER FOR INSERT POST TO POSTS TABLE (Client Side)
        .controller('ListPosts', ['$cookieStore', '$routeParams', '$scope', '$http', function ($cookieStore, $routeParams, $scope, $http) {

                //checking parameter root for
                var parameterRoot = $routeParams.id;
                
                $scope.submit = 'افزودن';
                $scope.AllComment = [];
                $scope.disable = false;
                $scope.commentMsg = [];
                $scope.addcoment = [];
//                alert(isLogin);
                $scope.test = isLogin;
                $scope.listCommentShow = [];
                $scope.ClickForShowComment = function (idPost) {
                    //console.log($scope.ClickForShowComment);

                    if (!$scope.listCommentShow[idPost])
                    {
                        $scope.listCommentShow[idPost] = true;
                        $http.get(BaseUrl + '/comments/' + idPost + '/by/posts/client.json').success(function (data) {
                            $scope.AllComment[idPost] = data;

                            //console.log($scope.ClickForShowComment.AllComment);
                        });
                    }

                };            
                $scope.addComment = function (idPost) {
                    if ($scope.commentMsg[idPost] === "undefined" || !$scope.commentMsg[idPost].length || $scope.commentMsg[idPost].length < 3)
                        return;
                    $('body , *').css({'cursor': 'wait'});
                    var data = [];
                    data = {"comment":
                                {
                                    "title": $scope.forum,
                                    "content": $scope.commentMsg[idPost],
                                    "enabled": true,
                                    "post": idPost
                                }
                    };
                    $http({
                        method: 'POST',
                        url: BaseUrl + '/comments.json',
                        headers: {'Content-Type': 'application/json;charset=utf-8;'},
                        data: data
                    }).then(function successCallback(response) {
                        if (response.data.RES === "403") {
                            $scope.commentMsg[idPost] = 'مجوز افزودن کامنت برای شما وجود ندارد.';
                        } else if (response.data.RES === "200") {
                            $scope.commentMsg[idPost] = '';
                            $scope.AllComment[idPost].push(response.data.comment);
                        } else {
                            $scope.commentMsg[idPost] += "\n" + "اطلاعات شما نامعتبر است";
                        }
                        $('body , *').css({'cursor': 'default'});
                    }, function errorCallback(response) {
                        $scope.commentMsg[idPost] += "\n" + "سرور با خطا روبرو شد.";
                        $('body , *').css({'cursor': 'default'});
                    });
                };

                $scope.disable = false;

                //check cookie for  cheking liked by user
                $scope.like = [];
                $scope.CookieSetLikeValue = function (idPost) {

                    var items = [];

                    var checkCookie = $cookieStore.get('items');
                    if (!checkCookie)
                    {
                        //Write Cookie if not exist
                        items.push(idPost);
                        var added = JSON.stringify(items);
                        $cookieStore.put('items', added);
                        //posts/{id}/insert/like/client.{_format} 
                        $http.get(BaseUrl + '/posts/' + idPost + '/insert/like/client.json').success(function () {
                            // alert("Like inserted");
                        });

                        $http.get(BaseUrl + '/posts/' + idPost + '/like/client.json').success(function (data) {
                            // $("#em" + idPost).html(data.like);
                            $scope.like[idPost] = data.like;
                        });

                    } else
                    {
                        var itemarr = $cookieStore.get('items');
                        var insertedIf = itemarr.indexOf(idPost);

                        if (insertedIf < 0)
                        {
                            items.push(idPost);
                            var added = JSON.stringify(items);
                            $cookieStore.put('items', added);
                            //posts/{id}/insert/like/client.{_format}  
                            $http.get(BaseUrl + '/posts/' + idPost + '/insert/like/client.json', function (data) {

                            }).success(function () {
                                //   alert("Like inserted");
                            });
                            ///posts/{id}/like/client

                            $http.get(BaseUrl + '/posts/' + idPost + '/like/client.json').success(function (data) {
//                                $(document).on("click","#em" + idPost,function (){
//                                    $(this).html(data.like)
//                                });
                                //$("#em" + idPost).html(data.like);
                                $scope.like[idPost] = data.like;
                                console.log(data);
                            });
                        } else
                        {
                            console.log($cookieStore.get('items') + "شما قبلا لایک کردید");
                        }
                    }
                };

                //check cookie for  cheking liked Comment by user
                $scope.likeComment = [];
                $scope.CookieSetLikeCommentValue = function (idComment) {

                    var items = [];

                    var checkCookie = $cookieStore.get('itemsComment');
                    if (!checkCookie)
                    {
                        //Write Cookie if not exist
                        items.push(idComment);
                        var added = JSON.stringify(items);
                        $cookieStore.put('itemsComment', added);
                        //posts/{id}/insert/like/client.{_format} 
                        $http.get(BaseUrl + '/comments/' + idComment + '/insert/like/client.json').success(function () {
                            // alert("Like inserted");
                        });
                        ///comments/{id}/like/client.{_format}
                        $http.get(BaseUrl + '/comments/' + idComment + '/like/client.json').success(function (data) {
                            // $("#em" + idPost).html(data.like);
                            $scope.likeComment[idComment] = data.like;
                        });

                    } else
                    {
                        var itemarr = $cookieStore.get('itemsComment');
                        var insertedIf = itemarr.indexOf(idComment);

                        if (insertedIf < 0)
                        {
                            items.push(idComment);
                            var added = JSON.stringify(items);
                            $cookieStore.put('itemsComment', added);

                            $http.get(BaseUrl + '/comments/' + idComment + '/insert/like/client.json').success(function () {
                                //   alert("Like inserted");
                            });

                            $http.get(BaseUrl + '/comments/' + idComment + '/like/client.json').success(function (data) {
                                $scope.likeComment[idComment] = data.like;
                                console.log(data);
                            });
                        } else
                        {
                            console.log($cookieStore.get('itemsComment') + "شما قبلا لایک کردید");
                        }
                    }
                };

                $scope.forum = '';
                //scroll pagination click read more
                $scope.myoffset=-1;
                if($routeParams.offset){
                    $scope.myoffset=$routeParams.offset;
                }
                
                var after = 0;
                $scope.busyScroll = false;
                $scope.PostsShow = [];

                $scope.loadMore = function () {

                    if ($scope.busyScroll)
                    {
                        return false;
                    }
                    $scope.busyScroll = true;

                    var url = BaseUrl + '/posts/' + after + '/paginations/' + parameterRoot + '/scrolls/'+$scope.myoffset+'.json';
                    $http.get(url).success(function (data) {
                        $scope.myoffset=-1;
                        if (data.forum && data.forum.title)
                            $scope.forum = data.forum.title;
                        var items = data.entities.length;
                        if (items > 0)
                        {
                            for (var i = 0; i < items; i++) {
                                $scope.PostsShow.push(data.entities[i]);
                                after++;
                            }
                            if (items < 5) {
                                $scope.busyScroll = true;
                                $scope.showMessage = "اطلاعاتی جهت نمایش موجود نمی باشد";
                            } else {
                                $scope.busyScroll = false;
                            }
                        } else
                        {
                            $scope.busyScroll = true;
                            $scope.showMessage = "اطلاعاتی جهت نمایش موجود نمی باشد";
                        }

                    });
                };

                $scope.newPostClick = function () {

                };
                $scope.newPost = function () {
                    if ($scope.title.length < 3 || $scope.mypost < 10)
                        return;
                    $scope.submit = 'در حال ارسال ...';
                    $scope.disable = true;
                    tinymce.activeEditor.getBody().setAttribute('contenteditable', false);
                    var data = [];
                    data = {"post":
                                {
                                    "title": $scope.title,
                                    "content": $scope.mypost,
                                    "group": parameterRoot}
                    };

                    $http({
                        method: 'POST',
                        url: BaseUrl + '/posts.json',
                        headers: {'Content-Type': 'application/json;charset=utf-8;'},
                        data: data
                    }).then(function successCallback(response) {
                        $scope.title='';
                        $scope.mypost='';
                        if (response.data.RES === "403") {
                            $('.insert').html("<h5 style='text-align:center'>مجوز ایجاد پست برای شما وجود ندارد.</h5>");
                        } else if (response.data.RES === "200") {
                            $('.insert').html("<h5 style='text-align:center'>پست شما با موفقیت ایجاد شد...</h5>");
                            var c = $scope.PostsShow.length + 1;
                            $scope.PostsShow.splice(0, 0, response.data.post);
                            //$scope.PostsShow.push(response.data.post);
                        } else {
                            $scope.disable = false;
                            tinymce.activeEditor.getBody().setAttribute('contenteditable', true);
                            $scope.mypost += "<h2 style='color:red;text-align:center' >اطلاعات شما نامعتبر است</h2>";
                            $scope.submit = 'ارسال مجدد';
                        }
                    }, function errorCallback(response) {
                        $('.insert').html("<h5 style='text-align:center'>مجوز ایجاد پست برای شما وجود ندارد..</h5>");
                    });

                };
            }])
        .controller('ListForum', ['$scope', '$http', function ($scope,$http) {
                $http.get(BaseUrl + "/forumgroupitemcount.json")
                        .success(function (response) {
                            $scope.ClientGroupData = response;
                        });
            }]);

