var NewsApp = angular.module('news-ranking-project', ['ngRoute','restangular','angulike']);

NewsApp.config(function($routeProvider) {
  $routeProvider
    .when('/', {
      controller:'TopCtrl',
      templateUrl:'/partials/top.html',
      reloadOnSearch: false
    })
    .when('/acerca', {
      templateUrl:'/partials/about.html'
    })
    .when('/link/:id', {
      controller:'LinkCtrl',
      templateUrl:'/partials/link.html'
    });

});

NewsApp.run(function($rootScope, Restangular){

  Restangular.setBaseUrl('/api');

  $rootScope.getDateDiff = function(date){
    var diff = moment().diff(moment(date));
    diff = Math.floor(moment.duration(diff).asHours());
    diff = (diff==0)?'menos de una hora':(diff==1)?'una hora':diff+' horas';
    return "hace "+ diff;
  }

  $rootScope.shareText = "Shared Links Ranking";

});

NewsApp.controller('TopCtrl', function($scope, Restangular, $http, $location) {

  $scope.titles = {
    newspaper: false,
    tag:false,
    tagColor:''
  }

  $scope.filters = {
  	newspaper:'',
  	tag:'',
    hs: 1
  }

  $scope.times = [ '1', '3', '6', '12', '24' ];
  
  $scope.newspapers   = [];
  $scope.tags     = [];
  $scope.topnews    = [];
  $scope.loading = true;
  $scope.sparklineData = {};

  $scope.createTitle = function(){

    var temp;

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
    _gaq.push(['_trackEvent', 'filter', filter, value]);
    $scope.filters[filter] = value;
    $location.search(filter,value);
    window.location = $location.absUrl();
    if(!justSet){
      $scope.refresh();
    }
    if($('.navbar-toggle').is(':visible') && $('#nav-main').is(':visible')){
      $("#nav-main").collapse('hide');
    }
  }

  $scope.refresh = function(){
    $scope.loading = true;
    $scope.createTitle();
    $scope.topnews = [];
    Restangular.all('topnews').getList($scope.filters).then(function(data){
      $scope.loading = false;
      $scope.topnews = data;
      $scope.refreshSparklines();
    });
  }   


  $scope.refreshSparklines = function(){
    if($scope.topnews.length){
      var IDs = [];
      angular.forEach($scope.topnews,function(e,i){
        IDs.push(e.id);
      });

      Restangular.one('sparklines', IDs.join(',')).get().then(function(data){
        $scope.sparklineData = data.data;
        $scope.renderSparklines();
      });
    }
  }

  $scope.renderSparklines = function(){
     angular.forEach($scope.sparklineData,function(e,i){
      var diff = e.dif_total;
      var acum = e.total;

      if(diff[0]!=0){
        diff.unshift(0);
      }

      if(acum[0]!=0){
        acum.unshift(0);
      }

      var w = $("#sparkline-"+i).parent().width();
      var q = acum.length;

      $("#sparkline-"+i).sparkline(diff, 
        {
          type: 'line',
          width: w,
          height: 35,
          tooltipFormat: '<span class="tooltip-clas"><span style="color: {{color}}">&#9679;</span> {{prefix}}{{y}}{{suffix}}</span>',
          
        });

      $("#sparkline-accum-"+i).sparkline(acum, 
        {
          type: 'line',
          width: w,
          height: 35,
          tooltipFormat: '<span class="tooltip-clas"><span style="color: {{color}}">&#9679;</span> {{prefix}}{{y}}{{suffix}}</span>'
/*          barColor: 'rgba(0,0,0,0.5)',
          barWidth: (w*0.2)/q,
          barSpacing: (w*0.8)/(q-1)*/
        });
    });
  }


  $scope.init = function(){

    Restangular.all('newspaper').getList().then(function(nList){
      $scope.newspapers = nList;
      Restangular.all('tag').getList().then(function(tList){
        $scope.tags = tList;
        if($location.search().tag){
          $scope.filterClick('tag',$location.search().tag,true);
        }
        if($location.search().newspaper){
          $scope.filterClick('newspaper',$location.search().newspaper,true);
        }
        if($location.search().hs){
          var hs = _.contains($scope.times, $location.search().hs)?$location.search().hs:3;
          $scope.filterClick('hs',hs,true);
        }
        $scope.refresh();
      });
    });
  
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

  }

  $scope.init();

});

NewsApp.controller('LinkCtrl', function($scope, Restangular, $http, $routeParams, $location) {

  $scope.link    = false;
  $scope.loading = true;

  $scope.init = function(){
    Restangular.one('link', $routeParams.id).get().then(function(data){
      console.log(data);
      if(data.id){
        $scope.link = data;
      } else {
        $location.path('/');
      }
      $scope.loading = false;
    });
  };

  $scope.init();

});