@extends('layout.app')

@section('content')
    <h1>WPKG Tool</h1>

    <a href="{{ url('/programs') }}" class="code">> Programs</a>
    <a href="{{ url('/machines') }}" class="code">> Machines</a>
    <a href="{{ url('/profiles') }}" class="code">> Profiles</a>
@stop