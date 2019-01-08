@extends('master')

@section('title', 'Restaurant Management')

@section('content')  
    <nav-bar> </nav-bar>
    <router-view class="container"> </router-view>
@endsection

@section('pagescript')
    <script src="js/vue.js"></script>
@stop