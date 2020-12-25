@extends('layouts.html')

@section('title', __('Bienvenido'))

@section('main')
	<main class="h-full bg-gray-100 flex items-center justify-center text-xl">
		<div>
			<a href="{{ env('BOT_URL') }}">
				Bot de <strong class="text-green-500">{{ env('APP_NAME') }}</strong>
			</a>
		</div>
	</main>
@endsection
