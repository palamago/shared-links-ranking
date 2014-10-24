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
		<meta name="keywords" content="your, awesome, keywords, here" />
		@show
		@section('meta_author')
		<meta name="author" content="Jon Doe" />
		@show
		@section('meta_description')
		<meta name="description" content="Lorem ipsum dolor sit amet, nihil fabulas et sea, nam posse menandri scripserit no, mei." />
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
		<style>
        body {
            padding: 110px 0;
        }
		@section('styles')
		@show
		</style>

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

	<body ng-app="news-ranking-project">
		<!-- To make sticky footer need to wrap in a div -->
		<div id="wrap">

		<!-- Container -->
		<div class="container">
			<!-- Notifications -->
			@include('notifications')
			<!-- ./ notifications -->
			<div>

				<div ng-view></div>

			</div>

			<!-- ./ content -->
		</div>
		<!-- ./ container -->

		<!-- the following div is needed to make a sticky footer -->
		<div id="push"></div>
		</div>
		<!-- ./wrap -->


	    <div id="footer">
	      <div class="container">
	        <p class="muted credit">@palamago</p>
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
		<script src="{{asset('js/app.js')}}"></script>
        <script src="{{asset('js/main.js')}}"></script>

        @yield('scripts')

		<script type="text/javascript">

		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-56067310-1']);
		  _gaq.push(['_trackPageview']);

		  (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();

		</script>

	</body>
</html>


