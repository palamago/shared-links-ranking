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

    if($scope.filters.newspaper && $scope.filters.newspaper!=''){
      temp = _.where($scope.newspapers, {id: $scope.filters.newspaper})[0];
      if(!temp){
        return;
      }
      $scope.titles.newspaper = temp.name;
    } else {
      $scope.titles.newspaper = false;
    }

    if($scope.filters.tag && $scope.filters.tag!=''){
      temp = _.where($scope.tags, {id: $scope.filters.tag})[0];
      if(!temp){
        return;
      }
      $scope.titles.tag = temp.name;
      $scope.titles.tagColor = temp.color;
    } else {
      $scope.titles.tag = false;
      $scope.titles.tagColor = '';
    }

  }

  $scope.filterClick = function(filter,value,justSet){
    _gaq.push(['_trackEvent', 'filter', filter, value+'']);
    $scope.filters[filter] = value;
    $location.search(filter,value);
    window.location = $location.absUrl();
    if(!justSet){
      $scope.refresh();
    } else {
    }
    if($('.navbar-toggle').is(':visible') && $('#nav-main').is(':visible')){
      $("#nav-main").collapse('hide');
    }
  }

  $scope.refresh = function(){
    $scope.loading = true;
    $scope.topnews = [];
    Restangular.all('topnews').getList($scope.filters).then(function(data){
      $scope.createTitle();
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

      //Set sizes on Back
      $('.back .social-numbers').height($('.front').height()/2);
      $('.back .social-numbers').width($('.front').width());

      var w = $("#sparkline-accum-"+i).parent().width();
      var q = acum.length;

    /*  $("#sparkline-"+i).sparkline(diff, 
        {
          type: 'line',
          width: w,
          height: 35,
          tooltipFormat: '<span class="tooltip-clas"><span style="color: {{color}}">&#9679;</span> {{prefix}}{{y}}{{suffix}}</span>',
          
        });*/

      $("#sparkline-accum-"+i).sparkline(acum, 
        {
          type: 'line',
          width: w,
          height: 35,
          tooltipFormat: '<span class="tooltip-clas"><span style="color: {{color}}">&#9679;</span> {{prefix}}{{y}}{{suffix}}</span>',
          lineColor: 'rgba(255,255,255,1)',
          spotColor: '#FFFFFF',
          minSpotColor: '#FFFFFF',
          maxSpotColor: '#FFFFFF',
         // valueSpots: acum,
          spotRadius: 3,
          lineWidth: 3,
          fillColor: false,
          highlightSpotColor: '#4285f4'
/*          barWidth: (w*0.2)/q,
          barSpacing: (w*0.8)/(q-1)*/
        });
    });
  }

  $scope.toggleAutorefresh = function(){
    if($scope.autorefresh && $scope.autorefreshID){
      $scope.autorefresh = false;
      clearInterval($scope.autorefreshID);
    } else {
      $scope.autorefresh = true;
      $scope.autorefreshID = setInterval(function(){
        $scope.refresh();
      },20*60*1000);
    }
  }

  $scope.init = function(){

    Restangular.all('newspaper').getList().then(function(nList){
      $scope.newspapers = Restangular.stripRestangular(nList);
      Restangular.all('tag').getList().then(function(tList){
        $scope.tags = Restangular.stripRestangular(tList);
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
        
        $('[data-toggle="tooltip"]').tooltip();

        $.material.init();

        //Remove this horrible thing. Just to avoid a weird problem with openshift load-times
        $scope.refresh();

        //Autorefresh Init
        $scope.autorefresh = false;
        $scope.autorefreshID = null;
        $scope.toggleAutorefresh();

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
            }, 1000);
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
