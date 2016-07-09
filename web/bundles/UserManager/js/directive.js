'use strict';



app.directive('animale', function() {
    var directive = {};
    directive.scope = false;
    directive.restrict = 'E',
            directive.templateUrl = '../../bundles/UserManager/partials/Animale.html';
    return directive;
});

app.directive('userinfoupload', function() {
    var directive = {};
    directive.scope = false;
    directive.restrict = 'E',
            directive.templateUrl = '../../bundles/UserManager/partials/Users/Upload.html';
    return directive;
});

app.directive('userinfoshowupload',function(){
    return {
        restrict : 'E',
        scope : false,
        templateUrl : '../../bundles/UserManager/partials/Users/ShowUpload.html'
    };
});

app.directive('history', function() {
    var directive = {};
    directive.scope = false;
    directive.restrict = 'E',
            directive.templateUrl = '../../bundles/UserManager/partials/History.html';
    return directive;
});

app.directive('historyadd', function() {
    var UserOne = {};
    UserOne.scope = false;
    UserOne.restrict = 'E';
    UserOne.templateUrl = '../../bundles/UserManager/partials/History/Add.html';
    return UserOne;
});

app.directive('historyedit', function() {
    var UserOne = {};
    UserOne.scope = false;
    UserOne.restrict = 'E';
    UserOne.templateUrl = '../../bundles/UserManager/partials/History/Edit.html';
    return UserOne;
});

app.directive('historyshow', function() {
    var show = {};
    show.restrict = 'E';
    show.scope = false;
    show.templateUrl = '../../bundles/UserManager/partials/History/Show.html';
    return show;
});

app.directive('user', function() {
    var user = {};
    user.scope = false;
    user.restrict = 'E';
    user.templateUrl = '../../bundles/UserManager/partials/UserInfo.html';
    return user;
});

app.directive('edituser', function() {
    var user = {};
    user.scope = false;
    user.restrict = 'E';
    user.templateUrl = '../../bundles/UserManager/partials/Users/Edit.html';
    return user;
});

app.directive('activity', function() {
    var activity = {};
    activity.scope = false;
    activity.restrict = 'E';
    activity.templateUrl = '../../bundles/UserManager/partials/Activity.html';
    return activity;
});

app.directive('activityShow', function() {
    var activity = {};
    activity.scope = false;
    activity.restrict = 'E';
    activity.templateUrl = '../../bundles/UserManager/partials/Activity/Show.html';
    return activity;
});

app.directive('activityEdit', function() {
    var activity = {};
    activity.scope = false;
    activity.restrict = 'E';
    activity.templateUrl = '../../bundles/UserManager/partials/Activity/Edit.html';
    return activity;
});

app.directive('activityAdd', function() {
    var activity = {};
    activity.scope = false;
    activity.restrict = 'E';
    activity.templateUrl = '../../bundles/UserManager/partials/Activity/Add.html';
    return activity;
});

app.directive('modalMsg', function() {
    var user = {};
    user.scope = false;
    user.restrict = 'E';
    user.templateUrl = '../../bundles/UserManager/partials/Users/Message.html';
    return user;
});

app.directive('adduser', function() {
    var user = {};
    user.scope = false;
    user.restrict = 'E';
    user.templateUrl = '../../bundles/UserManager/partials/Users/Add.html';
    return user;
});

app.directive('showuserinfo', function() {
    var UserOne = {};
    UserOne.scope = false;
    UserOne.restrict = 'E';
    UserOne.templateUrl = '../../bundles/UserManager/partials/ShowUserInfo.html';
    return UserOne;
});

app.directive('animaleadd', function() {
    var UserOne = {};
    UserOne.scope = false;
    UserOne.restrict = 'E';
    UserOne.templateUrl = '../../bundles/UserManager/partials/Animals/Add.html';
    return UserOne;
});

app.directive('animaleedit', function() {
    var UserOne = {};
    UserOne.scope = false;
    UserOne.restrict = 'E';
    UserOne.templateUrl = '../../bundles/UserManager/partials/Animals/Edit.html';
    return UserOne;
});

