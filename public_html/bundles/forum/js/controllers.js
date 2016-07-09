'use strict';

/* Controllers */


angular.module('myApp.controllers', ['ngCookies']).
        controller('Login', ['$rootScope', '$scope', '$window', '$cookies', 'Salt', 'Digest', function ($rootScope, $scope, $window, $cookies, Salt, Digest) {

                // On Submit function

                $scope.getSalt = function () {
                    var username = $scope.username;
                    var password = $scope.password;

                    // Get Salt
                    Salt.get({username: username}, function (data) {
                        var salt = data.salt;
                        var userID = data.userID;
                        $cookies.authid = "U/" + userID;

                        // alert(userID);
                        // Encrypt password accordingly to generate secret
                        Digest.cipher(password, salt).then(function (secret) {
                            // Display salt and secret for this example
                            $scope.salt = salt;
                            $scope.secret = secret;

                            // Store auth informations in cookies for page refresh
                            $cookies.username = $scope.username;
                            $cookies.secret = secret;
                            // Store auth informations in rootScope for multi views access
                            $rootScope.userAuth = {username: $scope.username, secret: $scope.secret};

                        }, function (err) {
                            console.log(err);
                        });
                    });
                };
            }])


//        **************************************************************************************************
//        *
//        * 
//        *                                       Comments Controller
//        *
//        *
//        **************************************************************************************************
//      //controller for admin side 
        .controller('Comments', ['$routeParams', '$rootScope', '$scope', '$window', '$cookies', 'Hello', 'Salt', '$http', 'TokenHandler', 'paginationCreateArray', 'getCountPagination', function ($routeParams, $rootScope, $scope, $window, $cookies, Hello, Salt, $http, TokenHandler, paginationCreateArray, getCountPagination) {
                // If auth information in cookie
                if (typeof $cookies.username != "undefined" && typeof $cookies.secret != "undefined") {
                    $rootScope.userAuth = {username: $cookies.username, secret: $cookies.secret};

                }

                if (typeof $rootScope.userAuth == "undefined") {
                    $window.location = '#/login';
                    return;
                }

                // main of controller
                var parameterRoot = $routeParams.id;

                //for show element select post on insert form
                $scope.postIditem = String(parameterRoot);


                //variable for pagination comments
                var count, orderBy, field;
                orderBy = '';
                field = '';

                //init pagination value
                var current = 1;
                var constPageItems = 10;
                var allPage = 0;
                var row = new Array();




                // console.log($scope.postIditem);
                var parameterUrl = "";  // Filter By 0 or -1
                var parameterUrlCount = ""; // json get filtered count


                //check undefined in html
                $scope.isUndefined = function (thing) {
                    return (thing == "undefined");
                }

                //console.log("isUndefined = " + $scope.isUndefined($scope.postIditem));



                //check route parameter AngularJS
                //example other :: 
                /*
                 * if ($scope.isUndefined(parameterRoot) == "undefined")
                 * 
                 */
                if (typeof parameterRoot == "undefined")
                {
                    parameterUrl = BaseUrl + "/api/comments/-1/filters/0/offsets/" + constPageItems + "/limits/id/orders/asc.json";
                    //api/comments/{filter}/count/filter.{_format}
                    parameterUrlCount = BaseUrl + "/api/comments/-1/count/filter.json";

                    //for combobox insert form
                    $http.get(BaseUrl + "/api/posts.json",
                            {headers: {'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)}}).success(function (response) {
                        $scope.allpost = response;
                    });


                } else
                {
                    //api/comments/{filter}/filters/{offset}/offsets/{limit}/limits/{attr}/orders/{asc}/posts/{itemGroup}.{_format} 
                    parameterUrl = BaseUrl + "/api/comments/-1/filters/0/offsets/" + constPageItems + "/limits/id/orders/asc/posts/" + parameterRoot + ".json";
                    //api/comments/{filter}/filters/{itemGroup}/post/count.{_format}
                    parameterUrlCount = BaseUrl + "/api/comments/-1/filters/" + parameterRoot + "/post/count.json";

                    $http.get(BaseUrl + "/api/posts/" + parameterRoot + ".json",
                            {headers: {'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)}}).success(function (response) {
                        $scope.breaditemPost = response;
                        console.log(response);
                    });

                }


                $http.get(parameterUrl,
                        {headers: {'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)}}).success(function (response) {
                    current = 1;
                    $scope.commentRows = response;
                });


                $scope.RefreshPagination = function () {
                    //alert(parameterUrl);
                    getCountPagination.TableCount(parameterUrlCount).get({username: $rootScope.userAuth.username, secret: $rootScope.userAuth.secret}, function (data) {
                        console.log(data.count);
                        allPage = Math.floor(data.count / constPageItems); //Math.floor(CountItems / constPageItems);
                        $scope.Allpaginate = paginationCreateArray.array(row, data.count, constPageItems, current);
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
                            if (typeof parameterRoot == "undefined")
                            {
                                parameterurlPagination = BaseUrl + "/api/comments/-1/filters/" + offset + "/offsets/" + constPageItems + "/limits/" + field + "/orders/" + orderBy + ".json";
                            } else
                            {   //api/comments/{filter}/filters/{offset}/offsets/{limit}/limits/{attr}/orders/{asc}/posts/{itemGroup}.{_format} 
                                parameterurlPagination = BaseUrl + "/api/comments/-1/filters/" + offset + "/offsets/" + constPageItems + "/limits/" + field + "/orders/" + orderBy + "/posts/" + parameterRoot + ".json";
                            }

                            $http.get(parameterurlPagination,
                                    {headers: {'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)}}).success(function (response) {
                                $scope.RefreshPagination();
                                $scope.commentRows = response;

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

                    });

                };

                //delete record of table
                $scope.deleteitemComment = function (item) {
                    $http({
                        method: 'DELETE',
                        url: BaseUrl + '/api/comments/' + item + '.json',
                        headers: {'Content-Type': 'application/json;charset=utf-8;', 'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)},
                        //data: data
                    }).then(function successCallback(response) {
                        $scope.paginate(current);
                        //$(".ajaxloader").fadeOut(500);
                    }, function errorCallback(response) {
                        alert('خطایی موجود است');
                        //$(".ajaxloader").fadeOut(500);
                    });
                };
                //insert record of table
                $scope.submitaddComment = function () {
                    var data = [];


                    if ($scope.isUndefined($scope.postIditem))
                    {
                        data = {"comment":
                                    {
                                        "title": $scope.title,
                                        "content": CKEDITOR.instances.content.getData(),
                                        "enabled": $scope.enabled,
                                        "post": $scope.postIiemSelect
                                    }
                        };
                        // console.log("Post ID:"+$scope.postIiemSelect);
                    } else
                    {
                        data = {"comment":
                                    {
                                        "title": $scope.title,
                                        "content": CKEDITOR.instances.content.getData(),
                                        "enabled": $scope.enabled,
                                        "post": $scope.postIditem
                                    }
                        };

                        // console.log("Post ID:"+$scope.postIditem);    
                    }


                    $http({
                        method: 'POST',
                        url: BaseUrl + '/api/comments.json',
                        headers: {'Content-Type': 'application/json;charset=utf-8;', 'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)},
                        data: data
                    }).then(function successCallback(response) {
                        $scope.paginate(current);
                    }, function errorCallback(response) {
                        alert('خطایی موجود است');
                    });

                };
                //edit record of table
                $scope.submiteditComment = function () {
                    var data = [];
//                    var userid = $cookies.authid;

//                    $http.get(BaseUrl + '/api/posts.json', {headers: {'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)}}).success(function (data) {
//                        $scope.PostsShow = data;
//                    });

                    ///api/comments 
                    data = {"comment":
                                {"title": $scope.EdittitleComment,
                                    "content": CKEDITOR.instances.EditContentComment.getData(),
                                    "enabled": $scope.Editenabled,
                                    "post": $scope.EditPostId
                                }
                    };

                    $http({
                        method: 'PUT',
                        url: BaseUrl + '/api/comments/' + $scope.EdititemId + '.json',
                        headers: {'Content-Type': 'application/json;charset=utf-8;', 'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)},
                        data: data
                    }).then(function successCallback(response) {
                        $scope.paginate(current);
                    }, function errorCallback(response) {
                        alert('مشکل در ویرایش پیش آمده است');
                    });

                }; // end submit function
                // 
                //call Pagination for first
                $scope.RefreshPagination();

            }])




//        **************************************************************************************************
//        *
//        * 
//        *                                       ForumGroup Controller
//        *
//        *
//        **************************************************************************************************
        //controller for admin side 
        .controller('ForumGroup', ['$rootScope', '$scope', '$window', '$cookies', 'Todos', '$http', 'TokenHandler', 'paginationCreateArray', 'getCountPagination', function ($rootScope, $scope, $window, $cookies, Todos, $http, TokenHandler, paginationCreateArray, getCountPagination) {
//              If auth information in cookie
                if (typeof $cookies.username != "undefined" && typeof $cookies.secret != "undefined") {
                    $rootScope.userAuth = {username: $cookies.username, secret: $cookies.secret};
                }
                // If not authenticated, go to login
                if (typeof $rootScope.userAuth == "undefined") {
                    $window.location = '#/login';
                    return;
                }

                //this function for insert new ForumGroup
                $scope.InsertForumGroup = function () {
                    var data = [];

                    if ($scope.ForumGroupenabled == null)
                    {
                        $scope.ForumGroupenabled = false;
                    }

                    if ($scope.ordernewitemshow == null)
                    {
                        $scope.ordernewitemshow = 0;
                    }

                    data = {"groupforum":
                                {"title": $scope.ForumGroupTitle,
                                    "meta": $scope.ForumGroupMeta,
                                    "orderList": $scope.ordernewitemshow,
                                    "enabled": $scope.ForumGroupenabled}};

                    console.log(data);

                    $http({
                        method: 'POST',
                        url: BaseUrl + '/api/groups/forums.json',
                        headers: {'Content-Type': 'application/json;charset=utf-8;', 'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)},
                        data: data
                    }).then(function successCallback(response) {
                        alert('بدرستی درج شد');
                        $(".ajaxloader").fadeOut(500);
                    }, function errorCallback(response) {
                        alert('خطایی در هنگام درج موجود است');
                        $(".ajaxloader").fadeOut(500);
                    });
                };
                //edit ForumGroup form
                $scope.EditForumGroup = function () {
                    var data = [];
                    var userid = $cookies.authid;

                    $http.get(BaseUrl + '/api/posts.json', {headers: {'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)}}).success(function (data) {
                        $scope.PostsShow = data;
                    });

                    data = {"groupforum":
                                {"title": $scope.ForumGroupTitleEdit,
                                    "meta": $scope.ForumGroupMetaEdit,
                                    "enabled": $scope.ForumGroupenabledEdit,
                                    "orderList": $scope.ordernewitemshowEdit
                                }
                    };
                    ///api/groups/{id}/forum
                    $http({
                        method: 'PUT',
                        url: BaseUrl + '/api/groups/' + $scope.EdititemId + '/forum.json',
                        headers: {'Content-Type': 'application/json;charset=utf-8;', 'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)},
                        data: data
                    }).then(function successCallback(response) {
                        $scope.paginate(current);
                    }, function errorCallback(response) {
                        alert('مشکل در ویرایش پیش آمده است');
                    });

                };
                // this function for order list set
                $scope.updateOrderList = function () {

                    var parameter;
                    var objArray = [];

                    $('.orderListIdLabel').each(function () {
                        if (!$(this).hasClass('badge'))
                        {
                            var id = $(this).attr('id');
                            var value = $(this).text();
                            var item = {};

                            item[id] = value;
                            objArray.push(item);
                        }
                    });

                    parameter = JSON.stringify(objArray);
                    parameter = parameter.replace("[", "");
                    parameter = parameter.replace("]", "");

                    for (var i = 0; i <= parameter.length; i++)
                    {
                        parameter = parameter.replace("{", "");
                        parameter = parameter.replace("}", "");
                    }

                    var data;
                    data = '{"orderId":{' + parameter.toString() + '}}';

                    console.log(data);

                    $http({
                        method: 'PUT',
                        url: BaseUrl + '/api/group/forum/by.json',
                        headers: {'Content-Type': 'application/json;charset=utf-8;', 'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)},
                        data: data
                    }).then(function successCallback(response) {

                        $(".ajaxloader").fadeOut(500);
                        alert('تغییرات ثبت شد');
                    }, function errorCallback(response) {
                        $(".ajaxloader").fadeOut(500);
                        alert('خطا');
                    });
                };
                //delete item of table
                $scope.deleteitem = function (item) {
                    $http({
                        method: 'DELETE',
                        url: BaseUrl + '/api/groups/' + item + '/forum.json',
                        headers: {'Content-Type': 'application/json;charset=utf-8;', 'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)},
                        //data: data
                    }).then(function successCallback(response) {
                        $scope.paginate(current);
                    }, function errorCallback(response) {
                        alert('خطایی موجود است');
                    });
                };


                //variable for pagination posts
                var count, orderBy, field;

                orderBy = '';
                field = '';

                //init pagination value
                var current = 1;
                var constPageItems = 5;
                var allPage = 0;
                var row = new Array();

                // main of controller

                var parameterUrl = "";  // Filter By 0 or -1
                var parameterUrlCount = ""; // json get filtered count

                ///api/groupforums/{filter}/filters/{offset}/offsets/{limit}/limits/{attr}/orders/{asc}.{_format} 
                parameterUrl = BaseUrl + "/api/groupforums/-1/filters/0/offsets/" + constPageItems + "/limits/id/orders/asc.json";
                //api/groups/{filter}/forum/filter/count.{_format} 
                parameterUrlCount = BaseUrl + "/api/groups/-1/forum/filter/count.json";

                $http.get(parameterUrl,
                        {headers: {'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)}}).success(function (response) {
                    current = 1;
                    $scope.forumgroupdata = response;
                });

                $scope.RefreshPagination = function () {
                    //alert(parameterUrl);
                    getCountPagination.TableCount(parameterUrlCount).get({username: $rootScope.userAuth.username, secret: $rootScope.userAuth.secret}, function (data) {
                        console.log(data.count);
                        allPage = Math.floor(data.count / constPageItems); //Math.floor(CountItems / constPageItems);
                        $scope.Allpaginate = paginationCreateArray.array(row, data.count, constPageItems, current);
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

                            ///api/groupforums/{filter}/filters/{offset}/offsets/{limit}/limits/{attr}/orders/{asc}.{_format} 
                            var parameterurlPagination = BaseUrl + "/api/groupforums/-1/filters/" + offset + "/offsets/" + constPageItems + "/limits/" + field + "/orders/asc.json";


                            $http.get(parameterurlPagination,
                                    {headers: {'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)}}).success(function (response) {
                                $scope.RefreshPagination();
                                $scope.forumgroupdata = response;

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

                            var parameterurlPagination = BaseUrl + "/api/groupforums/-1/filters/" + offset + "/offsets/" + constPageItems + "/limits/" + field + "/orders/asc.json";

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

                            var parameterurlPagination = BaseUrl + "/api/groupforums/-1/filters/" + offset + "/offsets/" + constPageItems + "/limits/" + field + "/orders/asc.json";

                            $http.get(parameterurlPagination,
                                    {headers: {'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)}}).success(function (response) {
                                $scope.content = response;
                                current = 1;
                                $scope.RefreshPagination();
                                $('.paginate').first().click();
                            });
                        };

                    });

                };

                //call Pagination for first
                $scope.RefreshPagination();

            }])
        //controller for client side 

//        **************************************************************************************************
//        *
//        * 
//        *                                       Posts Controllers
//        *
//        *
//        **************************************************************************************************

        //THIS CONTROLLER FOR INSERT POST TO POSTS TABLE (Admin Side)
        .controller('Posts', ['$routeParams', '$rootScope', '$scope', '$window', '$cookies', 'Todos', '$http', 'TokenHandler', 'getCountPagination', '$resource', 'paginationCreateArray',
            function ($routeParams, $rootScope, $scope, $window, $cookies, Todos, $http, TokenHandler, getCountPagination, $resource, paginationCreateArray) {
                // If auth information in cookie
                if (typeof $cookies.username != "undefined" && typeof $cookies.secret != "undefined") {
                    $rootScope.userAuth = {username: $cookies.username, secret: $cookies.secret};
                }
                // If not authenticated, go to login
                if (typeof $rootScope.userAuth == "undefined") {
                    $window.location = '#/login';
                    return;
                }


                //checking parameter root for value
                var parameterRoot = $routeParams.id;

                //for show element select post on insert form
                $scope.ForumGroupIditem = String(parameterRoot);

                //check undefined in html
                $scope.isUndefined = function (thing) {
                    return (thing == "undefined");
                }

                //console.log("isUndefined = " + $scope.isUndefined($scope.postIditem));
                //list of group for edit post form
                $http.get(BaseUrl + '/api/group/forums.json', {headers: {'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)}}).success(function (data) {
                    $scope.forumgroup = data;
                });

                //variable for pagination posts
                var count, orderBy, field;

                orderBy = '';
                field = '';

                //init pagination value
                var current = 1;
                var constPageItems = 10;
                var allPage = 0;
                var row = new Array();  // expoert pagination item
                var parameterUrl = "";  // Filter By 0 or -1
                var parameterUrlCount = ""; // json get filtered count

                //check route parameter AngularJS
                if (typeof parameterRoot == "undefined")
                {
                    parameterUrl = BaseUrl + "/api/posts/-1/filters/0/offsets/" + constPageItems + "/limits/id/orders/asc.json";
                    parameterUrlCount = BaseUrl + "/api/posts/-1/filter/count.json";
                } else
                {
                    // /api/posts/{filter}/filters/{offset}/offsets/{limit}/limits/{attr}/orders/{asc}/groups/{itemGroup}.{_format}
                    parameterUrl = BaseUrl + "/api/posts/-1/filters/0/offsets/" + constPageItems + "/limits/id/orders/asc/groups/" + parameterRoot + ".json";
                    /// api/posts/{filter}/filters/{itemGroup}/group/count.{_format}
                    parameterUrlCount = BaseUrl + "/api/posts/-1/filters/" + parameterRoot + "/group/count.json";

                    $http.get(BaseUrl + "/api/groups/" + parameterRoot + "/forum.json",
                            {headers: {'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)}}).success(function (response) {
                        $scope.breaditem = response;
                        //console.log(response);
                    });
                }

                //BaseUrl + "/api/posts/0/offsets/" + constPageItems + "/limit.json"
                $http.get(parameterUrl,
                        {headers: {'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)}}).success(function (response) {
                    current = 1;
                    $scope.PostsShow = response;
                });

                //refresh pagination
                $scope.RefreshPagination = function () {
                    //alert(parameterUrl);
                    getCountPagination.TableCount(parameterUrlCount).get({username: $rootScope.userAuth.username, secret: $rootScope.userAuth.secret}, function (data) {
                        //console.log(data.count);
                        allPage = Math.floor(data.count / constPageItems); //Math.floor(CountItems / constPageItems);
                        $scope.Allpaginate = paginationCreateArray.array(row, data.count, constPageItems, current);
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
                            if (typeof parameterRoot == "undefined")
                            {
                                parameterurlPagination = BaseUrl + "/api/posts/-1/filters/" + offset + "/offsets/" + constPageItems + "/limits/" + field + "/orders/" + orderBy + ".json";
                            } else
                            {
                                //api/posts/{filter}/filters/{offset}/offsets/{limit}/limits/{attr}/orders/{asc}/groups/{itemGroup}.{_format} 
                                parameterurlPagination = BaseUrl + "/api/posts/-1/filters/" + offset + "/offsets/" + constPageItems + "/limits/" + field + "/orders/" + orderBy + "/groups/" + parameterRoot + ".json";
                            }

                            $http.get(parameterurlPagination,
                                    {headers: {'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)}}).success(function (response) {
                                $scope.RefreshPagination();
                                $scope.PostsShow = response;

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
                                parameterurlPagination = BaseUrl + "/api/posts/-1/filters/" + offset + "/offsets/" + constPageItems + "/limits/" + field + "/orders/" + orderBy + ".json";
                            } else
                            {
                                //api/posts/{filter}/filters/{offset}/offsets/{limit}/limits/{attr}/orders/{asc}/groups/{itemGroup}.{_format} 
                                parameterurlPagination = BaseUrl + "/api/posts/-1/filters/" + offset + "/offsets/" + constPageItems + "/limits/" + field + "/orders/" + orderBy + "/groups/" + parameterRoot + ".json";
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
                                parameterurlPagination = BaseUrl + "/api/posts/-1/filters/" + offset + "/offsets/" + constPageItems + "/limits/" + field + "/orders/" + orderBy + ".json";
                            } else
                            {
                                //api/posts/{filter}/filters/{offset}/offsets/{limit}/limits/{attr}/orders/{asc}/groups/{itemGroup}.{_format} 
                                parameterurlPagination = BaseUrl + "/api/posts/-1/filters/" + offset + "/offsets/" + constPageItems + "/limits/" + field + "/orders/" + orderBy + "/groups/" + parameterRoot + ".json";
                            }


                            $http.get(parameterurlPagination,
                                    {headers: {'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)}}).success(function (response) {
                                $scope.content = response;
                                current = 1;
                                $scope.RefreshPagination();
                                $('.paginate').first().click();
                            });
                        };

                    });

                };
                //delete record of table
                $scope.deleteitem = function (item) {
                    $http({
                        method: 'DELETE',
                        url: BaseUrl + '/api/posts/' + item + '.json',
                        headers: {'Content-Type': 'application/json;charset=utf-8;', 'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)},
                        //data: data
                    }).then(function successCallback(response) {
                        $scope.paginate(current);
                        //$(".ajaxloader").fadeOut(500);
                    }, function errorCallback(response) {
                        alert('خطایی موجود است');
                        //$(".ajaxloader").fadeOut(500);
                    });
                };
                //insert record of table
                $scope.submitadd = function () {
                    var data = [];
                    if ($scope.isUndefined($scope.ForumGroupIditem))
                    {
                        data = {"post":
                                    {
                                        "title": $scope.title,
                                        "content": CKEDITOR.instances.content.getData(),
                                        "group": $scope.groupitem}
                        };
                    } else
                    {
                        data = {"post":
                                    {"title": $scope.title,
                                        "content": CKEDITOR.instances.content.getData(),
                                        "group": $scope.ForumGroupIditem
                                    }
                        };
                    }

                    console.log(data);

                    $http({
                        method: 'POST',
                        url: BaseUrl + '/api/posts.json',
                        headers: {'Content-Type': 'application/json;charset=utf-8;', 'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)},
                        data: data
                    }).then(function successCallback(response) {
                        $scope.paginate(current);
                    }, function errorCallback(response) {
                        alert('خطایی موجود است');
                    });

                };
                //edit record of table
                $scope.submitedit = function () {
                    var data = [];
                    var userid = $cookies.authid;

                    $http.get(BaseUrl + '/api/posts.json', {headers: {'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)}}).success(function (data) {
                        $scope.PostsShow = data;
                    });

                    data = {"post":
                                {"title": $scope.Edittitle,
                                    "content": CKEDITOR.instances.Editcontent.getData(),
                                    "enabled": $scope.Editenabled,
                                    "group": $scope.EditGroupId
                                }
                    };

                    $http({
                        method: 'PUT',
                        url: BaseUrl + '/api/posts/' + $scope.EdititemId + '.json',
                        headers: {'Content-Type': 'application/json;charset=utf-8;', 'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)},
                        data: data
                    }).then(function successCallback(response) {
                        $scope.paginate(current);
                    }, function errorCallback(response) {
                        alert('مشکل در ویرایش پیش آمده است');
                    });

                }; // end submit function
                //call Pagination for first
                $scope.RefreshPagination();

            }]);

      