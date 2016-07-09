'use strict';

app.run(function($rootScope) {
    $rootScope.$on('scope.stored', function(event, data) {
        console.log("scope.stored", data);
    });
});

app  //page
        .controller('Page', ['$rootScope', '$scope', '$window', '$cookies', 'Hello', 'Salt', '$http', 'TokenHandler', 'Scopes', function($rootScope, $scope, $window, $cookies, Hello, Salt, $http, TokenHandler, Scopes) {

                //Show title And Tooltip
                $scope.titleTitle = words.title;
                $scope.sortAscTitle = words.sortAsc;
                $scope.sortDescTitle = words.sortDesc;
                $scope.deleteBtnTitle = words.deleteBtn;
                $scope.showBtnTitle = words.showBtn;
                $scope.editBtnTitle = words.editBtn;
                $scope.indexTitle = words.index;
                $scope.contentTitle = words.content;
                $scope.idTitle = words.id;
                $scope.categoryTitle = words.category;
                $scope.actionTitle = words.action;
                $scope.visitTitle = words.visit;
                $scope.createdAtTitle = words.createdAt;
                $scope.updatedAtTitle = words.updatedAt;
                $scope.orderListTitle = words.orderList;
                $scope.metaTitle=words.meta;
                
                //accept to another controller for this controller Function 
                Scopes.store('page', $scope);
                var count, orderBy, field;
                orderBy = '';
                field = '';
                var row = new Array();
                // count all page
                $http.get( BaseUrlAdmin + "api/page/count.json").success(function(response) {

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
                    $http.get( BaseUrlAdmin + "api/pages/-1/filters/" + offset + "/offsets/10/limits/" + field + "/orders/" + orderBy + ".json").success(function(response) {
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
                $http.get( BaseUrlAdmin + "api/pages/0/offsets/" + 10 + "/limit.json").success(function(response) {
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
                    $http.get( BaseUrlAdmin + "api/pages/-1/filters/" + offset + "/offsets/10/limits/" + field + "/orders/desc.json").success(function(response) {
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
                    $http.get( BaseUrlAdmin + "api/pages/-1/filters/" + offset + "/offsets/10/limits/" + field + "/orders/asc.json").success(function(response) {
                        $scope.content = response;
                    });
                };
            }]) //Content Add
        .controller('pageAdd', ['$rootScope', '$scope', '$window', '$cookies', 'Hello', 'Salt', '$http', 'TokenHandler', function($rootScope, $scope, $window, $cookies, Hello, Salt, $http, TokenHandler) {
                //Add page Value
                $scope.SubmitAdd = function() {
                    var title, meta, content;
                    title = $scope.title;// $('#EditTitle').val();
                    meta = $scope.meta;//$('#Editorder_list').val();
                    content = CKEDITOR.instances.AddContentTextArea.getData();
//            alert('title is : '+title+' page is : '+content+' meta is : '+meta);
                    $http({
                        method: 'POST',
                        data: {"page": {
                                "title": title,
                                "content": content,
                                "meta": meta
                            }},
                        url:  BaseUrlAdmin + 'api/pages.json'
                    }).then(function successCallback(response) {
                        alert('اطلاعات با موفقیت ثبت شد');
                    }, function errorCallback(response) {
                        alert('مشکل در ثبت اطلاعات با پشتیبانی تماس بگیرید');
                    });
                };


            }]) //pageEdit
        .controller('pageEdit', ['$rootScope', '$scope', '$window', '$cookies', 'Hello', 'Salt', '$http', 'TokenHandler', 'Scopes', function($rootScope, $scope, $window, $cookies, Hello, Salt, $http, TokenHandler, Scopes) {

                Scopes.store('pageEdit', $scope);

                $scope.SubmitEdit = function() {
                    var id = $('#EditId').val();
                    var title, meta, content;
                    title = $('#EditTitle').val();
                    meta = $('#Editmeta').val();
                    content = CKEDITOR.instances.EditContent.getData();
//              alert(content);
                    // Simple GET request :
                    $http({
                        method: 'PUT',
                        data: {"page": {
                                "title": title,
                                "content": content,
                                "meta": meta
                            }
                        },
                        url:  BaseUrlAdmin + 'api/pages/' + id + '.json'
                    }).then(function successCallback(response) {
                        alert('اطلاعات با موفقیت ویرایش شد');
                        Scopes.get('page').sortAsc('id');
                    }, function errorCallback(response) {
                        alert('مشکل در ارسال اطلاعات با پشتیبانی تماس بگیرید');
                    });
                };

            }]) //pageDelete
        .controller('pageDelete', ['$rootScope', '$scope', '$window', '$cookies', 'Hello', 'Salt', '$http', 'TokenHandler', function($rootScope, $scope, $window, $cookies, Hello, Salt, $http, TokenHandler) {
                $scope.SubmitDelete = function() {
                    var id = $('#DeleteId').val();
                    // Simple GET request example:
                    $http({
                        method: 'DELETE',
                        url:  BaseUrlAdmin + 'api/pages/' + id + '.json'
                    }).then(function successCallback(response) {
                        $('#' + id).hide();
                        $('#CloseDeleteBtn').click();
                    }, function errorCallback(response) {
                        alert('No Deleted');
                    });
                };

            }]);

angular.module('myApp.filters', []).
        filter('htmlToPlaintext', function() {
            return function(text) {
                return  text ? String(text).replace(/<[^>]+>/gm, '') : '';
            };
        }
        );
