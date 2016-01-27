var NewsApp = angular.module('news-ranking-home', ['restangular','angulike']);

NewsApp.run(function($rootScope, Restangular){

  Restangular.setBaseUrl('/api');

  $rootScope.shareText = "Shared Links Ranking";

});

