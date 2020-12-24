<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width">
	  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
		<title>@yield('title', __('Batalla naval'))</title>
		<style>
			main.main {
				display: flex;
			}
		</style>
	</head>
	<body>
		@section('main')
			<main class="main">
				<div>
					<h1>
						<a>Bota</a>
					</h1>
				</div>
			</main>
		@show
	</body>
</html>
