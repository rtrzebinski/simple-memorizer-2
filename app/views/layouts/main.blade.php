<html>
	<head>
		<!-- jQuery JavaScript Library v1.10.2 -->
		<script src="jquery-ui-1.11.4/external/jquery/jquery.js" type="text/javascript"></script>
		<!-- jQuery UI - v1.11.4 -->
		<script src="jquery-ui-1.11.4/jquery-ui.min.js" type="text/javascript"></script>
		<link href="jquery-ui-1.11.4/jquery-ui.min.css" rel="stylesheet" type="text/css" />
		<!-- jTable 2.4.0 -->
		<script src="jtable.2.4.0/jquery.jtable.min.js" type="text/javascript"></script>
		<link href="jtable.2.4.0/themes/metro/blue/jtable.min.css" rel="stylesheet" type="text/css" />
		<!-- Bootstrap v3.3.5 -->
		<link rel="stylesheet" href="bootstrap-3.3.5-dist/css/bootstrap.min.css">
		<link rel="stylesheet" href="bootstrap-3.3.5-dist/css/bootstrap-theme.min.css">
		<script src="bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
		@yield('head')
	</head>
    <body>
        <div>
            @yield('content')
        </div>
    </body>
</html>