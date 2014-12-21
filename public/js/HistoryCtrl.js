NewsApp.controller('HistoryCtrl', function($scope, Restangular, $http, $routeParams, $location) {

    $scope.loading = true;
    $scope.topnews = [];
    
  $scope.refresh = function(){
    Restangular.all('historynews').getList().then(function(data){
      $scope.loading = false;
      $scope.topnews = data;
    });
  };

  $scope.init = function(){
    $scope.refresh();
  };

  $scope.init();

});