<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width">
	  <link href="{{ url('css/app.css') }}" rel="stylesheet">
		<title>@yield('title', __('Batalla naval'))</title>
	</head>
	<body>
		@section('main')
			<main class="is-flex center vcenter is-full-height">
				<div>
					<h1>
						<a>Bota</a>
					</h1>
				</div>
			</main>
		@show
	</body>
</html>
