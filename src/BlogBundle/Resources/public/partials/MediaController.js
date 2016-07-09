'us strict';

app.controller('Media', ['$rootScope','$scope', '$window', '$cookies', 'Hello', 'Salt','$http', function($rootScope, $scope, $window, $cookies, Hello, Salt , $http) {

     $http.get( BaseUrlAdmin+"gallerys.json").success(function(response) {
    
                $scope.content = response;
        
    });
              $scope.contentCategoryEdit = function() {
          var id = $('#EditMediaId1').val();

            $http({
              method: 'PUT',
              url:  BaseUrlAdmin+'galleries/' + id +'.json',
              data: { title: $scope.title, content:  $scope.content,orderList:$scope.orderList}
            }).then(function successCallback(response) {
                alert('Ok');
              }, function errorCallback(response) {
                alert('Nok');
              });
      };
    
  }])//Media Add
  .controller('MediaAdd', ['$rootScope','$scope', '$window', '$cookies', 'Hello', 'Salt','$http', function($rootScope, $scope, $window, $cookies, Hello, Salt , $http) {

      $scope.SubmitAdd = function() {
            $http({
              method: 'POST',
              data:  { title: $scope.title, content:  $scope.slug},
              url:  BaseUrlAdmin+'galleries.json',
              headers: { 'Content-Type': 'application/json;charset=utf-8' }
            }).then(function successCallback(response) {
                alert('Ok');
              }, function errorCallback(response) {
                alert('Nok');
              });
      };
    
  }]) //MediaEdit
  .controller('MediaEdit', ['$rootScope','$scope', '$window', '$cookies', 'Hello', 'Salt','$http', function($rootScope, $scope, $window, $cookies, Hello, Salt , $http) {

      $scope.SubmitEdit = function() {
          var id = $('#EditId').val();
          // Simple GET request example:
            $http({
              method: 'PUT',
              data:{title:$scope.title,slug:$scope.slug},
              url:  BaseUrlAdmin+'galleries/' + id +'.json'
            }).then(function successCallback(response) {
                alert('Ok');
              }, function errorCallback(response) {
                alert('Nok');
              });
      };
    
  }]) //contentCategoryDelete
  .controller('GalleyDelete', ['$rootScope','$scope', '$window', '$cookies', 'Hello', 'Salt','$http', function($rootScope, $scope, $window, $cookies, Hello, Salt , $http) {

      $scope.SubmitDelete = function() {
          var id = $('#DeleteId').val();
          // Simple GET request example:
            $http({
              method: 'DELETE',
              url:  BaseUrlAdmin+'galleries/' + id +'.json'
            }).then(function successCallback(response) {
                alert('Deleted');
              }, function errorCallback(response) {
                alert('No Deleted');
              });
      };
    
  }])


