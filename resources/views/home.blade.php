@extends('adminlte::page')

@section('title', 'Citizens Refer Management System')

@section('content_header')


@stop

@section('content')

    <div id="app">
        <example-component></example-component>
    </div>

@stop

@extends('footer')


@section('js')
    <script src="{{ asset('js/app.js') }}" defer></script>
@stop
