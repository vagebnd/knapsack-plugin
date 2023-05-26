@use(function Skeleton\Support\vite)

@extends('layouts.public')
{{ vite()->asset('views/pricelist.ts') }}

@section('content')
<div id="vue" />
@endsection
