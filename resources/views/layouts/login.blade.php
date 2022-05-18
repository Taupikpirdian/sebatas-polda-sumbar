<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Masuk - Dashboard Aplikasi Sebaran Data Kriminalitas </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
		<link href="{{ asset('asset/css/style.css') }}" rel="stylesheet">

</head>

<body class="h-100">

		@yield('content')

<!-- Required vendors -->
<script src="{{ asset('asset/vendor/global/global.min.js') }}"></script>
<script src="{{ asset('asset/vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('asset/js/custom.min.js') }}"></script>
<script src="{{ asset('asset/js/deznav-init.js') }}"></script>
</body>

</html>