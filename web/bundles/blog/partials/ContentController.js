'use strict';

app.run(function ($rootScope) {
    $rootScope.$on('scope.stored', function (event, data) {
        console.log("scope.stored", data);
    });
});
//{
//    date:
//            {children: 
//                {
//                    year: {},
//            month: {},
//            day: {}
//        }
//    },
//    time:
//            {
//                children: {
//                    hour: {},
//            minute: {}
//        }
//    }
//}
function getTimeStamp() {
       var now = new Date();
       return {"children":{"date":
                   {
                       "children":
                       {
                       "year":now.getFullYear(),
                       "month": (now.getMonth() + 1),
                       "day":(now.getDate())
                       }
                   },
               "time":{
                   "children":{
                       "hour":now.getHours(),
                       "minute":now.getMinutes()
                               }
                       }
               }
           };
}

function splitTime(value){
    try{
    var data=value.split("T");
    var year=data[0];
    var time=data[1];
    time=time.split("+");
    var place=time[1];
    time=time[0].split(":");
    year=year.split("-");
    
           return {"children":{"date":
                   {
                       "children":
                       {
                       "year":year[0],
                       "month": year[1],
                       "day":year[2]
                       }
                   },
               "time":{
                   "children":{
                       "hour":time[0],
                       "minute":time[1]+':'+time[2]+'+'+place
                               }
                       }
               }};
           }catch(error){
               return getTimeStamp();
           }
}
app.constant('place','content');
app  //content
        .controller('content', ['$rootScope', '$scope', '$window', '$cookies', 'Hello', 'Salt', '$http', 'TokenHandler','Scopes', function($rootScope, $scope, $window, $cookies, Hello, Salt, $http, TokenHandler,Scopes) {
                //Show Title Page And Tooltip
                $scope.titleTitle=words.title;
                $scope.sortAscTitle=words.sortAsc;
                $scope.sortDescTitle=words.sortDesc;
                $scope.deleteBtnTitle=words.deleteBtn;
                $scope.showBtnTitle=words.showBtn;
                $scope.editBtnTitle=words.editBtn;
                $scope.indexTitle=words.index;
                $scope.contentTitle=words.content;
                $scope.idTitle=words.id;
                $scope.categoryTitle=words.category;
                $scope.actionTitle=words.action;
                $scope.visitTitle=words.visit;
                $scope.createdAtTitle=words.createdAt;
                $scope.updatedAtTitle=words.updatedAt;
                $scope.orderListTitle=words.orderList;
                
                //accept to another controller for this controller Function 
                Scopes.store('content', $scope);
                var count,orderBy,field;
                orderBy='';
                field='';
                var row = new Array();
                // count all content
                $http.get( BaseUrlAdmin + "api/count.json").success(function(response) {

                    count = response.count;
                    var CountPaginate = Math.round(count / 10);
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
                    offset = offset * 10 - 10;
                    $http.get( BaseUrlAdmin + "api/contents/-1/filters/" + offset + "/offsets/10/limits/"+field+"/orders/"+orderBy+".json").success(function(response) {
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
                $http.get( BaseUrlAdmin + "api/contents/0/offsets/" + 10 + "/limit.json").success(function(response) {
                    current = 1;
                    $scope.content = response;
                });
                
                // Sort Order By desc
                $scope.sortDesc = function(field_in) {
                    field=field_in;
                    orderBy="desc";
                    $scope.desc=true;
                    $scope.asc=false;
                    var offset = current * 10 - 10;
                    $http.get( BaseUrlAdmin + "api/contents/-1/filters/" + offset + "/offsets/10/limits/"+field+"/orders/desc.json").success(function(response) {
                        $scope.content = response;
                    });
                };
                
                //Sort Order By Asc
                $scope.sortAsc = function(field_in) {
                    field=field_in;
                    orderBy="asc";
                    var offset = current * 10 - 10;
                    $scope.asc=true;
                    $scope.desc=false;
                    $http.get( BaseUrlAdmin + "api/contents/-1/filters/" + offset + "/offsets/10/limits/"+field+"/orders/asc.json").success(function(response) {
                        $scope.content = response;
                    });
                };
            }]) //Content Add
        .controller('contentAdd', ['$rootScope', '$scope', '$window', '$cookies', 'Hello', 'Salt', '$http', 'TokenHandler', function($rootScope, $scope, $window, $cookies, Hello, Salt, $http, TokenHandler) {
//read Category Data
                $http.get( BaseUrlAdmin + "api/content/categorys.json").success(function(response) {
                    $scope.ctgAll = response;
                });

                //Add content Value
                $scope.SubmitAdd = function() {
                    var title, category, order_list, content;
                    title = $scope.title;// $('#EditTitle').val();
                    category = $scope.ctg;//$('#EditctgId').val();
                    order_list = $scope.order_list;//$('#Editorder_list').val();
                    content = CKEDITOR.instances.AddContentTextArea.getData();
//            alert('title is : '+title+' content is : '+content+' order_list is : '+order_list+' category is : '+category );
                    $http({
                        method: 'POST',
                        data: {"content": {
                                "title": title,
                                "content": content,
                                "visit": 0,
                                "orderList": order_list,
                                "ctg": category
                            }},
                        url:  BaseUrlAdmin + 'api/contents.json',
                    }).then(function successCallback(response) {
                        alert('اطلاعات با موفقیت ثبت شد');
                    }, function errorCallback(response) {
                        alert('مشکل در ثبت اطلاعات با پشتیبانی تماس بگیرید');
                    });
                };


            }]) //contentEdit
        .controller('contentEdit', ['$rootScope', '$scope', '$window', '$cookies', 'Hello', 'Salt', '$http', 'TokenHandler','Scopes', function($rootScope, $scope, $window, $cookies, Hello, Salt, $http, TokenHandler,Scopes) {

                Scopes.store('contentEdit', $scope);
                //read Category Data
                $http.get( BaseUrlAdmin + "api/content/categorys.json").success(function(response) {
                    $scope.ctgAll = response;
                });

                $scope.SubmitEdit = function() {
                    var id = $('#EditId').val();
                    var title, category, order_list, content,created_at;
                    title = $('#EditTitle').val();
                    category = parseInt($('#EditctgId').val());
                    order_list = $('#Editorder_list').val();
                    created_at=$('#created_atValue').val();
                    content = CKEDITOR.instances.EditContent.getData();

//                    alert(splitTime(created_at));
//                    console.log(splitTime(created_at));
                    // Simple GET request example:
                    $http({
                        method: 'PUT',
                        data: {"content": {
                                "title": title,
                                "content": content,
                                "orderList": order_list,
                                "ctg": category
                            }
                        },
                        url:  BaseUrlAdmin + 'api/contents/' + id + '.json',
                    }).then(function successCallback(response) {
                        alert('اطلاعات با موفقیت ویرایش شد');
                        Scopes.get('content').sortAsc('id');
                    }, function errorCallback(response) {
                        alert('مشکل در ارسال اطلاعات با پشتیبانی تماس بگیرید');
                    });
                };

            }]) //contentDelete
        .controller('contentDelete', ['$rootScope', '$scope', '$window', '$cookies', 'Hello', 'Salt', '$http', 'TokenHandler', function($rootScope, $scope, $window, $cookies, Hello, Salt, $http, TokenHandler) {

                $scope.SubmitDelete = function() {
                    var id = $('#DeleteId').val();
                    // Simple GET request example:
                    $http({
                        method: 'DELETE',
                        url:  BaseUrlAdmin + 'api/contents/' + id + '.json',
                    }).then(function successCallback(response) {
                        $('#' + id).hide();
                        $('#CloseDeleteBtn').click();
                    }, function errorCallback(response) {
                        alert('No Deleted');
                    });
                };

            }]);

