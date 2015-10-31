<!DOCTYPE html>
<html lang="{$lang}">
<head>
	<meta charset="UTF-8">
	<title>Debug Form</title>
	<meta name="description" content="">
	<meta name="keyword" content="">
@section('styles')
{{-- スタイルシート指定のプレースホルダです。 --}}
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
@show
</head>

<body>
@yield ('content')

@section('scripts')
{{-- JavaScript指定のプレースホルダです。 --}}
<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
@show
</body>
</html>
