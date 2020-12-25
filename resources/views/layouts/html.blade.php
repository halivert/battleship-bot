<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-screen">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width">
	  {{-- <link href="{{ url('css/app.css') }}" rel="stylesheet"> --}}
		<link
			href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css"
			rel="stylesheet">
		<title>@yield('title', __('Batalla naval'))</title>
	</head>
	<body class="overflow-y-scroll p-8 h-full">
		@yield('main')
	</body>
</html>
