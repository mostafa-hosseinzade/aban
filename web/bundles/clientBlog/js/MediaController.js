'us strict';

app//Media
  .controller('Media', ['$rootScope','$scope', '$window', '$cookies', 'Hello', 'Salt','$http', function($rootScope, $scope, $window, $cookies, Hello, Salt , $http) {
    // If auth information in cookie
//    if ( typeof $cookies.username != "undefined" && typeof $cookies.secret != "undefined" ) {
//        $rootScope.userAuth = {username: $cookies.username, secret : $cookies.secret};
//    }
//    
//    // If not authenticated, go to login
//    if ( typeof $rootScope.userAuth == "undefined" ) {
//        $window.location = '#/login';
//        return;
//    }
     $http.get(BaseUrl+"gallerys.json").success(function(response) {
    
                $scope.content = response;
        
    });
              $scope.contentCategoryEdit = function() {
          var id = $('#EditMediaId1').val();

            $http({
              method: 'PUT',
              url: BaseUrl+'galleries/' + id +'.json',
              data: { title: $scope.title, content:  $scope.content,orderList:$scope.orderList}
            }).then(function successCallback(response) {
                alert('Ok');
              }, function errorCallback(response) {
                alert('Nok');
              });
      };
    
  }])//Media Add
  .controller('MediaAdd', ['$rootScope','$scope', '$window', '$cookies', 'Hello', 'Salt','$http', function($rootScope, $scope, $window, $cookies, Hello, Salt , $http) {
    // If auth information in cookie
//    if ( typeof $cookies.username != "undefined" && typeof $cookies.secret != "undefined" ) {
//        $rootScope.userAuth = {username: $cookies.username, secret : $cookies.secret};
//    }
//    
//    // If not authenticated, go to login
//    if ( typeof $rootScope.userAuth == "undefined" ) {
//        $window.location = '#/login';
//        return;
//    }
      $scope.SubmitAdd = function() {
            $http({
              method: 'POST',
              data:  { title: $scope.title, content:  $scope.slug},
              url: BaseUrl+'galleries.json',
              headers: { 'Content-Type': 'application/json;charset=utf-8' }
            }).then(function successCallback(response) {
                alert('Ok');
              }, function errorCallback(response) {
                alert('Nok');
              });
      };
    
  }]) //MediaEdit
  .controller('MediaEdit', ['$rootScope','$scope', '$window', '$cookies', 'Hello', 'Salt','$http', function($rootScope, $scope, $window, $cookies, Hello, Salt , $http) {
    // If auth information in cookie
//    if ( typeof $cookies.username != "undefined" && typeof $cookies.secret != "undefined" ) {
//        $rootScope.userAuth = {username: $cookies.username, secret : $cookies.secret};
//    }
//    
//    // If not authenticated, go to login
//    if ( typeof $rootScope.userAuth == "undefined" ) {
//        $window.location = '#/login';
//        return;
//    }
      $scope.SubmitEdit = function() {
          var id = $('#EditId').val();
          // Simple GET request example:
            $http({
              method: 'PUT',
              data:{title:$scope.title,slug:$scope.slug},
              url: BaseUrl+'galleries/' + id +'.json'
            }).then(function successCallback(response) {
                alert('Ok');
              }, function errorCallback(response) {
                alert('Nok');
              });
      };
    
  }]) //contentCategoryDelete
  .controller('GalleyDelete', ['$rootScope','$scope', '$window', '$cookies', 'Hello', 'Salt','$http', function($rootScope, $scope, $window, $cookies, Hello, Salt , $http) {
    // If auth information in cookie
//    if ( typeof $cookies.username != "undefined" && typeof $cookies.secret != "undefined" ) {
//        $rootScope.userAuth = {username: $cookies.username, secret : $cookies.secret};
//    }
//    
//    // If not authenticated, go to login
//    if ( typeof $rootScope.userAuth == "undefined" ) {
//        $window.location = '#/login';
//        return;
//    }
      $scope.SubmitDelete = function() {
          var id = $('#DeleteId').val();
          // Simple GET request example:
            $http({
              method: 'DELETE',
              url: BaseUrl+'galleries/' + id +'.json'
            }).then(function successCallback(response) {
                alert('Deleted');
              }, function errorCallback(response) {
                alert('No Deleted');
              });
      };
    
  }])


