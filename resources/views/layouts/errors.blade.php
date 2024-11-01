@if (request()->header('Base-Replace-Content'))
	<script>
		window.location.reload();
	</script>
@else
	<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="utf-8" />
        <title>{{ !empty($title) ? $title.' | '.config('app.name') : config('app.name') }}</title>
        <meta name="debug" content="{{ config('app.debug') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="base-url" content="{{ yurl('/') }}">
        <meta name="replace" content="1">
        <meta name="author" content="Tunas Mekar" />
        <meta name="description" content="Tunas Mekar" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

        <link rel="shortcut icon" href="{{ '/'.(config('base.logo.favicon')) }}" />
        <link rel="stylesheet" href="{{ ('/assets/css/fonts/poppins/all.css') }}">
        <link rel="stylesheet" href="{{ (('/assets/css/plugins.bundle.css')) }}">
        <link rel="stylesheet" href="{{ (('/assets/css/theme.bundle.css')) }}">
        <link rel="stylesheet" href="{{ (('/assets/css/theme.skins.bundle.css')) }}">
	</head>
	<body id="kt_body" class="header-fixed header-mobile-fixed">
		@yield('content')

		<script src="{{ (('/assets/js/plugins.bundle.js')) }}"></script>
		@stack('scripts')
	</body>
	</html>
@endif
