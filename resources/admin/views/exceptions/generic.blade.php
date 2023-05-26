@extends('layouts.public')

@section('content')
    <section class="container p-24 mx-auto">
        <h1 class="text-5xl text-blue-600">{{ $code }}</h1>
        <p class="text-2xl text-gray-600">{{ $message }}</p>
    </section>
@endsection
