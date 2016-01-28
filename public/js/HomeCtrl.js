NewsApp.controller('HomeCtrl', function($scope, Restangular, $http, $location,$timeout) {

  $scope.loading = true;
  $scope.newspapers = {};
  $scope.rankings = {};

  $scope.getRanking = function(group){
    Restangular.all('topnews').getList({limit:3,group:group}).then(function(data){
      $scope.rankings[group] = Restangular.stripRestangular(data);
    });
  };

  $scope.init = function(){
    Restangular.all('newspaper').getList().then(function(data){
      $scope.newspapers = _.groupBy(Restangular.stripRestangular(data), 'id_group');
      $timeout(function() {
        $('.img-newspaper').tooltip();
      }, 500);
    });
  };

  $scope.init();

});