<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Basic Page Needs
		================================================== -->
		<meta charset="utf-8" />
		<title>
			@section('title')
			Shared Links Ranking
			@show
		</title>
		@section('meta_keywords')
		<meta name="keywords" content="news, links, ranking" />
		@show
		@section('meta_author')
		<meta name="author" content="@palamago" />
		@show
		@section('meta_description')
		<meta name="description" content="Shared Links Ranking from multiples RSS sources." />
                @show
		<!-- Mobile Specific Metas
		================================================== -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- CSS
		================================================== -->
        <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('material/css/ripples.min.css')}}">
        <link rel="stylesheet" href="{{asset('material/css/material-wfont.min.css')}}">

        <!--link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap-theme.min.css')}}"-->
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('css/main.css')}}">

		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<!-- Favicons
		================================================== -->
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{{ asset('assets/ico/apple-touch-icon-144-precomposed.png') }}}">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{{ asset('assets/ico/apple-touch-icon-114-precomposed.png') }}}">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{{ asset('assets/ico/apple-touch-icon-72-precomposed.png') }}}">
		<link rel="apple-touch-icon-precomposed" href="{{{ asset('assets/ico/apple-touch-icon-57-precomposed.png') }}}">
		<link rel="shortcut icon" href="{{{ asset('assets/ico/favicon.png') }}}">
	</head>

	<body ng-app="news-ranking-home">
		<!-- To make sticky footer need to wrap in a div -->
		<div id="wrap">

			<!-- Container -->
			<div class="container" ng-controller="HomeCtrl">
				<div class="row">
					<div class="col-md-4">
						<img class="img-responsive" src="http://lorempixel.com/500/500/abstract/" />
					</div>
					<div class="col-md-8">
						<div class="jumbotron">
							<h1>Top Ranking . Link</h1>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
					</div>
					@foreach($groups as $g)
						<div class="col-md-4">
							<div class="jumbotron" ng-init="getRanking('{{$g->slug}}')">
								<h2 class="text-center">{{$g->name}} Top 3</h2>
								<div class="row">
									<p class="text-center" ng-hide="rankings['{{$g->slug}}'].length>0">Loading</p>
									<div ng-repeat="r in rankings['{{$g->slug}}']" class="col-md-12">
									<strong>@{{$index+1}}.</strong> @{{r.title}}
									</div>
								</div>
							</div>
						</div>
					@endforeach
				</div>
				<div class="row">
					<div class="col-md-4">
					</div>
					@foreach($groups as $g)
						<div class="col-md-4">
							<a class="btn btn-block btn-primary text-center" href="/{{$g->slug}}/">Ver {{$g->name}} Top 10</a>
						</div>
					@endforeach
				</div>
				<div class="row">
					<div class="col-md-4">
					</div>
					@foreach($groups as $g)
						<div class="col-md-4">
							<div class="jumbotron">
								<h3 class="text-center">Medios de {{$g->name}}</h3>
								<div class="row">
									<p class="text-center" ng-hide="newspapers['{{$g->slug}}'].length>0">Loading</p>
									<div ng-repeat="n in newspapers['{{$g->slug}}']" class="col-md-3">
										<img class="img-responsive" ng-src="@{{n.logo}}" />
									</div>
								</div>
							</div>
						</div>
					@endforeach
				</div>

			</div>
			<!-- ./ container -->

			<!-- the following div is needed to make a sticky footer -->
			<div id="push"></div>
		</div>
		<!-- ./wrap -->

	    <div id="footer">
			<div class="container text-center">
				<p class="muted credit">
					<a href="http://twitter.com/gauyo" class="btn btn-default btn-fab btn-raised mdi-image-wb-incandescent" data-toggle="tooltip" data-placement="top" target="_blank" title="" data-original-title="Idea: @Gauyo"></a>

					<a href="http://twitter.com/palamago" class="btn btn-default btn-fab btn-raised mdi-action-settings" data-toggle="tooltip" data-placement="top" target="_blank" title="" data-original-title="Development: @palamago"></a>

					<a href="http://blog.palamago.com.ar/2014/11/shared-links-ranking/" class="btn btn-default btn-fab btn-raised mdi-editor-format-quote" data-toggle="tooltip" data-placement="top" target="_blank" title="" data-original-title="Blog Post (Spanish)"></a>

					<a href="https://github.com/palamago/shared-links-ranking" class="btn btn-default btn-fab btn-raised mdi-device-developer-mode" data-toggle="tooltip" data-placement="top" target="_blank" title="" data-original-title="Source Code"></a>

					<div id="compartir" class="">
							<div class="row">
								<div class="col-sm-2 col-sm-offset-3 text-center" fb-like></div>
								<div class="col-sm-2 text-center" google-plus></div>
								<div class="col-sm-2 text-center" tweet="shareText"></div>
							</div>
					</div>
			</div>
	    </div>

		<!-- Javascripts
		================================================== -->
        <script src="{{asset('js/libs/jquery.min.js')}}"></script>
        <script src="{{asset('js/libs/jquery.sparkline.min.js')}}"></script>
        <script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>

        <script src="{{asset('material/js/ripples.min.js')}}"></script>
        <script src="{{asset('material/js/material.min.js')}}"></script>

  		<script src="{{asset('js/libs/moment.js')}}"></script>
        <script src="{{asset('js/libs/lodash.min.js')}}"></script>
        <script src="{{asset('js/libs/angular.min.js')}}"></script>
        <script src="{{asset('js/libs/angular-route.min.js')}}"></script>
        <script src="{{asset('js/libs/restangular.min.js')}}"></script>
        <script src="{{asset('js/libs/angulike.js')}}"></script>

        <script src="{{asset('bower_components/d3plus/d3plus.full.min.js')}}"></script>

		<script src="{{asset('js/app-home.js')}}"></script>
		<script src="{{asset('js/HomeCtrl.js')}}"></script>
        <script src="{{asset('js/main.js')}}"></script>

        @yield('scripts')

		<script type="text/javascript">

		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', '<?php echo getenv('ga_code')?>']);
		  _gaq.push(['_trackPageview']);

		  (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();

		</script>

	</body>
</html>


