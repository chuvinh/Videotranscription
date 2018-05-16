<!DOCTYPE html>
<html>
<head>
	<title>Video transcript</title>
	<meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="{{ asset('css/style.css') }}">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="{{ asset('js/urlObject.js')}}"></script>
</head>
<body>
	<div class=" col-md-12">
		@include("header")
	</div>
	<div class=" col-md-12" style="margin-top: 70px;height: 400px;background: #ddefdd">
		@yield('content')
	</div>
	<div class=" col-md-12">
		@include("footer")
	</div>
	<script>
	$(".header-nav-item").click(function(){
		$(".header-nav-item").removeClass("is-active");
		$(this).addClass("is-active");
	});
</script>
</body>
</html>