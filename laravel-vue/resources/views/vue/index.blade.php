@extends('master')

@section('title', 'Vue.js App')

@section('navbar')
    <navbar-component></navbar-component>
@endsection

@section('content')
    <router-view></router-view>

@endsection
@section('pagescript')
<script src="js/app.js"></script>
@stop
