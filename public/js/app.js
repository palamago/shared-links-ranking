var NewsApp = angular.module('news-ranking-project', ['ngRoute','restangular','angulike']);

NewsApp.config(function($routeProvider) {
  $routeProvider
    .when('/', {
      controller:'TopCtrl',
      templateUrl:'/partials/top.html',
      reloadOnSearch: false
    })
    .when('/history', {
      controller:'HistoryCtrl',
      templateUrl:'/partials/history.html'
    })
    .when('/link/:id', {
      controller:'LinkCtrl',
      templateUrl:'/partials/link.html'
    })
    .otherwise({redirectTo:'/'});

});

NewsApp.run(function($rootScope, Restangular){

  Restangular.setBaseUrl('/api');

  $rootScope.getDateDiff = function(date){
    var diff = moment().diff(moment(date));
    diff = Math.floor(moment.duration(diff).asHours());
    //diff = (diff==0)?'menos de una hora':(diff==1)?'una hora':diff+' horas';
    diff = (diff==0)?'1h':(diff==1)?'1h':diff+'h';
    return diff;
  }

  $rootScope.shareText = "Shared Links Ranking";

});

