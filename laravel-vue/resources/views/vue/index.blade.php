@extends('master')

@section('title', 'Food@Home Dashboard')

@section('navbar')
    <navbar-component></navbar-component>
@endsection

@section('content')
    <router-view></router-view>

@endsection
@section('pagescript')
<script src="{{ URL::asset('js/app.js') }}"></script>
@stop
