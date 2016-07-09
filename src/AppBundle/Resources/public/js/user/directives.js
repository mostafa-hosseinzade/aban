'use strict';

/* Directives */


angular.module('myApp.directives', ['ngCookies'])
        //show loading page for all ajax (angularJS) request
        .directive('loading', ['$http', function ($http)
            {
                return {
                    restrict: 'A',
                    link: function (scope, elm, attrs)
                    {
                        $(elm).find('img').attr('src', baseURL + '/bundles/public/img/loading.gif');
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
            }])

        /// Mask Directive work by jquery.mask
        .directive('maskedInput', function ()
        {
            return {
                restrict: 'A',
                scope: false,
                link: function ($scope, elem, attrs) {
                    $.mask.definitions['~'] = "[+-]";
                    var def = $(elem).mask(attrs.pattern);
                    $(elem).on('keyup', function () {
                        var value = $(elem).val();
                        $scope.$apply(function () {
                            //console.log(attrs.septrator);
                            var re = new RegExp(attrs.septrator, "g");
                            $scope[attrs.id] = value.replace(re, '');
                        });

                    });
                }
            };
        })

        .directive('persianMony', function ()
        {
            return {
                restrict: 'A',
                scope: false,
                link: function ($scope, elem, attrs) {

                    function splitMonyTextBox(ctrl)
                    {
                        var separator = ",";
                        var int = ctrl.replace(new RegExp(separator, "g"), "");
                        var regexp = new RegExp("\\B(\\d{3})(" + separator + "|$)");
                        do
                        {
                            int = int.replace(regexp, separator + "$1");
                        }
                        while (int.search(regexp) >= 0)
                        return int;
                    }
                    function isNumberKey(evt)
                    {
                        var charCode = (evt.which) ? evt.which : evt.keyCode
                        if (charCode > 31 && (charCode < 48 || charCode > 57))
                            return false;
                        return true;
                    }

                    $(elem).on('keyup', function (e) {
                        var x = splitMonyTextBox($(elem).val());
                        $(elem).val(x);
                        $scope.$apply(function () {
                            var re = new RegExp(',', "g");
                            $scope[attrs.id] = x.replace(re, '');
                            //console.log($scope);
                        });

                        if (!isNumberKey(e)) {
                            var str = $(elem).val();
                            $(elem).val(str.replace(/[^0-9,\\.]+/g, ''));
                        }
                    });

                    $(elem).on('keypress', function (e) {
                        if (!isNumberKey(e))
                            e.preventDefault();
                    });

                }
            }
            ;
        })

//------------------------------------------------------------------------------
        ////user directive control
        //change info user
        .directive('informationUser', ['$window', function ($window) {
                return {
                    restrict: 'E', //E = element, A = attribute, C = class, M = comment   
                    templateUrl: '../bundles/app/partials/users/UserInformation.html',
                    $scope: false,
                    controller: function ($scope, $http) {
                        $http.get(baseURL + "/cli_panel/user.json").success(function (response) {
                            $scope.userinfo = response;
                            $scope.name = response[0].name;
                            $scope.family = response[0].family;
                            $scope.email = response[0].email;
                            $scope.phone = response[0].phone;
                            $scope.mobile = response[0].mobile;
                            $scope.address = response[0].address;
                            $scope.username = response[0].username;
                            $scope.photo = response[0].photo;

                            console.log($scope.photo);

                            $scope.money = response[0].money;

                            if (!$scope.photo) {
                                $scope.photo = "data:image/png;base64,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";
                            }
                        });
                        $scope.currentDocument;
                        $scope.tableIndex;
                        //function for load docs of user
                        $scope.ShowloadDocInfoUser;
                        $scope.loadDocInfoUser = function () {
                            $http.get(baseURL + "/cli_panel/document/user.json").success(function (response) {
                                $scope.ShowloadDocInfoUser = response;
                            });
                        };
                        $scope.RepresentDocumentUser = false;
                        $scope.classShowDocument = 'glyphicon-eye-open';
                        $scope.ClickShowDocument = function () {
                            if (!$scope.RepresentDocumentUser)
                            {
                                $scope.loadDocInfoUser();
                                $scope.classShowDocument = 'glyphicon-eye-close';
                            } else {
                                $scope.classShowDocument = 'glyphicon-eye-open';
                            }
                            $scope.RepresentDocumentUser = !$scope.RepresentDocumentUser;
                        };

                        $scope.downloadFileDoc = function (photo) {
                            $window.location.href = photo;
                        };

                        $scope.showModalDelete = function (index, idDoc) {
                            $scope.currentDocument = idDoc;
                            $scope.tableIndex = index;
                            $("#ModalDeleteDoc").modal('show');
                        };
                        $scope.deleteDoc = function () {
                            //cli_panel/documents/{id}/user/delete.{_format}
                            $http({
                                method: 'DELETE',
                                url: baseURL + '/cli_panel/documents/' + $scope.currentDocument + '/user/delete.json',
                            }).then(function (response) {
                                if (response.data.deleteMessage == 0)
                                {
                                    $scope.ShowloadDocInfoUser.splice($scope.tableIndex, 1);
                                }
                            });
                        };

                        $scope.ShowAddDocumentModal = function () {
                            $('#ModalAddDocument').modal('show');
                        };

                        $scope.addDocumentPhoto = function () {
                            var data = {
                                'photo': $scope.photoDocument,
                                'documentTitle': $scope.titleDocument,
                            };

                            $http({
                                method: 'POST',
                                url: baseURL + '/cli_panel/users/documents/inserts.json',
                                data: data
                            }).then(function (response) {
                                if (response.data.message == 0)
                                {
                                    $('#ModalAddDocument').modal('hide');
                                    $scope.RepresentDocumentUser = false;
                                    $scope.photoDocument = '';
                                    $scope.titleDocument = '';
                                    $('#ShowImageDoc').attr('src', 'data:image/png;base64,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ZZ2xavz48XnLW96Sww47rMsxL09yc5cDEgEDeqStgP3oRz9qZZ2xbNKkSXnb297W9anOF5dSbupygIABPdHGJcQk+epXv9rKOmPd1KlTc9NNN2X+/PldjrmqlHJNV4sLGNATbb0C+9a3vpVf/vKXraw11s2YMSMrVqzIHnt0+jCNZaWUTh4JImBAT8ycOTMTJkzY4XXWrVuXb3/72y3siGT4lfHKlSuzyy6dPkzjT0opr2h7UQEDemJgYCALFy5sZa3PfOYzrazDsF133bUXpzp/sJTy4jYXFTCgZ449tp2zEL/+9a/nrrvuamUthu29995ZsWJF16c6f6yU8vttLShgQM8cc8wxrR3xccstt7SyDv9mwYIFWbZsWSZPntzViIlJPlVKOaGNxQQM6Jk5c+bkoIMOamWtu+++O1/+8pdbWYt/s+lU5zber9yCKUk+U0opO7qQgAE91dZlxCR597vfnR//+Metrcewww8/PNdee20rz6/cghlJvlBKOXRHFhEwoKfaDNjatWtz3XXX5aGHHmptTYYdc8wxXZ/qPCfJHaWUBdu7gIABPbX33ntnr732am29e++9N0uXLnVvWAdOOumkvP71r+9yxO4ZPhBzu/6DGN/yZkakX/7yl/ne977X723AM5o1a1b233//fm+jc2eeeWbe+973trbed77znVx++eV561vf2vX9TGPOC17wgqxdu7bLD83Mz3DEjm+a5v9ty18cEwFbv359vvWtb/V7G/CMFixYMCYCdtppp+VTn/pUfvrTn7a25j333JNLL700119/fQ49dIfeWuEpzj777Kxduza33nprVyMOSvLFUsqJTdM8sLV/ySVEoOfGjRuXCy64oPV1h4aG8qY3vSkf/ehHs27dutbXH8vOP//8nHnmmV2OWJzk86WUrb4RTcCAvjjhhBNy8MEHt77ur371q3zkIx/Jeeedl89+9rN5/PHHW58xVl188cV53vOe1+WIo5N8upQyaWu+WMCAvrnwwgs7W3v16tV5z3vek1e+8pX54Ac/mLvvvjsbN27sbN5YMDAwkMsvvzwnnNDKfchbclKSPy+lPONbXGPiPTBgZFq0aFGWLFmSb3zjG53N+NnPfpaPf/zj+fjHP57Zs2fniCOOyK677prZs2dnzpw5mT17dmbMmNHlx8VHnVe96lV58MEH893vfrerEacn+XAp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gAFQJQEDoEoCBkCVBAyAKgkYAFUSMACqJGAAVKmVgD366KNtLAPAKLZu3bpW1xtMsnZHFxkaGmphKwCMZvfff39bS61OhgN2746uJGAAPJMWW3Fv0lLAWqwqAKNUFwH76Y6uJGAAPJNf/OIXbS31s6SlgK1duzY/+MEPdnhHAIxed911VxvLrGmaZm3SUsCS5M4772xjGQBGofvvvz/33HNPG0v9+m2vVt4DS5JVq1a1sQwAo9CqVauycePGNpb62aafDCa5u40Vf/jDH+bee1tpIQCjzFe+8pW2lvp1swabprkryY/bWPXWW29tYxkARpF//Md/zN///d+3tdxnN/1k05M4/qqNVe+444788Ic/bGMpAEaJW265pa2lHk7ypU2/aDVgGzduzAc+8IE2lgJgFFi1alW+//3vt7XcF5umWb/pF5sC9r+SPNjG6t/85jfzd3/3d20sBUDFHnnkkfzpn/5pm0t++sm/GEySpmkeS/LXbU1YuXKlS4kAY9iGDRuybNmyNj/c93iSzz35N578NPpWLiMmw08cvv766/Pgg628qAOgMrfccku++c1vtrnknU3T/MZjn54csM8neaStSffdd1+uvfZaEQMYYz7xiU/kk5/8ZNvL/sVTf+PXAWua5sEk72lz2t13351LLrnE5USAMeCxxx7LypUru/gw30+TfPCpv/nUAy1XJGntaYtJ8q//+q+57LLL8rd/+7dtLgvACDI0NJTLL788d9xxRxfLX9c0zW+dnDzw1Ed7lFL+KMmfdLGDo446Kq961asyf/78LpYHoMfWr1+f2267LZ/4xCeydu0On4+8Od9Psqhpmg1P/YPNBWxCkn9KsqCLnQwODuaUU07Jueeem1133bWLEQB07LHHHssdd9yRj3zkI10fqfX8pmk2+yn53wpYkpRSzk7yiS53lCQHHnhgjjnmmBx99NFZsKCTXgLQkjVr1uTrX/96Vq1alaZpsm7duq5Hfrlpmt/b0h9uNmBJUkr5epIlXe3qqaZOnZo5c+Zk9uzZmT17dubMmZMpU6b0ajwAT/L4449n9erVGRoaytDQUO6///6sXr26rSfKb42NSY5omuZbW/qC8U/zl1+dZFWSaW3vanPWrl2btWvX5ic/+UkvxgEwsr3r6eKV/PanEH/tiafUvyLDFQSAXvlCkque6Yu2GLAkaZrmtiQ3tLUjAHgGdyf595v71OFTPW3AnrA0Seu3VAPAUzyQ5PQnHqzxjLb4IY4nK6VMTXJnksU7tjcA2KzHk5zaNM1W3wm9Na/A0jTN2iSnZ/hxHgDQtsu2JV7JVgYsSZqm+UmSI5O0+nhhAMa0RzP8ntfN2/oXtzpgSdI0zU+TnJDkY9s6CACe4v8mOa5pmu16cMZWvQe2OaWUq5MsyzZGEACSfC3JmU3T/Hx7F9jugCVJKeUPktyaZKftXgSAsebDSS5qmmb9jiyyQwFLklLKYRk+p+WIuWVaDgAAAOVJREFUHVoIgNHuwSRvaZqmlbMndzhgm5RS/jDJ25Mc0MqCAIwW65PcnGRZ0zRDbS3aWsCSXx/F8pok1yeZ19rCANRoY4bfZrquaZp/aXvxVgO2SSllepIrk1yRHj0MGIAR5YtJrmqa5jtdDegkYJuUUnZO8rwkZyR5fpKZnQ0DoJ82JPl6kv+Z5DNN0/xT1wM7DdiTPXF58XczHLPTk+zTk8EAdGVdkr9J8ukMR+u+Xg7vWcCeqpRyeIY/8LHHEz/2fOLHpl+79AjQX48nuS/JvRl+lOBPn/Tznyf5StM0D/drc/8fnOy+1L/CbXwAAAAASUVORK5CYII=');
                                } else if (response.data.message == -1) {
                                    alert('عکسی وارد نکرده اید');
                                } else {
                                    alert('خطایی موجود است');
                                }
                            });
                        };

                        $scope.editMode = false;
                        $scope.textBtnEdit = 'ویرایش';
                        $scope.disablepassdemo = true;
                        $scope.submitEdit = function () {
                            var data = {
                                'name': $scope.name,
                                'family': $scope.family,
                                'email': $scope.email,
                                'phone': $scope.phone,
                                'address': $scope.address,
                                'password': $scope.password,
                                'photo': $scope.imageInfo,
                                'mobile': $scope.mobile
                            };

                            $http({
                                method: 'POST',
                                url: baseURL + '/cli_panel/users/infos.json',
                                data: data
                            });
                        };
                        $scope.submitChangePassword = function () {
                            var data2 = {
                                'password': $scope.password                                     //'username': $scope.username
                            };
                            $http({
                                method: 'POST',
                                url: baseURL + '/cli_panel/users/passwords.json',
                                data: data2
                            });

                        };
                        $scope.ClickEditForm = function () {
                            $scope.editMode = !$scope.editMode;

                            if ($scope.editMode)
                            {
                                $scope.textBtnEdit = 'خروج از ویرایش';
                            } else
                            {
                                $scope.textBtnEdit = 'ویرایش';
                            }
                        };


                    }
                }
            }
        ])
        //match password inside panel user
        .directive('pwCheck', [function () {
                return {
                    require: 'ngModel',
                    link: function (scope, elem, attrs, ctrl) {
                        var firstPassword = '#' + attrs.pwCheck;
                        elem.add(firstPassword).on('keyup', function () {
                            scope.$apply(function () {
                                var v = elem.val() === $(firstPassword).val();
                                ctrl.$setValidity('pwmatch', v);
                            });
                        });
                    }
                }
            }
        ])
        //change value model
        .directive('testChange', function () {
            return function (scope, element, attrs) {
                element.bind('change', function () {
                    console.log('value changed');
                });
            };
        }
        )

        //animals directive control
        .directive('myAnimalsDirective', function () {
            return {
                restrict: 'E', //E = element, A = attribute, C = class, M = comment   
                templateUrl: '../bundles/app/partials/animals/AnimalsInformation.html',
                scope: false,
                controller: function ($scope, $http) {
                    $scope.animalsSelectedForGallaryPhoto = 0;
                    $http.get(baseURL + "/cli_panel/animals/user.json").success(function (response) {
                        $scope.animalsUser = response;
                    });
                    //click show gallery
                    $scope.galleryShow = function (animals) {

                        $http.get(baseURL + "/cli_panel/pictures/" + animals + "/animals.json").success(function (response) {
                            $scope.listImageGallery = response;
                            $("#galleryFull").fadeIn(100);
                            $scope.animalsSelectedForGallaryPhoto = animals;
                            $('#ShowImageProfile').attr('src', '');
                        });
                    };
                    $scope.doModalAnimalsEdit = function (index, id, photo, name, age, stature, weight, micro_chip, idAnimalsType, sex) {
                        //$scope.showModal = true;
                        $scope.SelectedAnimalsTypeCategory = idAnimalsType;
                        $scope.animalID = id;
                        //$scope.photoEdit = photo;
                        $scope.AnimalNameEdit = name;
                        $scope.AnimalAgeEdit = age;
                        $scope.AnimalStatureEdit = stature;
                        $scope.AnimalMicroChipEdit = micro_chip;
                        $scope.AnimalweightEdit = weight;
                        $scope.AnimalSex = sex;
                        $scope.selectCategoryAnimalsEdit = idAnimalsType;
                        $scope.indexOfCurrentAnimalsEdit = index;

                        $http.get(baseURL + "/cli_panel/animals/category.json").success(function (response) {
                            $scope.SelectCategory = response;
                            $("#ModalEditAnimals").modal();


                        });

                        $http.get(baseURL + "/cli_panel/pictures/" + id + "/animals.json").success(function (response) {
                            $scope.galleryImgEdit = response;
                        });

                    };
                    $scope.selectedImageGalleryEdit = '';
                    $scope.setDefualtImage = function ($event, idPhoto, photo) {

                        $http({
                            method: 'PUT',
                            url: baseURL + '/cli_panel/animals/' + idPhoto + '/defaults/' + $scope.animalID + '/picture.json',
                        }).then(function (response) {
                            alert("عکس پیشفرض انتخاب شد");
                            //animalsUser.defaultPhoto[index]= photo
                            $scope.animalsUser.defaultPhoto[$scope.indexOfCurrentAnimalsEdit].photo = photo;

                        });

                        var elem = $event.target;
                        $('#galleryEditImage .img-thumbnail').removeClass('selectedThumtional');
                        $(elem).addClass('selectedThumtional');

                    };
                    $scope.editAnimals = function () {
                        //$scope.showModal = false;

                        var data = {
                            'name': $scope.AnimalNameEdit,
                            'age': $scope.AnimalAgeEdit,
                            'sex': $scope.AnimalSex,
                            'weight': $scope.AnimalweightEdit,
                            'stature': $scope.AnimalStatureEdit,
//                            'microChip': $scope.AnimalMicroChipEdit,
                            'Animalscategory': $scope.selectCategoryAnimalsEdit,
                            'photo': $scope.photoEdit,
                            'id': $scope.animalID
                        };

                        $http({
                            method: 'PUT',
                            url: baseURL + '/cli_panel/animals/' + $scope.animalID + '/edit/info.json',
                            data: data
                        }).then(function (response) {
                            alert('بدرستی ویرایش شد');
                            $("#ModalEditAnimals").modal('hide');
//                            $http.get(baseURL + "/cli_panel/animals/user.json").success(function (response) {
//                                $scope.animalsUser = response;
//                            });

                        });

                    };
                    $scope.addPhoto = function (idAnimals) {
                        //show form edit photo gallery 
                        $('#AddPhotoAnimals').modal('show');


                    };
                    $scope.AddNewImageToGallery = function () {
                        //
                        //$scope.photoEdit
                        var data = {
                            'photo_default': 0,
                            'photo': $scope.photoEdit,
                            'animals': $scope.animalsSelectedForGallaryPhoto
                        };
                        //cli_panel/photos/animals.{_format}
                        $http({
                            method: 'POST',
                            url: baseURL + '/cli_panel/photos/animals.json',
                            data: data
                        }).then(function (response) {
                            if (response.data.message == 0)
                            {
                                $scope.galleryShow($scope.animalsSelectedForGallaryPhoto);

                            }
                        });


                    };
                    $scope.removePhoto = function (idPicture) {
                        //cli_panel/photos/{id}/animals

                        $http({
                            method: 'DELETE',
                            url: baseURL + '/cli_panel/photos/' + idPicture + '/animals.json'
                        }).then(function successCallback(response) {
                            //refresh list after delete
                            $scope.galleryShow($scope.animalsSelectedForGallaryPhoto);
                            //$(".ajaxloader").fadeOut(500);
                        }, function errorCallback(response) {
                            alert('خطایی موجود است');
                            //$(".ajaxloader").fadeOut(500);
                        });
                    };

                }
            }
        })
        .directive('reserveTimes', function () {
            return {
                restrict: 'E', //E = element, A = attribute, C = class, M = comment   
                templateUrl: '../bundles/app/partials/reservetime/reserveinformation.html',
                $scope: false,
                controller: function ($scope, $http) {
                    //cli_panel/doctor/list/by/expertise.{_format}
                    $http.get(baseURL + "/cli_panel/doctor/list/by/expertise.json").success(function (response) {
                        $scope.InfoDoctorListCertificationDoctor = response;
                        
                        //console.log($scope.InfoDoctorListCertificationDoctor);
                    });

                    $scope.currentWeek = 0;
                    $scope.CurrentDoctorID = 0;
                    $scope.AllWeek = 0;
                    $scope.SeeTimes = false;
                    $scope.selectedDoctorName = '';

                    $scope.ShowHiddenReserveForm = false;

                    //check for nobat dehi ya nobat nadahi
                    $scope.checkValueZero = function (x) {

                        if (parseInt(x) === 0) {
                            return false;
                        }
                        else {
                            return true;
                        }

                    };

                    $scope.ShowScheduling = function (idDoctor, doctorName, DoctorFamily) {
                        $scope.CurrentDoctorID = idDoctor;
                        $scope.selectedDoctorName = "دکتر " + doctorName + " " + DoctorFamily;
                        $scope.SeeTimes = true;
                        $http.get(baseURL + "/cli_panel/doctors/" + $scope.CurrentDoctorID + "/times/" + $scope.currentWeek + "/reserved.json").success(function (response) {
                            $scope.TableTimeReserved = response;
                            $scope.idTimeDoctor = response.idTimeDoctor;
                            $scope.currentWeek = parseInt(response.CurrentWeek);
                            $scope.AllWeek = parseInt(response.CountWeek);
                            var dateOne = new Date(response.StartedWeek);
                            $scope.one = [];
                            $scope.two = [];
                            $scope.three = [];
                            $scope.four = [];
                            $scope.five = [];
                            $scope.six = [];
                            $scope.seven = [];

                            $scope.one[0] = moment(dateOne).format('jYYYY/jM/jD');
                            $scope.one[1] = moment(dateOne);

                            $scope.two[0] = moment(dateOne).add(1, 'days').format('jYYYY/jM/jD');
                            $scope.two[1] = moment(dateOne).add(1, 'days');

                            $scope.three[0] = moment(dateOne).add(2, 'days').format('jYYYY/jM/jD');
                            $scope.three[1] = moment(dateOne).add(2, 'days');

                            $scope.four[0] = moment(dateOne).add(3, 'days').format('jYYYY/jM/jD');
                            $scope.four[1] = moment(dateOne).add(3, 'days');//.format('jYYYY/jM/jD');

                            $scope.five[0] = moment(dateOne).add(4, 'days').format('jYYYY/jM/jD');
                            $scope.five[1] = moment(dateOne).add(4, 'days');

                            $scope.six[0] = moment(dateOne).add(5, 'days').format('jYYYY/jM/jD');
                            $scope.six[1] = moment(dateOne).add(5, 'days');//.format('jYYYY/jM/jD');

                            $scope.seven[0] = moment(dateOne).add(6, 'days').format('jYYYY/jM/jD');
                            $scope.seven[1] = moment(dateOne).add(6, 'days');//.format('jYYYY/jM/jD');

                        });
                    }

                    $scope.nextWeek = function () {
                        if ($scope.AllWeek > $scope.currentWeek) {
                            $scope.currentWeek++;
                        }
                        $scope.ShowScheduling($scope.CurrentDoctorID);
                        //console.log($scope.currentWeek);
                    };

                    $scope.prevWeek = function () {
                        if ($scope.currentWeek > 0)
                        {
                            $scope.currentWeek--;
                        }
                        $scope.ShowScheduling($scope.CurrentDoctorID);
                        //console.log($scope.currentWeek);
                    };
                    $scope.selectDateReserve = '';
                    $scope.selectShamsiDateReserve = '';
                    $scope.ShowFormReserve = function (index) {
                        $scope.ShowHiddenReserveForm = true;

                        switch (index) {
                            case 0:
                                {
                                    $scope.selectDateReserve = $scope.one[1];
                                    $scope.selectShamsiDateReserve = $scope.one[0];
                                }
                                ;
                                break;
                            case 1:
                                {
                                    $scope.selectDateReserve = $scope.two[1];
                                    $scope.selectShamsiDateReserve = $scope.two[0];
                                }
                                ;
                                break;
                            case 2:
                                {
                                    $scope.selectDateReserve = $scope.three[1];
                                    $scope.selectShamsiDateReserve = $scope.three[0];
                                }
                                ;
                                break;
                            case 3:
                                {
                                    $scope.selectDateReserve = $scope.four[1];
                                    $scope.selectShamsiDateReserve = $scope.four[0];
                                }
                                ;
                                break;
                            case 4:
                                {
                                    $scope.selectDateReserve = $scope.five[1];
                                    $scope.selectShamsiDateReserve = $scope.five[0];
                                }
                                ;
                                break;
                            case 5:
                                {
                                    $scope.selectDateReserve = $scope.six[1];
                                    $scope.selectShamsiDateReserve = $scope.six[0];
                                }
                                ;
                                break;
                            case 6:
                                {
                                    $scope.selectDateReserve = $scope.seven[1];
                                    $scope.selectShamsiDateReserve = $scope.seven[0];
                                }
                                ;
                                break;
                        }

                        $scope.dateSet = $scope.selectDateReserve;

                        $http.get(baseURL + "/cli_panel/animals/user.json").success(function (response) {
                            $scope.myAnimals = response;
                        });

                    };

                    //hold current animal select for reserve time
                    $scope.CurrentAnimalsSelect = 0;

                    $scope.SelectAnimals = function ($event, idAnimals) {
                        $scope.CurrentAnimalsSelect = idAnimals;

                        var elem = $event.target;
                        $('#divAnimals .img-thumbnail').removeClass('selectedThumtional');
                        $(elem).addClass('selectedThumtional');
                    };

                    //cli_panel/tickets/inserts
                    $scope.UserTickets = [];
                    $scope.insertClick = function () {

                        var data = {
                            'desc': $scope.desc,
                            'AnimalsSelect': $scope.CurrentAnimalsSelect,
                            'idTimeDoctor': $scope.idTimeDoctor,
                            'date': $scope.dateSet
                        };

                        $http({
                            method: 'POST',
                            url: baseURL + '/cli_panel/tickets/inserts.json',
                            data: data
                        }).then(function (response) {
                            if (response.data.message == "Ticket inserted") {
                                alert('شما در سیستم نوبت دهی شدید');
                                $scope.UserTickets.push(response.data.request);
                                $scope.ShowHiddenReserveForm = false;
                                $scope.SeeTimes = false;
                                $scope.desc = '';
                            }
                            ;
                        });
                    };

                    //cli_panel/ticket/user.json
                    $http.get(baseURL + "/cli_panel/ticket/user.json").success(function (response) {
                        $scope.UserTickets = response;
                    });

                    $scope.currentTicket;
                    $scope.UserTicketsIndexRow;
                    $scope.currentNumber;
                    $scope.DoModalDeleteTicket = function (index, num, idTicket) {
                        $('#ModalDeleteTicket').modal('show');
                        $scope.currentTicket = idTicket;
                        $scope.UserTicketsIndexRow = index;
                        $scope.currentNumber = num;
                    };
                    $scope.deleteTicket = function () {
                        ///cli_panel/tickets/{id}/user
                        $http({
                            method: 'DELETE',
                            url: baseURL + '/cli_panel/tickets/' + $scope.currentTicket + '/user.json',
                        }).then(function (response) {
                            if (response.data.deleteMessage == 0)
                            {
                                $scope.UserTickets.splice($scope.UserTicketsIndexRow, 1);
                                $('#ModalDeleteTicket').modal('hide');
                            }
                        });
                    };
                }
            };
        })
        // savabegh-info ----------------------------------------------------------------
        .directive('savabeghInfo', function () {
            return {
                restrict: 'E', //E = element, A = attribute, C = class, M = comment   
                templateUrl: '../bundles/app/partials/savabegh/savabeghinformation.html',
                $scope: false,
                controller: function ($scope, $http) {

                }
            }
        })

        //chipCode
        // -------------------------------------------------------------------------------
        .directive('microChip', function () {
            return {
                restrict: 'E', //E = element, A = attribute, C = class, M = comment   
                templateUrl: '../bundles/app/partials/microchip/microchipinformation.html',
                scope: false,
                controller: function ($scope, $http) {
                    $http.get(baseURL + "/cli_panel/animals/category.json").success(function (response) {
                        $scope.SelectCategoryAddAnimal = response;
                    });
                    $scope.AnimalMicroChip;
                    $scope.InsertAnimal = function () {

                        var data = {
                            'name': $scope.AnimalNameAdd,
                            'age': $scope.AnimalAgeAdd,
                            'sex': $scope.AnimalSexAdd,
                            'weight': $scope.AnimalweightAdd,
                            'stature': $scope.AnimalStatureAdd,
                            'microChip': $scope.AnimalMicroChip,
                            'Animalscategory': $scope.SelectCatAnimalAdd,
                            'photo': $scope.photoAddAnimal
                        };

                        $http({
                            method: 'POST',
                            url: baseURL + '/cli_panel/animals/inserts/infos.json',
                            data: data
                        }).then(function (response) {
                            if (response.data.message === '0')
                            {
                                $scope.AnimalNameAdd = ' ';
                                $scope.AnimalAgeAdd = ' ';
                                $scope.AnimalSexAdd = ' ';
                                $scope.AnimalweightAdd = ' ';
                                $scope.AnimalStatureAdd = ' ';
                                $scope.AnimalMicroChip = ' ';
                                $scope.SelectCatAnimalAdd = ' ';
                                $scope.photoAddAnimal = ' ';
                                $('#ShowImageAddAnimal').attr('src', 'data:image/png;base64,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');


                                alert('این حیوان ثبت شد');

                            } else if (
                                    response.data.message === '-1') {
                                alert('عکسی وارد نکرده اید');
                            } else {
                                alert('خطایی موجود است');
                            }
                        });
                    };

                }
            }
        })

        .directive('messageUsers', function () {
            return {
                restrict: 'E', //E = element, A = attribute, C = class, M = comment   
                templateUrl: '../bundles/app/partials/messages/messagesInformation.html',
                scope: false,
                controller: function ($scope, $http) {
                    /*
                     * SingleUser = 0 
                     * MultiUser = 1
                     * SindleDoctor = 2 
                     * MultiDoctor = 3
                     */
                    $scope.messageUser = [];
                    $scope.currentMessage = [];
                    $scope.currentMessage['message'] = '';
                    var dateCurrent = new Date();
                    $scope.currentMessage['d'] = moment(dateCurrent);
                    $scope.currentMessage['sendeduser'] = '';
                    $scope.iconGLY = 'glyphicon-envelope';

                    $http.get(baseURL + "/cli_panel/messages/user.json").success(function (response) {
                        $scope.messageUser = response;
                    });

                    $scope.ShowMessage = function (index, idMessage) {

                        $scope.currentMessage.massage = $scope.messageUser[index].message;
                        $scope.messageUser[index].is_read = true;
                        $scope.currentMessage.sendeduser = $scope.messageUser[index].user_name_message_owner;
                        $scope.currentMessage.d = $scope.messageUser[index].create_at;
                        $("#ModalShowMessage").modal('show');

                        $http({
                            method: 'PUT',
                            url: baseURL + '/cli_panel/sets/' + idMessage + '/readed/message.json',
                        }).then(function (response) {
                            if (response.data.message === '0') {
                                console.log('isReaded');
                            }
                        });

                    };

                    $scope.SendMessageForAdmin = function () {
                        //cli_panel/sends/messagetoadmins
                        var data = {
                            'message': $scope.messageInsert
                        };

                        $http({
                            method: 'POST',
                            url: baseURL + '/cli_panel/sends/messagetoadmins.json',
                            data: data
                        }).then(function (response) {
                            if (response.data.message === '0')
                            {
                                $scope.messageInsert = '';
                            } else if (response.data.message === '-1') {
                                alert('Errror');
                            }
                        });
                    };

                }
            }
        })

        .directive('userShopInformation', function ($cookieStore) {

            return {
                restrict: 'E', //E = element, A = attribute, C = class, M = comment   
                templateUrl: '../bundles/app/partials/shop/shopInformation.html',
                scope: false,
                controller: function ($scope, $http,$cookies) {
                    $scope.mony = '';
                    $scope.factorsUser = [];
                    function splitMony(ctrl) {
                        var separator = ",";
                        var int = ctrl.replace(new RegExp(separator, "g"), "");
                        var regexp = new RegExp("\\B(\\d{3})(" + separator + "|$)");
                        do
                        {
                            int = int.replace(regexp, separator + "$1");
                        }
                        while (int.search(regexp) >= 0)
                        return int;
                    }
                    $http.get(baseURL + "/cli_panel/user/mojodi/hesab.json").success(function (response) {
                        var temp = String(response.monyUser);
                        $scope.mojodiHesab = splitMony(temp);
                    });

                    $http.get(baseURL + "/cli_panel/factor/user.json").success(function (response) {
                        $scope.factorsUser = response.factor;
                    });

                    //delete factor
                    $scope.currentIdFactor;
                    $scope.currentIndexFactor;
                    $scope.ShowdeleteFactorModal = function (idFactor, index) {
                        //cli_panel/factors/{idFactor}/remove.json
                        $scope.currentIdFactor = idFactor;
                        $scope.currentIndexFactor = index;
                        $("#ModalDeleteFactor").modal("show");
                    };
                    $scope.deleteOrCancelFactor = function () {
                        //cli_panel/factors/{idFactor}/remove.{_format}
                        $http({
                            method: 'DELETE',
                            url: baseURL + "/cli_panel/factors/" + $scope.currentIdFactor + "/remove.json",
                        }).then(function (response) {
                            if (response.data.deleteMessage == 0)
                            {
                                $scope.factorsUser.splice($scope.currentIndexFactor, 1);
                                $('#ModalDeleteFactor').modal('hide');
                            }
                        });
                    };
                    $scope.ShowFactorProduct = function (idFactor) {
                        $scope.currentIdFactor = idFactor;
                        //cli_panel/baskets/{idFactor}/factor
                        $http.get(baseURL + "/cli_panel/baskets/" + idFactor + "/factor.json").success(function (response) {
                            $scope.Baskets = response.basket;
                            $("#ShowProductFactorModal").modal("show");
                        });
                    };

                    $scope.reSharjAccount = function () {
                        //https://pgws.bpm.bankmellat.ir/pgwchannel/startpay.mellat
                        //model for money for payment


                        $http({
                            method: 'POST',
                            url: baseURL + '/cli_panel/payments/' + $scope.mony + '/mellats.json',
                        }).then(function (response) {
                            $scope.ErrorBank = 0;
                            if (response.data.error == '1')
                            {
                                $scope.ErrorBank = 1;
                                $scope.ErrorBankMessage = response.data.message;
                            } else
                            {
                                $scope.formRefId = response.data.formRefId;
                            }

                            console.log(response);
                        });


                    };

                    $scope.CloseMessageError = function () {
                        $scope.ErrorBank = false;
                    };

                    //=============================== Payment ==================================

                   
                    $scope.taxation = 0.09;
                    $scope.discount = 0.1;
                    $scope.other = 10000;
                    
                    $scope.message;
                    $scope.ref;
                    $scope.isLoading = false;
                    $scope.payment_success = false;
                    $scope.payment_error;
                 
                    $scope.items = [];
                    if ($cookies['basket']) {
                        $scope.items = JSON.parse($cookies['basket']);
                        console.log(JSON.parse($cookies['basket']))
                    }
                    $scope.total = function () {
                        var total = 0;
                        angular.forEach($scope.items, function (item) {
                            total += item.nu * item.p;
                        });
                        return total;
                    };
                    $scope.totalFinal = function () {
                        return $scope.total() * (1 + $scope.taxation - $scope.discount) + $scope.other;
                    };
                    $scope.payment = function () {
                        $scope.isLoading = true;
                        var data = [];
                        if ($cookieStore.get('basket')) {
                            data = JSON.parse($cookieStore.get('basket'));
                            $http({
                                method: 'post',
                                url: BaseUrl + 'payment',
                                data: data
                            }).success(function (response) {
                                $scope.payment_success = true;
                                if (response.error) {
                                    $scope.message = response.message;
                                    $scope.payment_error = true;
                                } else if (response.success) {
                                    $scope.message = 'خرید موفق';
                                    $scope.user.money = response.total;
                                    $scope.ref = response.ref;
                                    $scope.payment_error = false;
                                    $cookieStore.remove('basket');
                                }
                            });
                        }
                    };
                }
            };

        });