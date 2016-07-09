'use strict';

app.run(function ($rootScope) {
    $rootScope.$on('scope.stored', function (event, data) {
        console.log("scope.stored", data);
    });
});

app  //image
        .controller('image', ['$rootScope', '$scope', '$window', '$cookies', 'Hello', 'Salt', '$http', 'TokenHandler','Scopes', function($rootScope, $scope, $window, $cookies, Hello, Salt, $http, TokenHandler,Scopes) {
                // If auth information in cookie
                if (typeof $cookies.username != "undefined" && typeof $cookies.secret != "undefined") {
                    $rootScope.userAuth = {username: $cookies.username, secret: $cookies.secret};
                }

//    // If not authenticated, go to login
                if (typeof $rootScope.userAuth == "undefined") {
                    $window.location = '#/login';
                    return;
                }
                Scopes.store('image', $scope);
                var count,orderBy,field;
                orderBy='';
                field='';
                var row = new Array();
                // count all image
                $http.get(BaseUrl + "api/images/count.json",
                        {headers: {'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)}}).success(function(response) {

                    count = response.count;
                    var CountPaginate = Math.round(count / 8);
                    $scope.CountPaginate=CountPaginate;
                    for (var i = 0; i < CountPaginate; i++) {
                        row[i] = i + 1;
                    }
                    $scope.Allpaginate = row;
                });
                //current offset
                var current;
                
                //function paginate
                $scope.paginate = function(offset) {
                    if(orderBy === ''){
                        orderBy="asc";
                    }
                    
                    if(field === ''){
                        field='id';
                    }
                    
                    current = offset;
                    offset = offset * 8 - 8;
                    $http.get(BaseUrl + "api/images/-1/filters/" + offset + "/offsets/8/limits/"+field+"/orders/"+orderBy+".json",
                            {headers: {'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)}}).success(function(response) {
                        $scope.content = response;
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
                
                //first query
                $http.get(BaseUrl + "api/images/0/offsets/" + 8 + "/limit.json",
                        {headers: {'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)}}).success(function(response) {
                    current = 1;
                    $scope.content = response;
                });
                
                // Sort Order By desc
                $scope.sortDesc = function(field_in) {
                    field=field_in;
                    orderBy="desc";
                    $scope.desc=true;
                    $scope.asc=false;
                    var offset = current * 8 - 8;
                    $http.get(BaseUrl + "api/images/-1/filters/" + offset + "/offsets/8/limits/"+field+"/orders/desc.json",
                            {headers: {'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)}}).success(function(response) {
                        $scope.content = response;
                    });
                };
                
                //Sort Order By Asc
                $scope.sortAsc = function(field_in) {
                    field=field_in;
                    orderBy="asc";
                    var offset = current * 8 - 8;
                    $scope.asc=true;
                    $scope.desc=false;
                    $http.get(BaseUrl + "api/images/-1/filters/" + offset + "/offsets/8/limits/"+field+"/orders/asc.json",
                            {headers: {'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)}}).success(function(response) {
                        $scope.content = response;
                    });
                };
            }]) //Content Add
        .controller('imageAdd', ['$rootScope', '$scope', '$window', '$cookies', 'Hello', 'Salt', '$http', 'TokenHandler','Scopes', function($rootScope, $scope, $window, $cookies, Hello, Salt, $http, TokenHandler,Scopes) {
                // If auth information in cookie
                if (typeof $cookies.username != "undefined" && typeof $cookies.secret != "undefined") {
                    $rootScope.userAuth = {username: $cookies.username, secret: $cookies.secret};
                }

                // If not authenticated, go to login
                if (typeof $rootScope.userAuth == "undefined") {
                    $window.location = '#/login';
                    return;
                }
                Scopes.store('imageAdd', $scope);
                 $http.get(BaseUrl + "api/gallerys.json",
                        {headers: {'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)}}).success(function(response) {
                    $scope.galleryAll = response;
                });
                //Add image Value
                $scope.SubmitAdd = function() {
                    var alt,src,gallery,order_list;
                    alt = $scope.alt;//$('#AddAlt').val();
                    src = $('#AddSrcPic').val();
                    gallery=$scope.gallery;
                    order_list=$scope.order_list
//            alert('alt is : '+alt+' gallery is : '+gallery+' src is : '+src);
                    $http({
                        method: 'POST',
                        data: {"images": {
                                "alt": alt,
                                "src": src,
                                "gallery":gallery,
                                "orderList":order_list
                            }},
                        url: BaseUrl + 'api/images.json',
                        headers: {'Content-Type': 'application/json;charset=utf-8', 'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)}
                    }).then(function successCallback(response) {
                        alert('اطلاعات با موفقیت ثبت شد');
                            try{
                            Scopes.get('image').sortAsc('id');
                            }catch(error){

                            }
                            var data="<div class='col-lg-3 col-md-3 col-sm-6 col-sm-6 ImageInfo'\n\
                                         style='background-image: url("+src+")'>\n\
                                        <div class='header'>"+ alt +"</div>\n\
                                        <div class='center'>\n\
                                        </div>\n\
                                        <div class='footer'>\n\
                                            <strong>توضیحات</strong> : "+alt+"\n\
                                            <button onclick='DeleteImage("+response.id+")' class='btn btn-danger btn-sm pull-left bottom-left'>Delete</button>\n\
                                        </div>\n\
                                    </div>";
                    
                                        $('#ImageGalleryShow').append(data);
                    
                    }, function errorCallback(response) {
                        alert('مشکل در ثبت اطلاعات با پشتیبانی تماس بگیرید');
                    });
                };


            }]) //imageDelete
        .controller('imageDelete', ['$rootScope', '$scope', '$window', '$cookies', 'Hello', 'Salt', '$http', 'TokenHandler', function($rootScope, $scope, $window, $cookies, Hello, Salt, $http, TokenHandler) {
                // If auth information in cookie
                if (typeof $cookies.username != "undefined" && typeof $cookies.secret != "undefined") {
                    $rootScope.userAuth = {username: $cookies.username, secret: $cookies.secret};
                }

//    // If not authenticated, go to login
                if (typeof $rootScope.userAuth == "undefined") {
                    $window.location = '#/login';
                    return;
                }
                $scope.SubmitDelete = function() {
                    var id = $('#DeleteImageId').val();
                    // Simple GET request example:
                    $http({
                        method: 'DELETE',
                        url: BaseUrl + 'api/images/' + id + '.json',
                        headers: {'x-wsse': TokenHandler.getCredentials($rootScope.userAuth.username, $rootScope.userAuth.secret)}
                    }).then(function successCallback(response) {
                        $('#' + id+"div").slideUp();
                        $('#CloseDeleteBtn').click();
                        $('#CloseDeleteImageBtn').click();
                    }, function errorCallback(response) {
                        alert('No Deleted');
                    });
                };

            }]);
