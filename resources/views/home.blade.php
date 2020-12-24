@extends('layouts.html')

@section('title', __('Bienvenido'))

@section('styles')
	<style>
		main.main {
			display: flex;
		}
	</style>
@endsection

@section('main')
	<main class="main">
		<div>
			<h1>
				<a>Bot</a>
			</h1>
		</div>
	</main>
@endsection
