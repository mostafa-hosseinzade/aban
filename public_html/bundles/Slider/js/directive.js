'use strict';

app.directive('slider', function() {
    var user = {};
    user.scope = false;
    user.restrict = 'E';
    user.templateUrl = '../../bundles/Slider/partials/SliderInfo.html';
    return user;
});

app.directive('imagedata', function($compile) {
    var data = {};
    data.restrict = 'E';
    data.link = function($scope, element, attributes, controller) {
        $scope.src = attributes.value;
        $scope.key = attributes.key;
        $scope.key2 = attributes.key2;
        if (($scope.key == 'ForumBundleimg' || $scope.key == 'ShopBundleimg') && $scope.key2 !='label') {
            $compile($(element).next().html("<img class='pull-left' ng-src='{{src}}' style='min-width: 235px;min-height: 120px;max-width: 400px;max-height: 400px' class='img img-responsive' />"))($scope);
        }
    }
    return data;
});

app.directive('showslider', function() {
    var user = {};
    user.scope = false;
    user.restrict = 'E';
    user.templateUrl = '../../bundles/Slider/partials/Sliders/show.html';
    return user;
});

app.service('fileUpload', ['$http', function($http) {
        this.uploadFileToUrl = function(file, uploadUrl) {
            var fd = new FormData();
            fd.append('file', file);
            $http.post(uploadUrl, fd, {
                transformRequest: angular.identity,
                headers: {'Content-Type': undefined}
            }).success(function() {

            }).error(function() {
            });
        }
    }]);

app.directive('fileModel', ['$parse', function($parse) {
        return {
            restrict: 'A',
            link: function(scope, element, attrs) {
                var model = $parse(attrs.fileModel);
                var modelSetter = model.assign;

                element.bind('change', function() {
                    scope.$apply(function() {
                        modelSetter(scope, element[0].files[0]);
                    });
                });
            }
        };
    }]);

app.directive('modalMsg', function() {
    var user = {};
    user.scope = false;
    user.restrict = 'E';
    user.templateUrl = '../../bundles/Slider/partials/Sliders/Message.html';
    return user;
});


app.directive('addslider', function() {
    var user = {};
    user.scope = false;
    user.restrict = 'E';
    user.templateUrl = '../../bundles/Slider/partials/Sliders/Add.html';
    return user;
});

app.directive('editslider', function() {
    var user = {};
    user.scope = false;
    user.restrict = 'E';
    user.templateUrl = '../../bundles/Slider/partials/Sliders/Edit.html';
    return user;
});

///////////////////
// Key Value
///////////////////



app.directive('messagekeyvalue', function() {
    var KeyValue = {};
    KeyValue.scope = false;
    KeyValue.restrict = 'E';
    KeyValue.templateUrl = '../../bundles/Slider/partials/KeyValue/Message.html';
    return KeyValue;
});


app.directive('editkeyvalue', function() {
    var user = {};
    user.scope = false;
    user.restrict = 'E';
    user.templateUrl = '../../bundles/Slider/partials/KeyValue/Edit.html';
    return user;
});
app.directive('showkeyvalue', function() {
    var KeyValue = {};
    KeyValue.scope = false;
    KeyValue.restrict = 'E';
    KeyValue.templateUrl = '../../bundles/Slider/partials/KeyValue/Show.html';
    return KeyValue;
});




