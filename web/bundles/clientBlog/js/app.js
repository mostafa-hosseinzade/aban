'use strict';


// Declare app level module which depends on filters, and services
angular.module('myApp', ['myApp.filters', 'myApp.directives', 'myApp.controllers', 'infinite-scroll']).
        config(['$routeProvider', function ($routeProvider) {
                //Gallery Info
                $routeProvider.when('/Gallery', {templateUrl: 'bundles/clientBlog/partials/client/Gallery/Gallery.html', controller: 'gallery'});
                $routeProvider.when('/Gallery/:id', {templateUrl: 'bundles/clientBlog/partials/client/Gallery/Show.html', controller: 'gallery'});
                //Page Info
                $routeProvider.when('/Page/:id', {templateUrl: 'bundles/clientBlog/partials/client/Page/Page.html', controller: 'Page'});
                $routeProvider.when('/Content/:id', {templateUrl: 'bundles/clientBlog/partials/client/Content/Content.html', controller: 'Content'});
                $routeProvider.when('/Content/Show/:id', {templateUrl: 'bundles/clientBlog/partials/client/Content/Show.html', controller: 'ContentShow'});
                //Doctors Info
                $routeProvider.when('/Doctors', {templateUrl: 'bundles/clientBlog/partials/client/Doctors/Doctors.html', controller: 'Doctors'});
                $routeProvider.when('/Doctors/Show/:id', {templateUrl: 'bundles/clientBlog/partials/client/Doctors/Show.html', controller: 'DoctorsShow'});
                $routeProvider.when('/Doctors/ShowReserve/:id', {templateUrl: BaseUrl + 'expertise/ReserveTemplate', controller: 'DoctorsReserve'});
                $routeProvider.when('/MicroChip',{templateUrl: BaseUrl+"bundles/clientBlog/partials/client/MicroChip/index.html",controller:'MicroChip'    })
                $routeProvider.otherwise({redirectTo: BaseUrl});
            }]).directive('testChange', function () {
    return function (scope, element, attrs) {
        element.bind('change', function () {
        });
    };

}).filter('nl2br', function () {
    return function (text) {
        return text ? text.replace(/\n/g, '<br/>') : '';
    };
}).directive('loading', ['$http', function ($http)
    {
        return {
            restrict: 'A',
            link: function (scope, elm, attrs)
            {
                $(elm).find('img').attr('src', BaseUrl + 'bundles/public/img/loading.gif');
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
    }]);

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
// This Filter For Remove Tag HTML  
angular.module('ng').filter('htmlToPlaintext', function () {
    return function (text) {
        return  text ? String(text).replace(/<[^>]+>/gm, '') : '';
    };
});

angular.module('myApp.filters', []).
        filter('interpolate', ['version', function (version) {
                return function (text) {
                    return String(text).replace(/\%VERSION\%/mg, version);
                }
            }]);

// count the elements in an object
angular.module('myApp.filters', []).filter('lengthOfObject', function() {
  return function( obj ) {
    var size = 0, key;
    for (key in obj) {
      if (obj.hasOwnProperty(key)) size++;
    }
   return size;
 }
})

angular.module('ng').filter('ConvertedToDateShamsi', function () {
    return function (dateString) {
        if (dateString != null)
        {
            return moment(dateString, 'YYYY-M-D HH:mm:ss').format('jYYYY/jM/jD HH:mm:ss');
        }
        else {
            return "تاریخ ندارد"
        }
    };
}
);