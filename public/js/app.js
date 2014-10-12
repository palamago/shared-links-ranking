var NewsApp = angular.module('news-ranking-project', ['ngRoute','restangular']);

NewsApp.config(function($routeProvider) {
  $routeProvider
    .when('/', {
      controller:'TopCtrl',
      templateUrl:'/partials/top.html',
      reloadOnSearch: false
    })

});

NewsApp.controller('TopCtrl', function($scope, Restangular, $http, $location) {

  $scope.titles = {
    newspaper: false,
    time: 'Últimas 24hs',
    tag:false,
    tagColor:''
  }

  $scope.filters = {
  	newspaper:'',
  	time:'today',
  	tag:''
  }

  $scope.times = [
    {id:'today',  label:'Hoy', title: 'Últimas 24hs'}
    ,{id:'3days', label:'3 días', title: 'de los últimos 3 días'}
    ,{id:'week',  label:'Semana', title: 'de la última semana'}
  ];
  
  Restangular.setBaseUrl('/api');

  $scope.newspapers   = [];
  $scope.tags     = [];
  $scope.topnews    = [];

  $scope.createTitle = function(){

    var temp;
    temp = _.where($scope.times, {id: $scope.filters.time})[0];
    $scope.titles.time = temp.title;

    if($scope.filters.newspaper!=''){
      temp = _.where($scope.newspapers, {id: $scope.filters.newspaper})[0];
      $scope.titles.newspaper = temp.name;
    } else {
      $scope.titles.newspaper = false;
    }

    if($scope.filters.tag!=''){
      temp = _.where($scope.tags, {id: $scope.filters.tag})[0];
      $scope.titles.tag = temp.name;
      $scope.titles.tagColor = temp.color;
    } else {
      $scope.titles.tag = false;
      $scope.titles.tagColor = '';
    }

  }

  $scope.filterClick = function(filter,value,justSet){
    $scope.filters[filter] = value;
    $location.search(filter,value);
    window.location = $location.absUrl();
    if(!justSet){
      $scope.refresh();
    }
    $("#nav-main").collapse('hide');
  }

  $scope.refresh = function(){
    $scope.createTitle();
  	Restangular.all('topnews').getList($scope.filters).then(function(data){
      $scope.topnews = data;
      $scope.refreshSparklines();
    });
  } 	

  $scope.sparklineData = {};

  $scope.refreshSparklines = function(){
    if($scope.topnews.length){
      var IDs = [];
      angular.forEach($scope.topnews,function(e,i){
        IDs.push(e.id);
      });

      $http.get('/api/sparklines/'+IDs.join(',')).success(function(data){
        $scope.sparklineData = data;
        $scope.renderSparklines();
      });
    }
  }

  $scope.renderSparklines = function(){
     angular.forEach($scope.sparklineData,function(e,i){
      if(e[0]!=0){
        e.unshift(0);
      }
      $("#sparkline-"+i).sparkline(e, 
      {
        type: 'line',
          width: $("#sparkline-"+i).parent().width(),
          height: '50'
        });
    });
  }

  $(window).bind('resize', function(e)
  {
      window.resizeEvt;
      $(window).resize(function()
      {
          clearTimeout(window.resizeEvt);
          window.resizeEvt = setTimeout(function()
          {
              $scope.renderSparklines();
          }, 250);
      });
  });

  $scope.init = function(){
    if($location.search().time){
      $scope.filterClick('time',$location.search().time,true);
    }
    if($location.search().tag){
      $scope.filterClick('tag',$location.search().tag,true);
    }
    if($location.search().newspaper){
      $scope.filterClick('newspaper',$location.search().newspaper,true);
    }

    Restangular.all('newspaper').getList().then(function(nList){
      $scope.newspapers = nList;
      Restangular.all('tag').getList().then(function(tList){
        $scope.tags = tList;
        $scope.refresh();
      });
    });

  }

  $scope.init();

});