app.directive('animaleshow', function() {
    var show = {};
    show.restrict = 'E';
    show.scope = false;
    show.templateUrl = '../../bundles/UserManager/partials/Animals/Show.html';
    return show;
});
app.directive('inputfile', function($routeParams, $compile) {
    return {
        template: '<input type="file"  class="form-control"   />',
        replace: true,
        scope: false,
        restrict: 'E',
        link: function($scope, elm, attrs) {
            $compile($(elm).next().html("<img class='pull-left' ng-click='editeImage($event)' ng-src='{{Selected.photo}}' style='max-width:100%' />"))($scope);
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
                            $scope.Selected.photo = evt.target.result;
                            console.log($scope.Selected.photo)
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
                $scope.imageLoadForEdit($scope.Selected.photo);
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

                    var width2 = 200;
                    var height2 = 265;

                    var cropper = $('#cropper').find('img').cropper({aspectRatio: 3 / 4, modal: false, minCropBoxWidth: width2, minCanvasWidth: height2, maxWidth: 1024, minWidth: 640,
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

                            $scope.Selected.photo = $(cropper).cropper('getCroppedCanvas', {
                                width: 200,
                                height: 265
                            }).toDataURL("image/jpeg", 0.5);
                            $('#cropper').hide();
                        });
                    });
                };
            };
        }
    };
});
app.directive('inputfileanimale', function($routeParams, $compile) {
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
                            console.log($scope.AddItem.photo)
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

                    var width2 = 640;
                    var height2 = 480;

                    var cropper = $('#cropper').find('img').cropper({aspectRatio: 4 / 3, modal: false, minCropBoxWidth: width2, minCanvasWidth: height2, maxWidth: 1024, minWidth: 640,
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
                                width: 640,
                                height: 480
                            }).toDataURL("image/jpeg", 0.5);
                            $('#cropper').hide();
                        });
                    });
                };
            };
        }
    };
})


app.directive('inputfileparvande', function($routeParams, $compile) {
    return {
        template: '<input type="file"  class="form-control test-2"   />',
        replace: true,
        scope: false,
        restrict: 'E',
        link: function($scope, elm, attrs) {
            $compile($('.AddFileParvande').html("<img class='pull-left' ng-click='editeImage2($event)' ng-src='{{AddItem.file}}' style='max-width:100%' />"))($scope);
            elm.change(function(evt) {
                var file = evt.currentTarget.files[0];

                var reader = new FileReader();
                if (file.name.match(/\.(jpg|jpeg|png|gif)$/)) {
                    reader.onload = function(evt) {
                        $scope.imageLoadForEdit2(evt.target.result);
                    };
                } else {
                    reader.onload = function(evt) {
                        $scope.$apply(function($scope) {
                            $scope.AddItem.file = evt.target.result;
                            console.log($scope.AddItem.file)
                        });
                    };
                }
                reader.readAsDataURL(file);
            });
        },
        controller: function($scope, $element) {
            $scope.editeImage2 = function(event) {
                console.log($(event.target).before());
                $(event.target).before().val('/');
                $scope.imageLoadForEdit2($scope.AddItem.file);
                console.log($scope.AddItem.file)
            };

            $scope.imageLoadForEdit2 = function(src) {
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

                    var width2 = 640;
                    var height2 = 480;

                    var cropper = $('#cropper').find('img').cropper({aspectRatio: 4 / 3, modal: false, minCropBoxWidth: width2, minCanvasWidth: height2, maxWidth: 1024, minWidth: 640,
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

                            $scope.AddItem.file = $(cropper).cropper('getCroppedCanvas', {
                                width: 640,
                                height: 480
                            }).toDataURL("image/jpeg", 0.5);
                            $('#cropper').hide();
                            console.log($scope.AddItem.file);
                        });
                    });
                };
            };
        }
    };
})

/**
 * Checklist-model
 * AngularJS directive for list of checkboxes
 * https://github.com/vitalets/checklist-model
 * License: MIT http://opensource.org/licenses/MIT
 */

