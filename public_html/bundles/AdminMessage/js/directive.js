'use strict';

app.directive('message', function() {
    var user = {};
    user.scope = false;
    user.restrict = 'E';
    user.templateUrl = '../../bundles/AdminMessage/partials/MessageInfo.html';
    return user;
});

app.directive('editmessage', function() {
    var user = {};
    user.scope = false;
    user.restrict = 'E';
    user.templateUrl = '../../bundles/AdminMessage/partials/Users/Edit.html';
    return user;
});

app.directive('modalMsg', function() {
    var user = {};
    user.scope = false;
    user.restrict = 'E';
    user.templateUrl = '../../bundles/AdminMessage/partials/Messages/Message.html';
    return user;
});

app.directive('addmessage', function() {
    var user = {};
    user.scope = false;
    user.restrict = 'E';
    user.templateUrl = '../../bundles/AdminMessage/partials/Messages/Add.html';
    return user;
});

app.directive('showmessage', function() {
    var user = {};
    user.scope = false;
    user.restrict = 'E';
    user.templateUrl = '../../bundles/AdminMessage/partials/Messages/show.html';
    return user;
});

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