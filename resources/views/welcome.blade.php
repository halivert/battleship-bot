@extends('layouts.html')

@section('title', __('Bienvenido'))

@section('main')
    <main class="h-full bg-gray-100 flex items-center justify-center text-xl">
        <div>
            <a href="https://t.me/{{ config('botman.telegram.username') }}">
                Bot de <strong class="text-green-500">{{
                    config('app.name')
                }}</strong>
            </a>
        </div>
    </main>
@endsection
