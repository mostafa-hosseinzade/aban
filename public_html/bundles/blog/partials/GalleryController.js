'use strict';

app.run(function($rootScope) {
    $rootScope.$on('scope.stored', function(event, data) {
        console.log("scope.stored", data);
    });
});

app  //gallery
        .controller('gallery', ['$rootScope', '$scope', '$window', '$cookies', 'Hello', 'Salt', '$http', 'TokenHandler', 'Scopes', function($rootScope, $scope, $window, $cookies, Hello, Salt, $http, TokenHandler, Scopes) {

                //Show title And Tooltip scope
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
                $scope.orderListTitle = words.orderList;
                $scope.metaTitle=words.meta;
                
                //accept to another controller for this controller Function 
                Scopes.store('gallery', $scope);
                var count, orderBy, field;
                orderBy = '';
                field = '';
                var row = new Array();
                // count all gallery
                $http.get( BaseUrlAdmin + "api/gallerys/count.json").success(function(response) {

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
                    $http.get( BaseUrlAdmin + "api/galleries/-1/filters/" + offset + "/offsets/10/limits/" + field + "/orders/" + orderBy + ".json").success(function(response) {
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
                $http.get( BaseUrlAdmin + "api/galleries/0/offsets/" + 10 + "/limit.json").success(function(response) {
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
                    $http.get( BaseUrlAdmin + "api/galleries/-1/filters/" + offset + "/offsets/10/limits/" + field + "/orders/desc.json").success(function(response) {
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
                    $http.get( BaseUrlAdmin + "api/galleries/-1/filters/" + offset + "/offsets/10/limits/" + field + "/orders/asc.json").success(function(response) {
                        $scope.content = response;
                    });
                };

                $scope.show = function(id) {
                    $http.get( BaseUrlAdmin + "api/images/" + id + "/gallery.json").success(function(response) {
                        $scope.GalleryImage = response;
                        $scope.gallery = id;
                    });
                };
            }]) //Content Add
        .controller('galleryAdd', ['$rootScope', '$scope', '$window', '$cookies', 'Hello', 'Salt', '$http', 'TokenHandler', function($rootScope, $scope, $window, $cookies, Hello, Salt, $http, TokenHandler) {
                //Add gallery Value
                $scope.SubmitAdd = function() {
                    var title, order_list, meta;
                    title = $scope.title;// $('#EditTitle').val();
                    order_list = $scope.order_list;//$('#Editorder_list').val();
                    meta = $scope.meta;
//            alert('title is : '+title+' meta is : '+meta+' order_list is : '+order_list );
                    $http({
                        method: 'POST',
                        data: {"gallery": {
                                "title": title,
                                "meta": meta,
                                "orderList": order_list
                            }},
                        url:  BaseUrlAdmin + 'api/galleries.json',
                        headers: {'Content-Type': 'application/json;charset=utf-8'}
                    }).then(function successCallback(response) {
                        alert('اطلاعات با موفقیت ثبت شد');
                    }, function errorCallback(response) {
                        alert('مشکل در ثبت اطلاعات با پشتیبانی تماس بگیرید');
                    });
                };


            }]) //galleryEdit
        .controller('galleryEdit', ['$rootScope', '$scope', '$window', '$cookies', 'Hello', 'Salt', '$http', 'TokenHandler', 'Scopes', function($rootScope, $scope, $window, $cookies, Hello, Salt, $http, TokenHandler, Scopes) {
                Scopes.store('galleryEdit', $scope);

                $scope.SubmitEdit = function() {
                    var id = $('#EditId').val();
                    var title, meta, order_list;
                    title = $('#EditTitle').val();
                    meta = $('#Editmeta').val();
                    order_list = $('#Editorder_list').val();
//              alert("title is : "+title+" meta is : "+meta+" orderlist is : "+order_list);
                    // Simple GET request :
                    $http({
                        method: 'PUT',
                        data: {"gallery": {
                                "title": title,
                                "meta": meta,
                                "orderList": order_list
                            }
                        },
                        url:  BaseUrlAdmin + 'api/galleries/' + id + '.json'
                    }).then(function successCallback(response) {
                        alert('اطلاعات با موفقیت ویرایش شد');
                        Scopes.get('gallery').sortAsc('id');
                    }, function errorCallback(response) {
                        alert('مشکل در ارسال اطلاعات با پشتیبانی تماس بگیرید');
                    });
                };

            }]) //galleryDelete
        .controller('galleryDelete', ['$rootScope', '$scope', '$window', '$cookies', 'Hello', 'Salt', '$http', 'TokenHandler', function($rootScope, $scope, $window, $cookies, Hello, Salt, $http, TokenHandler) {
                $scope.SubmitDelete = function() {
                    var id = $('#DeleteId').val();
                    // Simple GET request example:
                    $http({
                        method: 'DELETE',
                        url:  BaseUrlAdmin + 'api/galleries/' + id + '.json'
                    }).then(function successCallback(response) {
                        $('#' + id).hide();
                        $('#CloseDeleteBtn').click();
                    }, function errorCallback(response) {
                        alert('No Deleted');
                    });
                };

            }]);