app.directive('inputfile', function($routeParams, $compile) {
    return {
        template: '<input type="file"  class="form-control"   />',
        replace: true,
        scope: false,
        restrict: 'E',
        link: function($scope, elm, attrs) {
            $compile($(elm).next().html("<img class='pull-left' ng-click='editeImage($event)' ng-src='{{AddItem.photo}}' style='max-width:100%' />"))($scope);
            elm.change(function(evt) {
                var file = evt.currentTarget.files[0];
                var reader = new FileReader();
                if (file.name.match(/\.(jpg|jpeg|png|gif)$/)) {
                    reader.onload = function(evt) {
                        $scope.imageLoadForEdit(evt.target.result);
                    };
                } else {
                    reader.onload = function(evt) {
                        $scope.$apply(function($scope) {
                            $scope.AddItem.photo = evt.target.result;
//                            console.log($scope.Selected.photo)
                        });
                    };
                }
                reader.readAsDataURL(file);
            });
        },
        controller: function($scope, $element) {
            $scope.editeImage = function(event) {
                console.log($(event.target).before());
                $(event.target).before().val('/');
                $scope.imageLoadForEdit($scope.AddItem.photo);
            };

            $scope.imageLoadForEdit = function(src) {
                var image = new Image();
                image.src = src;
                var img;
                var croper;
                image.onload = function() {
                    var width = this.width;
                    var height = this.height;

                    $('#cropper').html($compile("<div style='position:relative;height:100vh'><img ng-src='{{myImage}}'  />\n\
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
                                        <span class='selection'>(طول : {{selection.width}} , ارتفاع : {{selection.height}} )</span></div></div>")($scope));
                    var allElements = document.getElementsByTagName('*');
                    for (var i = 0, n = allElements.length; i < n; i++)
                    {
                        if (allElements[i].getAttribute('data-toggle') !== null)
                        {
                            $(allElements[i]).tooltip();
                        }
                    }

                    $scope.$apply(function($scope) {
                        $scope.myImage = image.src;
                    });

                    var width2 = 1024;
                    var height2 = 578;

                    var cropper = $('#cropper').find('img').cropper({aspectRatio: 16 / 9, modal: false, minCropBoxWidth: width2, minCanvasWidth: height2, maxWidth: 1024, minWidth: 640,
                        build: function() {
                            img = $('#cropper').find('img');

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
                            $('#cropper').show();
                            $scope.$apply(function($scope) {
                                $scope.selection = {'width': img.width(), 'height': img.height()};
                            });
                            $(this).on('crop.cropper', function(e) {
                                var size = $(this).cropper('getCropBoxData', true);
                                $scope.$apply(function($scope) {
                                    $scope.selection = {'width': Math.round(size.width), 'height': Math.round(size.height)};
                                });

                            });
                        }
                    });
                    $('.cropplus').on('click', function(e) {
                        e.preventDefault();
                        img = $(elm).next().find('img');
                        if (img.width() + 30 <= 1024 || img.height() + 20 <= 576) {
                            img.width(img.width() + 30);
                            croper.update();
                        }
                    });

                    $('#cancel').on('click', function(e) {
                        e.preventDefault();
                        $('#cropper').hide();
                        $(cropper).cropper('destroy');
                    });
                    $('#left').on('click', function(e) {
                        e.preventDefault();
                        $(cropper).cropper('move', -10, 0);
                    });
                    $('#right').on('click', function(e) {
                        e.preventDefault();
                        $(cropper).cropper('move', 10, 0);
                    });
                    $('#up').on('click', function(e) {
                        e.preventDefault();
                        $(cropper).cropper('move', 0, -10);
                    });
                    $('#bottom').on('click', function(e) {
                        e.preventDefault();
                        $(cropper).cropper('move', 0, 10);
                    });
                    $('#reverseh').on('click', function(e) {
                        e.preventDefault();
                        $(cropper).cropper('scaleX', -$(cropper).cropper('getData').scaleX);
                    });
                    $('#reversev').on('click', function(e) {
                        e.preventDefault();
                        $(cropper).cropper('scaleY', -$(cropper).cropper('getData').scaleY);
                    });
                    $('#rotatel').on('click', function(e) {
                        e.preventDefault();
                        $(cropper).cropper('rotate', -5);
                    });
                    $('#rotater').on('click', function(e) {
                        e.preventDefault();
                        $(cropper).cropper('rotate', 5);
                    });
                    $('#reset').on('click', function(e) {
                        e.preventDefault();
                        $(cropper).cropper('reset');
                    });
                    $('#submit').on('click', function(e) {
                        e.preventDefault();
                        $scope.$apply(function($scope) {
                            var size = $(cropper).cropper('getCropBoxData', true);
                            var width = size.width;
                            var height = size.height;

                            $scope.AddItem.photo = $(cropper).cropper('getCroppedCanvas', {
                                width: 1024,
                                height: 578
                            }).toDataURL("image/jpeg", 0.8);
                            $('#cropper').hide();
                        });
                    });
                };
            };
        }
    };
});

function NotifyCation(title, text, type, hide) {
    new PNotify({
        title: title,
        text: text,
        type: type,
        hide: hide,
        nonblock: {
            nonblock: true
        },
        before_close: function(PNotify) {
            // You can access the notice's options with this. It is read only.
            //PNotify.options.text;

            // You can change the notice's options after the timer like this:
            PNotify.update({
                title: PNotify.options.title + " - Enjoy your Stay",
                before_close: null
            });
            PNotify.queueRemove();
            return false;
        }
    });
}

app.factory('paginationCreateArray', function() {
    return {
        array: function(CountPaginate, current) {
            var arr = new Array();
            if (CountPaginate < 10)
            {  // kamtar az 10
                for (var i = 0; i < CountPaginate; i++) {
                    arr[i] = i + 1;
                }
            } else {
                if (current > 8 && current <= CountPaginate - 8)
                {
                    // vasat
                    arr[0] = 1;
                    arr[1] = 0;
                    var j = 2;
                    for (var i = current - 2; i < current + 4; i++) {
                        arr[j] = i;
                        j++;
                    }
                    arr[8] = 0;
                    arr[9] = CountPaginate;
                } else if (current > CountPaginate - 8)
                {
                    //last
                    arr[0] = 1;
                    arr[1] = 0;
                    var j = 2;
                    for (var i = CountPaginate - 7; i <= CountPaginate; i++) {
                        arr[j] = i;
                        j++;
                    }
                } else {
                    //first
                    for (var i = 0; i <= 7; i++) {
                        arr[i] = i + 1;
                    }
                    arr[8] = 0;
                    arr[9] = CountPaginate;
                }
            }
            return arr;
        }
    };
});

