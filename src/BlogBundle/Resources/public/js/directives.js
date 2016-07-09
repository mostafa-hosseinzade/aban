'use strict';

/* Directives */


angular.module('myApp.directives', []).
        directive('appVersion', ['version', function (version) {
                return function (scope, elm, attrs) {
                    elm.text(version);
                };
            }]);
angular.module('myApp.directives', []).
        directive('loading', ['$http', function ($http)
            {
                return {
                    restrict: 'A',
                    link: function (scope, elm, attrs)
                    {
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
            }]).
        directive('header', function ($routeParams) {

            return {
                template: function () {
                    var tmpl = '<th>ردیف</th>';
                    var route = '';
                    if ($routeParams.entity) {
                        route += $routeParams.entity + "/";
                    }
                    if ($routeParams.filter) {
                        route += "filter/" + $routeParams.filter + "/";
                    }
                    $.each(entity[$routeParams.entity]['list'], function (item) {
                        var list = entity[$routeParams.entity]['list'];
                        tmpl += "<th style='border:none'><table ><tr style='background-color:rgba(90,156,144,0.0);border:none'><td style='text-align:center'>" + list[item].label + "</td><td><a data-toggle='tooltip' data-placement='top' title='صعودی' href='#/" + route + "order/" + list[item].name + "/ASC'><i class='fa fa-chevron-up'></i></a><a data-toggle='tooltip' data-placement='bottom' title='نزولی' href='#/" + route + "order/" + list[item].name + "/DESC'><i class='fa fa-chevron-down'></i></a></td></tr></table></td>";
                    });
                    return tmpl;
                },
                replace: false,
                link: function (elm) {
                    var allElements = document.getElementsByTagName('*');
                    for (var i = 0, n = allElements.length; i < n; i++)
                    {
                        if (allElements[i].getAttribute('data-toggle') !== null)
                        {
                            $(allElements[i]).tooltip();
                        }
                    }
                }
            };
        }).
        directive('row', function ($routeParams) {
            return {
                template: function (tElement, tAttrs) {
                    var tmpl = "<td ng-dblclick='showItem(row, $index)' ng-click='selectedItems(row, $index)' ng-class='{selectedItem:row.id==selectedItem.id}'>{{$index+1}}</td>";
                    var list = entity[$routeParams.entity]['list'];
                    $.each(list, function (item) {
                        var propertyshow = '';
                        if (list[item].type === 'entity') {
                            propertyshow = list[item].name + '.' + list[item].prop;
                        } else {
                            propertyshow = list[item].name;
                        }
                        if (list[item].name !== 'id') {
                            tmpl += "<td ng-dblclick='showItem(row, $index)' ng-click='selectedItems(row, $index)' ng-class='{selectedItem:row.id==selectedItem.id}'><span " + list[item].type + "='row." + propertyshow + "' ng-data='row." + propertyshow + "' /></td>";
                        }
                    });
                    return tmpl;
                },
                replace: false,
                scope: false,
                require: 'ngModel'
            };
        }).
        directive('editform', function ($routeParams) {
            return {
                template: function (tElement, tAttrs) {
                    var tmpl = '';
                    var form = entity[$routeParams.entity]['form'];
                    $.each(form, function (item) {
                        if (form[item].type === 'file') {
                            tmpl += "<div class='form-group' ><label for='recipient-name' class='control-label'>" + form[item].label + "</label><span input" + form[item].type + " index='" + item + "'  ></span></div>";
                        } else {
                            tmpl += "<div class='form-group' ><label for='recipient-name' class='control-label'>" + form[item].label + "</label><span input" + form[item].type + " index='" + item + "'  ng-model='selectedItem." + form[item].name + "' ></span></div>";
                        }

                    });
                    return tmpl;
                },
                replace: false,
                require: 'ngModel'
            };
        }).
        directive('showpanel', function ($routeParams) {
            return {
                template: function (tElement, tAttrs) {
                    var tmpl = '';
                    var show = entity[$routeParams.entity]['show'];
                    $.each(show, function (item) {
                        tmpl += "<div  ><label  class='control-label'>" + show[item].label + " : </label><span show" + show[item].type + " index='" + item + "'  ></span></div>";
                    });
                    return tmpl;
                },
                replace: false,
                scope: false,
                require: 'ngModel'
            };
        }).
        directive('mainMenu', function ($routeParams) {
            return {
                template: function () {
                    var tmpl = '';
                    $.each(entity, function (item) {
                        if ($routeParams.entity == entity[item].name) {
                            tmpl += "<a class='nav-item nav-link active'  href='#/" + entity[item].name + "'>" + entity[item].label + "</a>";
                        } else {
                            tmpl += "<a class='nav-item nav-link' href='#/" + entity[item].name + "'>" + entity[item].label + "</a>";
                        }
                    });
                    return tmpl;
                },
                replace: false,
                scope: false
            };
        }).
        directive('inputstring', function () {
            return {
                template: '<input type="text"  class="form-control"   />',
                replace: true,
                require: 'ngModel',
                link: function (scope, elm, attrs) {
                    elm.removeAttr('inputstring');
                    elm.removeAttr('index');
                }
            };
        }).
        directive('inputfile', function ($routeParams, $compile) {
            return {
                template: '<input type="file"  class="form-control"   />',
                replace: true,
                scope: false,
                link: function ($scope, elm, attrs) {
                    $("<div style='position:fixed;width:100%;height:100vh;padding:30px;background:rgba(0,0,0,0.8);top:0;left:0;display:none;text-align:center;direction:ltr!important;z-index:999999999'></div>").insertAfter('#AddModal');
                    $($compile("<img ng-click='editeImage($event)' ng-src='{{selectedItem." + entity[$routeParams.entity]['form'][parseInt(attrs.index)].name + "}}' style='max-width:100%' />")($scope)).insertAfter(elm);
                    elm.change(function (evt) {
                        var file = evt.currentTarget.files[0];
                        var reader = new FileReader();
                        if (file.name.match(/\.(jpg|jpeg|png|gif)$/)) {
                            reader.onload = function (evt) {
                                $scope.imageLoadForEdit(evt.target.result);
                            };
                        } else {
                            reader.onload = function (evt) {
                                $scope.$apply(function ($scope) {
                                    $scope.selectedItem[entity[$routeParams.entity]['form'][parseInt(attrs.index)].name] = evt.target.result;
                                    console.log($scope.selectedItem[entity[$routeParams.entity]['form'][parseInt(attrs.index)].name]);
                                });
                            };
                        }
                        reader.readAsDataURL(file);
                    });
                },
                controller: function ($scope, $element) {
                    var index = $($element).attr("index");
                    $scope.editeImage = function (event) {
                        console.log($(event.target).before());
                        $(event.target).before().val('/');
                        $scope.imageLoadForEdit($scope.selectedItem[entity[$routeParams.entity]['form'][parseInt(index)].name]);
                    };

                    $scope.imageLoadForEdit = function (src) {
                        var image = new Image();
                        image.src = src;
                        var img;
                        var croper;
                        image.onload = function () {
                            var width = this.width;
                            var height = this.height;
                            $('#AddModal').next().html($compile("<img ng-src='{{myImage}}'  />\n\
                                        <div class='panelcrop'>\n\
                                        <button id='cancel' data-toggle='tooltip' data-placement='bottom' title='انصراف'><i class='fa fa-remove'></i></button>\n\
                                        <button id='submit' data-toggle='tooltip' data-placement='bottom' title='تایید'><i class='fa fa-check-square-o'></i></button>\n\
                                        <button id='reset' data-toggle='tooltip' data-placement='bottom' title='پاک کردن تغییرات'><i class='fa fa-refresh'></i></button>\n\
                                        <button id='left' data-toggle='tooltip' data-placement='bottom' title='حرکت به چپ'><i class='fa fa-hand-o-left'></i></button>\n\
                                        <button id='right' data-toggle='tooltip' data-placement='bottom' title='حرکت به راست'><i class='fa fa-hand-o-right'></i></button>\n\
                                        <button id='up' data-toggle='tooltip' data-placement='bottom' title='حرکت به بالا'><i class='fa fa-hand-o-up'></i></button>\n\
                                        <button id='bottom' data-toggle='tooltip' data-placement='bottom' title='حرکت به پایین'><i class='fa fa-hand-o-down'></i></button>\n\
                                        <button id='reversev' data-toggle='tooltip' data-placement='bottom' title='وارون افقی'><i class='fa fa-arrows-v'></i></button>\n\
                                        <button id='reverseh' data-toggle='tooltip' data-placement='bottom' title='وارون عمودی'><i class='fa fa-arrows-h'></i></button>\n\
                                        <button id='rotatel' data-toggle='tooltip' data-placement='bottom' title='چرخش به چپ'><i class='fa fa-undo'></i></button>\n\
                                        <button id='rotater' data-toggle='tooltip' data-placement='bottom' title='چرخش به راست'><i class='fa fa-repeat'></i></button>\n\
                                        <span class='selection'>(طول : {{selection.width}} , ارتفاع : {{selection.height}} )</span></div>")($scope));
                            var allElements = document.getElementsByTagName('*');
                            for (var i = 0, n = allElements.length; i < n; i++)
                            {
                                if (allElements[i].getAttribute('data-toggle') !== null)
                                {
                                    $(allElements[i]).tooltip();
                                }
                            }

                            $scope.$apply(function ($scope) {
                                $scope.myImage = image.src;
                            });
                            var width2 = 640;
                            var height2 = 360;
                            if (entity[$routeParams.entity]['form'][parseInt(index)].width) {
                                width2 = entity[$routeParams.entity]['form'][parseInt(index)].width;
                                console.log(width2);
                                var ratio = 16 / 9;
                                if (entity[$routeParams.entity]['form'][parseInt(index)].ratio) {
                                    ratio = parseFloat(entity[$routeParams.entity]['form'][parseInt(index)].ratio);
                                }
                                height2 = (1 / ratio) * width;
                                console.log(height2);
                            }
                            var cropper = $('#AddModal').next().find('img').cropper({aspectRatio: (entity[$routeParams.entity]['form'][parseInt(index)].ratio ? parseFloat(entity[$routeParams.entity]['form'][parseInt(index)].ratio) : 16 / 9), minCropBoxWidth: width2, minCanvasWidth: height2, maxWidth: 1024, minWidth: 640,
                                build: function () {
                                    img = $('#AddModal').next().find('img');

                                    if (width < width2 || height < height2) {
                                        var r = confirm("اندازه تصویر مناسب نیست در صورت تایید تصویر کیفیت  خود را از دست می دهد.");
                                        if (r == true) {
                                            if (img.width() < width2)
                                                img.width(width2);
                                            else
                                                img.height(height2);
                                        } else {
                                            return;
                                        }
                                    }
                                    $('#AddModal').next().show();
                                    $scope.$apply(function ($scope) {
                                        $scope.selection = {'width': img.width(), 'height': img.height()};
                                    });
                                    $(this).on('crop.cropper', function (e) {
                                        var size = $(this).cropper('getCropBoxData', true);
                                        $scope.$apply(function ($scope) {
                                            $scope.selection = {'width': Math.round(size.width), 'height': Math.round(size.height)};
                                        });

                                    });
                                }
                            });
                            $('.cropplus').on('click', function (e) {
                                e.preventDefault();
                                img = $(elm).next().find('img');
                                if (img.width() + 30 <= 1024 || img.height() + 20 <= 576) {
                                    img.width(img.width() + 30);
                                    croper.update();
                                }
                            });

                            $('#cancel').on('click', function (e) {
                                e.preventDefault();
                                $('#AddModal').next().hide();
                                $(cropper).cropper('destroy');
                            });
                            $('#left').on('click', function (e) {
                                e.preventDefault();
                                $(cropper).cropper('move', -10, 0);
                            });
                            $('#right').on('click', function (e) {
                                e.preventDefault();
                                $(cropper).cropper('move', 10, 0);
                            });
                            $('#up').on('click', function (e) {
                                e.preventDefault();
                                $(cropper).cropper('move', 0, -10);
                            });
                            $('#bottom').on('click', function (e) {
                                e.preventDefault();
                                $(cropper).cropper('move', 0, 10);
                            });
                            $('#reverseh').on('click', function (e) {
                                e.preventDefault();
                                $(cropper).cropper('scaleX', -$(cropper).cropper('getData').scaleX);
                            });
                            $('#reversev').on('click', function (e) {
                                e.preventDefault();
                                $(cropper).cropper('scaleY', -$(cropper).cropper('getData').scaleY);
                            });
                            $('#rotatel').on('click', function (e) {
                                e.preventDefault();
                                $(cropper).cropper('rotate', -5);
                            });
                            $('#rotater').on('click', function (e) {
                                e.preventDefault();
                                $(cropper).cropper('rotate', 5);
                            });
                            $('#reset').on('click', function (e) {
                                e.preventDefault();
                                $(cropper).cropper('reset');
                            });
                            $('#submit').on('click', function (e) {
                                e.preventDefault();
                                $scope.$apply(function ($scope) {
                                    var size = $(cropper).cropper('getCropBoxData', true);
                                    var width = size.width;
                                    var height = size.height;
                                    if (entity[$routeParams.entity]['form'][parseInt(index)].width) {
                                        width = entity[$routeParams.entity]['form'][parseInt(index)].width;
                                        var ratio = 16 / 9;
                                        if (entity[$routeParams.entity]['form'][parseInt(index)].ratio) {
                                            ratio = parseFloat(entity[$routeParams.entity]['form'][parseInt(index)].ratio);
                                        }
                                        height = (1 / ratio) * width;
                                    }
                                    $scope.selectedItem[entity[$routeParams.entity]['form'][parseInt(index)].name] = $(cropper).cropper('getCroppedCanvas', {
                                        width: width,
                                        height: height
                                    }).toDataURL("image/jpeg", 0.5);
                                    $('#AddModal').next().hide();
                                });
                            });
                        };
                    };
                }
            };
        }).
        directive('inputinteger', function () {
            return {
                template: '<input type="number"  class="form-control"   />',
                replace: true,
                require: 'ngModel',
                link: function (scope, elm, attrs) {
                    elm.removeAttr('inputinteger');
                    elm.removeAttr('index');
                }
            };
        }).
        directive('inputtext', function () {
            return {
                template: '<div  ckeditor="options"  class="form-control"  ready="onReady()" />',
                replace: true,
                require: 'ngModel',
                link: function (scope, elm, attrs) {
                    elm.removeAttr('inputtext');
                    elm.removeAttr('index');
                }
            };
        }).
        directive('inputentity', function ($routeParams, $http, $compile, $parse) {
            return {
                template: '<select  class="form-control"   /></select>',
                replace: true,
                require: 'ngModel',
                link: function ($scope, elm, attrs) {
                    $('#modaledit , #modalAdd').on('click', function () {
                        var parent_id = ($scope.selectedItem.id) ? $scope.selectedItem.id : -1;
                        var edit = entity[$routeParams.entity]['form'][parseInt(attrs.index)];
                        var filter = (edit.filter ? edit.filter : -1);
                        $http.get(BaseUrlAdmin + 'api/entities/' + edit.entityName + "/names/" + edit.name + "/parents/" + $routeParams.entity + "/ids/" + parent_id + "/props/" + edit.prop + "/filters/" + filter + '.json').success(function (response) {
                            var model = $parse(edit.name + "list");
                            model.assign($scope, response);
                            elm.html($compile('<option value="">انتخاب کنید...</option><option ng-selected="selectedItem.' + edit.name + '.id==item.id" ng-repeat="item in ' + edit.name + 'list" value="{{item.id}}">{{item.' + edit.prop + '}}</option>')($scope));
                        });
                        elm.removeAttr('inputentity');
                        elm.removeAttr('index');
                    });
                }
            };
        }).
        directive('inputboolean', function () {
            return {
                template: '<input type="checkbox"  />',
                replace: true,
                require: 'ngModel',
                link: function (scope, elm, attrs) {
                    elm.removeAttr('inputboolean');
                    elm.removeAttr('index');
                }
            };
        }).
        directive('picture', function () {
            return {
                template: '<img style="width:150px"  ng-src="{{picture}}" />',
                replace: true,
                scope: {
                    picture: '='
                }
            };
        }).
        directive('string', function () {
            return {
                template: '<span>{{string| cut:true:20:"..."}}</span>',
                replace: true,
                scope: {
                    string: '='
                }
            };
        }).
        directive('datetime', function () {
            return {
                template: '<span>{{datetime| ConvertedToDateShamsi }}</span>',
                replace: true,
                scope: {
                    datetime: '='
                }
            };
        }).
        directive('link', function () {
            return {
                template: '<a href="{{link}}">{{mylink}}</a>',
                replace: true,
                scope: {
                    link: '='
                }
            };
        }).
        directive('money', function () {
            return {
                template: '<span>{{money| Rial }}</span>',
                replace: true,
                scope: {
                    money: '='
                }
            };
        }).
        directive('boolean', function () {
            return {
                template: '<span>{{boolean | boolean }}</span>',
                replace: true,
                scope: {
                    boolean: '='
                }
            };
        }).
        directive('text', function () {
            return {
                template: '<span>{{text| htmlToPlaintext | cut:true:200:"..."}}</span>',
                replace: true,
                scope: {
                    text: '='
                }
            };
        }).
        directive('integer', function () {
            return {
                template: '<span>{{integer}}</span>',
                replace: true,
                scope: {
                    integer: '='
                }
            };
        }).
        directive('mydef', function () {
            return {
                template: '<span>{{mydef}}</span>',
                replace: true,
                scope: {
                    mydef: '='
                }
            };
        }).
        directive('entity', function () {
            return {
                template: '<span>{{entity}}</span>',
                replace: true,
                scope: {
                    entity: '='
                }
            };
        }).
        directive('showstring', function ($routeParams, $compile) {
            return {
                template: '<span /></span>',
                replace: true,
                scope: false,
                link: function ($scope, elm, attrs) {
                    var show = entity[$routeParams.entity]['show'][parseInt(attrs.index)];
                    elm.html($compile('<strong>{{selectedItem.' + show.name + '}}</strong>')($scope));
                    elm.removeAttr('index');
                }
            };
        }).
        directive('showinteger', function ($routeParams, $compile) {
            return {
                template: '<span /></span>',
                replace: true,
                scope: false,
                link: function ($scope, elm, attrs) {
                    var show = entity[$routeParams.entity]['show'][parseInt(attrs.index)];
                    elm.html($compile('<strong>{{selectedItem.' + show.name + '}}</strong>')($scope));
                    elm.removeAttr('index');
                }
            };
        }).
        directive('showtext', function ($routeParams, $compile) {
            return {
                template: '<span /></span>',
                replace: true,
                scope: false,
                link: function ($scope, elm, attrs) {
                    var show = entity[$routeParams.entity]['show'][parseInt(attrs.index)];
                    elm.html($compile('<div ng-bind-html-unsafe="selectedItem.' + show.name + '"></div>')($scope));
                    elm.removeAttr('index');
                }
            };
        }).
        directive('showdatetime', function ($routeParams, $compile) {
            return {
                template: '<span /></span>',
                replace: true,
                scope: false,
                link: function ($scope, elm, attrs) {
                    var show = entity[$routeParams.entity]['show'][parseInt(attrs.index)];
                    elm.html($compile('<span>{{selectedItem.' + show.name + ' | ConvertedToDateShamsi}}</span>')($scope));
                    elm.removeAttr('index');
                }
            };
        }).
        directive('showboolean', function ($routeParams, $compile) {
            return {
                template: '<span /></span>',
                replace: true,
                scope: false,
                link: function ($scope, elm, attrs) {
                    var show = entity[$routeParams.entity]['show'][parseInt(attrs.index)];
                    elm.html($compile('<span>{{selectedItem.' + show.name + ' | boolean}}</span>')($scope));
                    elm.removeAttr('index');
                }
            };
        }).
        directive('showpicture', function ($routeParams, $compile) {
            return {
                template: '<span /></span>',
                replace: true,
                scope: false,
                link: function ($scope, elm, attrs) {
                    var show = entity[$routeParams.entity]['show'][parseInt(attrs.index)];
                    elm.html($compile('<img ng-src="{{selectedItem.' + show.name + '}}" style="max-width:100%" />')($scope));
                    elm.removeAttr('index');
                }
            };
        });