@extends('{$addon_name}::layouts.app')

<?php
	$title = trans('{$addon_name}::messages.sample_title');
?>



{{--
--}}
@section('content')

<h1>{{ $title }}</h1>

@endsection



{{--
--}}
@section('styles')

@endsection



{{--
--}}
@section('scripts')

@endsection