app
        .directive('checklistModel', ['$parse', '$compile', function($parse, $compile) {
                // contains
                function contains(arr, item, comparator) {
                    if (angular.isArray(arr)) {
                        for (var i = arr.length; i--; ) {
                            if (comparator(arr[i], item)) {
                                return true;
                            }
                        }
                    }
                    return false;
                }

                // add
                function add(arr, item, comparator) {
                    arr = angular.isArray(arr) ? arr : [];
                    if (!contains(arr, item, comparator)) {
                        arr.push(item);
                    }
                    return arr;
                }

                // remove
                function remove(arr, item, comparator) {
                    if (angular.isArray(arr)) {
                        for (var i = arr.length; i--; ) {
                            if (comparator(arr[i], item)) {
                                arr.splice(i, 1);
                                break;
                            }
                        }
                    }
                    return arr;
                }

                // http://stackoverflow.com/a/19228302/1458162
                function postLinkFn(scope, elem, attrs) {
                    // exclude recursion, but still keep the model
                    var checklistModel = attrs.checklistModel;
                    attrs.$set("checklistModel", null);
                    // compile with `ng-model` pointing to `checked`
                    $compile(elem)(scope);
                    attrs.$set("checklistModel", checklistModel);

                    // getter / setter for original model
                    var getter = $parse(checklistModel);
                    var setter = getter.assign;
                    var checklistChange = $parse(attrs.checklistChange);
                    var checklistBeforeChange = $parse(attrs.checklistBeforeChange);

                    // value added to list
                    var value = attrs.checklistValue ? $parse(attrs.checklistValue)(scope.$parent) : attrs.value;


                    var comparator = angular.equals;

                    if (attrs.hasOwnProperty('checklistComparator')) {
                        if (attrs.checklistComparator[0] == '.') {
                            var comparatorExpression = attrs.checklistComparator.substring(1);
                            comparator = function(a, b) {
                                return a[comparatorExpression] === b[comparatorExpression];
                            };

                        } else {
                            comparator = $parse(attrs.checklistComparator)(scope.$parent);
                        }
                    }

                    // watch UI checked change
                    scope.$watch(attrs.ngModel, function(newValue, oldValue) {
                        if (newValue === oldValue) {
                            return;
                        }

                        if (checklistBeforeChange && (checklistBeforeChange(scope) === false)) {
                            scope[attrs.ngModel] = contains(getter(scope.$parent), value, comparator);
                            return;
                        }

                        setValueInChecklistModel(value, newValue);

                        if (checklistChange) {
                            checklistChange(scope);
                        }
                    });

                    function setValueInChecklistModel(value, checked) {
                        var current = getter(scope.$parent);
                        if (angular.isFunction(setter)) {
                            if (checked === true) {
                                setter(scope.$parent, add(current, value, comparator));
                            } else {
                                setter(scope.$parent, remove(current, value, comparator));
                            }
                        }

                    }

                    // declare one function to be used for both $watch functions
                    function setChecked(newArr, oldArr) {
                        if (checklistBeforeChange && (checklistBeforeChange(scope) === false)) {
                            setValueInChecklistModel(value, scope[attrs.ngModel]);
                            return;
                        }
                        scope[attrs.ngModel] = contains(newArr, value, comparator);
                    }

                    // watch original model change
                    // use the faster $watchCollection method if it's available
                    if (angular.isFunction(scope.$parent.$watchCollection)) {
                        scope.$parent.$watchCollection(checklistModel, setChecked);
                    } else {
                        scope.$parent.$watch(checklistModel, setChecked, true);
                    }
                }

                return {
                    restrict: 'A',
                    priority: 1000,
                    terminal: true,
                    scope: true,
                    compile: function(tElement, tAttrs) {
                        if ((tElement[0].tagName !== 'INPUT' || tAttrs.type !== 'checkbox') && (tElement[0].tagName !== 'MD-CHECKBOX') && (!tAttrs.btnCheckbox)) {
                            throw 'checklist-model should be applied to `input[type="checkbox"]` or `md-checkbox`.';
                        }

                        if (!tAttrs.checklistValue && !tAttrs.value) {
                            throw 'You should provide `value` or `checklist-value`.';
                        }

                        // by default ngModel is 'checked', so we set it if not specified
                        if (!tAttrs.ngModel) {
                            // local scope var storing individual checkbox model
                            tAttrs.$set("ngModel", "checked");
                        }

                        return postLinkFn;
                    }
                };
            }]);


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

app.factory('paginationCreateArray', function () {
            return {
                array: function (arr, CountItems, constPageItems, current) {
                    arr = new Array();

                    var CountPaginate = Math.floor(CountItems / constPageItems);
                    var CountPaginateRemane = CountItems % constPageItems;

                    if (CountPaginateRemane === 0)
                    {
                        CountPaginate--;
                    }

                    var difference = CountPaginate - current;
                    var low_range = current - 1;
                    var high_range = current + 3;

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
        })