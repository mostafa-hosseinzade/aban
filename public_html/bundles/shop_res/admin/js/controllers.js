'use strict';
var app = angular.module('myApp.controllers', ['ngCookies']);
app.controller('category', ['$scope', '$http', '$location', '$cookieStore', '$routeParams', function ($scope, $http, $location, $cookieStore, $routeParams) {
        var item = [];
        var url = '/category/';
        $http.get('/category/ctg_showAll').success(function (response) {
            $scope.item = response;
            console.log(response);
        });

        $scope.additem = function (name, ctg_no, parent, photo, descr, is_has_sub_ctg) {
            var data = {
                'name': $scope.name,
                'ctg_no': $scope.ctg_no,
                'parent': $scope.parent.name,
                'photo': $scope.photo,
                'descr': $scope.descr,
                'is_has_sub_ctg': $scope.is_has_sub_ctg
            };
//            console.log(item);
            $http({method: 'post', data: data, url: '/category/'}).success(function (response) {
                var id = response.split(';');
                data['id'] = id[1];
                item.push(data);
                $http.get('/category/ctg_showAll').success(function (response) {
                    $scope.item = response;
                    console.log(response);
                });
            });
        };
        $scope.removeItem = function (ctg_no, index) {
            $http.get('/category/' + ctg_no + '/ctg_del').success(function (response) {
                $scope.item.splice(index, 1);
            });
        };


    }]);
app.controller('product', ['$scope', '$http', '$window', '$routeParams', '$cookies', '$cookieStore', '$timeout', function ($scope, $http, $window, $routeParams, $cookies, $cookieStore, $timeout) {
        var url = '/product/';
        var item = [];
        $http.get('/product/product_showAll').success(function (response) {
            $scope.item = response;
//            console.log(response);
        });
        $scope.findCtg = function (categoryAdd) {
            $http({method: 'get', url: '/product/findCtg'}).success(function (response) {
                $scope.categoryAdd = response;

            });
        };
        $scope.additem = function (category, name, product_no, price, photo, descr) {
//            $http({method: 'get', url: '/product/findCtg'}).success(function (response) {
//                $scope.categoryAdd = response;
//                console.log(response);
//            });
//            $http({method: 'post',data:data, url: '/product/create'}).success(function (response){
//                console.log(response);


            var data = {
                'category': $scope.category,
                'name': $scope.name,
                'productNo': $scope.product_no,
                'price': $scope.price,
                'photo': $scope.photo,
                'descr': $scope.descr

            };
            $http({method: 'post', data: data, url: '/product/create'}).success(function (response) {
//                var id = response.split(';');
//                data['id'] = id[1];
//                $scope.id = id[1];
//                console.log(data);
                if (response === "existProductNo") {
                    alert('شماره محصول جدید تکراری است');
                }
                ;
                item.push(data);
                $http.get('/product/product_showAll').success(function (response) {
                    $scope.item = response;
                });
//                $scope.item = item;
            });
        };
        $scope.removeItem = function (id, index) {
            $http.get('/product/' + id + '/pro_del').success(function (response) {
                $scope.item.splice(index, 1);
                console.log(response);
            });
        };
        $scope.editItem = function (item, index) {
            $http({method: 'get', url: '/product/findCtg'}).success(function (response) {
                $scope.category = response;
            });
            $scope.defaultSelectedCategory = item.category;
            $scope.editCategory = $scope.defaultSelectedCategory;
            $scope.editName = item.name;
            $scope.editProduct_no = item.product_no;
            $scope.editPrice = item.price;
            $scope.editPhoto = item.photo;
            $scope.editDescr = item.descr;
            $scope.editId = item.id;


            $scope.selectItem = index;
            console.log($scope.defaultSelectedCategory);
        };

        $scope.saveItem = function (id, selectItem) {
            var data = {
                'category': $scope.editCategory,
                'name': $scope.editName,
                'productNo': $scope.editProduct_no,
                'price': $scope.editPrice,
                'photo': $scope.editPhoto,
                'descr': $scope.editDescr
            };
            //console.log(index);
            //console.log(data);
            $http({method: 'put', data: data, url: '/product/' + id + '/update'}).success(function (response) {
                console.log(response);
                $scope.item[$scope.selectItem] = response;
//                item.push(data);
//                $scope.item = item;
            });

        };
        
//        var dade = function () {
//            return Math.round(Math.random() * 100)
//        };
//        var lineChartData = {
//            labels:[],
//            datasets:[
//                {
//                    
//                }
//            ]
//        };



//        $scope.chart = {
//            options: {
//                chart: {
//                    type: 'bar'
//                }
//            },
//            series: [{
//                    data: [10, 15, 12, 8, 7]
//                }],
//            title: {
//                text: 'Hello'
//            },
//            loading: false
//        };

    }]);
//            chart: {
//                type: 'lineChart',
//                hieght: 450,
//                x: function (d) {
//                    return d.x;
//                },
//                y: function (d) {
//                    return d.y;
//                },
//                useInteractiveGuideline: true,
//                dispatch: {
//                    stateChange: function (e) {
//                        console.log("stateChange");
//                    },
//                    changeState: function (e) {
//                        console.log("changeState");
//                    },
//                    tooltipShow: function (e) {
//                        console.log("tooltipShow");
//                    },
//                    tooltipHide: function (e) {
//                        console.log("tooltipHide");
//                    }
//                },
//                xAxis: {
//                    axisLabel: 'Time (ms)'
//                },
//                yAxis: {
//                    axisLabel: 'Voltage (v)',
//                    tickFormat: function (d) {
//                        return d3.format('.02f')(d);
//                    },
//                    axisLabelDistance: -10
//                },
//                callback: function (chart) {
//                    console.log("!!! lineChart callback !!!");
//                }
//            },
//            title: {
//                enable: true,
//                text: 'نمودار'
//            },
//            subtitle: {
//                enable: true,
//                text: 'زیر نمودار',
//                css: {
//                    'text-align': 'center',
//                    'margin': '10px 13px 0px 7px'
//                }
//            },
//            caption: {
//                enable: true,
//                css: {
//                    'text-align': 'justify',
//                    'margin': '10px 13px 0px 7px'
//                }
//            }
//        };
//        $scope.data = [
//            [65, 59, 80, 81, 56, 55, 40],
//            [28, 48, 40, 19, 86, 27, 90]
//        ];
//         $scope.onClick = function (points, evt) {
//             







//                sinAndCos();
//
//        /*Random Data Generator */
//        function sinAndCos() {
//            var sin = [],
//                    sin2 = [],
//                    cos = [];
//
//            //Data is represented as an array of {x,y} pairs.
//            for (var i = 0; i < 100; i++) {
//                sin.push({x: i, y: Math.sin(i / 10)});
//                sin2.push({x: i, y: i % 10 == 5 ? null : Math.sin(i / 10) * 0.25 + 0.5});
//                cos.push({x: i, y: .5 * Math.cos(i / 10 + 2) + Math.random() / 10});
//            }
//
//            //Line chart data should be sent as an array of series objects.
//            return [
//                {
//                    values: sin, //values - represents the array of {x,y} data points
//                    key: 'Sine Wave', //key  - the name of the series.
//                    color: '#ff7f0e'  //color - optional: choose your own line color.
//                },
//                {
//                    values: cos,
//                    key: 'Cosine Wave',
//                    color: '#2ca02c'
//                },
//                {
//                    values: sin2,
//                    key: 'Another sine wave',
//                    color: '#7777ff',
//                    area: true      //area - set to true if you want this line to turn into a filled area chart.
//                }
//            ];
//        };





