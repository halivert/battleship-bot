@extends('layouts.html')

@section('main')
	<main class="main">
		<form action="{{ route('set-webhook.update') }}">
			<label class="label" for="password">{{
				__('Contraseña')
			}}</label>
			<input id="password" type="text" name="password">
		</form>
	</main>
@endsection
