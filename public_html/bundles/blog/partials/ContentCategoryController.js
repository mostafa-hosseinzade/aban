'use strict';

app.run(function($rootScope) {
    $rootScope.$on('scope.stored', function(event, data) {
        console.log("scope.stored", data);
    });
});

app  //content
        .controller('ContentCategory', ['$rootScope', '$scope', '$window', '$cookies', 'Hello', 'Salt', '$http', 'TokenHandler', 'Scopes', function($rootScope, $scope, $window, $cookies, Hello, Salt, $http, TokenHandler, Scopes) {
 
                //accept to another controller for this controller Function 
                Scopes.store('ContentCategory', $scope);
                
                //Show Title Page And Tooltip
                $scope.titleTitle = words.title;
                $scope.sortAscTitle = words.sortAsc;
                $scope.sortDescTitle = words.sortDesc;
                $scope.deleteBtnTitle = words.deleteBtn;
                $scope.showBtnTitle = words.showBtn;
                $scope.editBtnTitle = words.editBtn;
                $scope.indexTitle = words.index;
                $scope.idTitle = words.id;
                $scope.actionTitle = words.action;
                $scope.createdAtTitle = words.createdAt;
                $scope.updatedAtTitle = words.updatedAt;
                $scope.slugTitle=words.slug;
                $scope.activeTitle = words.active;
                //variable for paginate
                var count, orderBy, field;
                orderBy = '';
                field = '';
                var row = new Array();
                
                // count all content
                $http.get( BaseUrlAdmin + "api/content/categorys/count.json").success(function(response) {

                    count = response.count;
                    var CountPaginate = Math.round(count / 10);
                    $scope.CountPaginate = CountPaginate;
                    for (var i = 0; i < CountPaginate; i++) {
                        row[i] = i + 1;
                    }
                    $scope.Allpaginate = row;
                });
                //current offset
                var current;

                //function paginate
                $scope.paginate = function(offset) {
                    if (orderBy === '') {
                        orderBy = "asc";
                    }

                    if (field === '') {
                        field = 'id';
                    }

                    current = offset;
                    offset = offset * 10 - 10;
                    $http.get( BaseUrlAdmin + "api/contents/-1/categories/" + offset + "/filters/10/offsets/" + field + "/limits/" + orderBy + "/order.json"
                            ).success(function(response) {
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
                $http.get( BaseUrlAdmin + "api/contents/0/categories/" + 10 + "/offset/limit.json").success(function(response) {
                    current = 1;
                    $scope.content = response;
                });

                // Sort Order By desc
                $scope.sortDesc = function(field_in) {
                    field = field_in;
                    orderBy = "desc";
                    $scope.desc = true;
                    $scope.asc = false;
                    var offset = current * 10 - 10;
                    $http.get( BaseUrlAdminh + "api/contents/-1/categories/" + offset + "/filters/10/offsets/" + field + "/limits/desc/order.json").success(function(response) {
                        $scope.content = response;
                    });
                };

                //Sort Order By Asc
                $scope.sortAsc = function(field_in) {
                    field = field_in;
                    orderBy = "asc";
                    var offset = current * 10 - 10;
                    $scope.asc = true;
                    $scope.desc = false;
                    $http.get( BaseUrlAdmin + "api/contents/-1/categories/" + offset + "/filters/10/offsets/" + field + "/limits/asc/order.json").success(function(response) {
                        $scope.content = response;
                    });
                };
            }]) //Content Category Add
        .controller('ContentCategoryAdd', ['$rootScope', '$scope', '$window', '$cookies', 'Hello', 'Salt', '$http', 'TokenHandler', function($rootScope, $scope, $window, $cookies, Hello, Salt, $http, TokenHandler) {
                $scope.SubmitAdd = function() {
                    var title, slug,active;
                    title = $scope.title;// $('#EditTitle').val();
                    slug =$scope.slug;
                    active = $scope.active;
//            alert('title is : '+title+' slug is : '+slug+' active is : '+active );
                    $http({
                        method: 'POST',
                        data: {"contentcategory": {
                                "title": title,
                                "slug": slug,
                                "active":active
                            }},
                        url:  BaseUrlAdmin + 'api/contents/categories.json',
                        headers: {'Content-Type': 'application/json;charset=utf-8'}
                    }).then(function successCallback(response) {
                        alert('اطلاعات با موفقیت ثبت شد');
                    }, function errorCallback(response) {
                        alert('مشکل در ثبت اطلاعات با پشتیبانی تماس بگیرید');
                    });
                };


            }]) //content Category Edit
        .controller('ContentCategoryEdit', ['$rootScope', '$scope', '$window', '$cookies', 'Hello', 'Salt', '$http', 'TokenHandler', 'Scopes', function($rootScope, $scope, $window, $cookies, Hello, Salt, $http, TokenHandler, Scopes) {
                Scopes.store('ContentCategoryEdit', $scope);
                $scope.SubmitEdit = function() {
                    var id = $('#EditId').val();
                    var title, slug,active;
                    title = $scope.title;
                    slug = $('#EditSlug').val();
                    active=$('#EditActive').val();
//                    alert("title is : "+title+" id is: "+id+" slug is : "+slug);

                    // Simple GET request 
                    $http({
                        method: 'PUT',
                        data: {"contentcategory": {
                                "title": title,
                                "slug": slug,
                                "active":active
                            }
                        },
                        url:  BaseUrlAdmin + 'api/contents/' + id + '/category.json',
                    }).then(function successCallback(response) {
                        alert('اطلاعات با موفقیت ویرایش شد');
                        Scopes.get('ContentCategory').sortAsc('id');
                    }, function errorCallback(response) {
                        alert('مشکل در ارسال اطلاعات با پشتیبانی تماس بگیرید');
                    });
                };

            }]) //contentDelete
        .controller('ContentCategoryDelete', ['$rootScope', '$scope', '$window', '$cookies', 'Hello', 'Salt', '$http', 'TokenHandler', function($rootScope, $scope, $window, $cookies, Hello, Salt, $http, TokenHandler) {
                $scope.SubmitDelete = function() {
                    var id = $('#DeleteId').val();
                    // Simple GET request example:
                    if(id == 1 || id == 2){
                        alert("این اطلاعات را نمی توانید پاک کنید");
                    }else{
                    $http({
                        method: 'DELETE',
                        url:  BaseUrlAdmin + 'api/contents/' + id + '/category.json',
                    }).then(function successCallback(response) {
                        $('#' + id).hide();
                        $('#CloseDeleteBtn').click();
                    }, function errorCallback(response) {
                        alert('محتوای سایت جزو این دسته بندی می باشد | نمی توانید حذف کنید');
                    });
                }
                };
            }]);
//filter html data
angular.module('myApp.filters', []).
        filter('htmlToPlaintext', function() {
            return function(text) {
                return  text ? String(text).replace(/<[^>]+>/gm, '') : '';
            };
        }
        );
