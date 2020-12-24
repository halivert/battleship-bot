@extends('layouts.html')

@section('title', __('Bienvenido'))

@section('main')
	<main class="main">
		<div>
			<h1>
				<a>Bot</a>
			</h1>
		</div>

		<div>
			<table>
				@foreach (App\Models\Update::all() as $update)
					<tr>
						<td>{{ $update->update_id }}</td>
						<td>{{ $update->type }}</td>
						<td>{{ $update->{$update->type}->chat->id }}</td>
					</tr>
				@endforeach
			</table>
		</div>
	</main>
@endsection
