@extends('adminlte::page')

@section('title', 'Citizen RMS')

@section('content_header')


@stop

@section('content')

    <div id="app">
        <citizen-component></citizen-component>
    </div>

@stop

@extends('footer')


@section('js')
    <script src="{{ asset('js/app.js') }}" defer></script>
@stop
