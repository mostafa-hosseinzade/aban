'use strict';

angular.module('myApp', ['myApp.filters', 'myApp.services', 'myApp.directives', 'myApp.controllers', 'ngRoute', 'angular-loading-bar', 'ngAnimate']).
        config(['$routeProvider', function ($routeProvider) {
                $routeProvider.when('/profile', {templateUrl: BaseUrl + 'bundles/blog/partials/admin/profile.html', controller: 'profile'});
                $routeProvider.when('/', {templateUrl: BaseUrl + 'bundles/blog/partials/admin/index.html', controller: 'index'});
                $routeProvider.when('/:entity', {templateUrl: BaseUrl + 'bundles/blog/partials/admin/Images/Images.html', controller: 'fullController'});
                $routeProvider.when('/:entity/page/:current', {templateUrl: BaseUrl + 'bundles/blog/partials/admin/Images/Images.html', controller: 'fullController'});
                $routeProvider.when('/:entity/filter/:filter', {templateUrl: BaseUrl + 'bundles/blog/partials/admin/Images/Images.html', controller: 'fullController'});
                $routeProvider.when('/:entity/filter/:filter/page/:current', {templateUrl: BaseUrl + 'bundles/blog/partials/admin/Images/Images.html', controller: 'fullController'});
                $routeProvider.when('/:entity/order/:attr/:asc', {templateUrl: BaseUrl + 'bundles/blog/partials/admin/Images/Images.html', controller: 'fullController'});
                $routeProvider.when('/:entity/filter/:filter/order/:attr/:asc', {templateUrl: BaseUrl + 'bundles/blog/partials/admin/Images/Images.html', controller: 'fullController'});
                $routeProvider.when('/:entity/filter/:filter/order/:attr/:asc/page/:current', {templateUrl: BaseUrl + 'bundles/blog/partials/admin/Images/Images.html', controller: 'fullController'});
                $routeProvider.when('/:entity/order/:attr/:asc/page/:current', {templateUrl: BaseUrl + 'bundles/blog/partials/admin/Images/Images.html', controller: 'fullController'});

                $routeProvider.when('/', {templateUrl: BaseUrl + 'bundles/blog/partials/admin/index.html', controller: 'index'});
                $routeProvider.otherwise({redirectTo: BaseUrl});

            }]).directive('testChange', function () {
    return function (scope, element, attrs) {
        element.bind('change', function () {
            console.log('value changed');
        });
    };
}).filter('nl2br', function () {
    return function (text) {
        return text ? text.replace(/\n/g, '<br/>') : '';
    };
})
        ;
angular.module('ng').filter('cut', function () {
    return function (value, wordwise, max, tail) {
        if (!value)
            return '';

        max = parseInt(max, 10);
        if (!max)
            return value;
        if (value.length <= max)
            return value;

        value = value.substr(0, max);
        if (wordwise) {
            var lastspace = value.lastIndexOf(' ');
            if (lastspace != -1) {
                value = value.substr(0, lastspace);
            }
        }
        return value + (tail || ' …');
    };
});

//darash Comment
//This For set text checkbox
angular.module('ng').filter('boolean', function () {
    return function (value) {
        if (value) {
            return 'فعال';
        } else {
            return 'غیر فعال';
        }
    };
});

//darash Comment  
// This Filter For Remove Tag HTML  
angular.module('ng').filter('htmlToPlaintext', function () {
    return function (text) {
        return  text ? String(text).replace(/<[^>]+>/gm, '') : '';
    };
}
)
        .filter('Rial', function () {
            return function (dateString) {
                return dateString + 'ریال';
            };
        })
        .filter('ConvertedToDateShamsi', function () {
            return function (dateString) {
                if (dateString != null)
                {
                    return moment(dateString, 'YYYY-M-D HH:mm:ss').format('jYYYY/jM/jD');
                } else {
                    return "تاریخ ندارد";
                }
            };
        });