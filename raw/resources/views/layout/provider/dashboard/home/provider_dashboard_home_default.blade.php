@extends('root.root-authenticated-theme-default')
@section('head-title')
    @parent
    <title>Dashboard</title>
@endsection

@section('head-description')
    @parent
    <meta name="description" content="Dashboard">
@endsection

@section('body-content')
    @parent
    Ini Provider
@endsection

@section('head-css-post')
    @parent
    <link rel="stylesheet" href="{{asset('/css/layout/provider/dashboard/home/provider_dashboard_home_default.min.css')}}">
@endsection

@section('body-js-lower-post')
    @parent
    <script type="text/javascript" src="{{asset('/js/layout/provider/dashboard/home/provider_dashboard_home_default.js')}}"></script>
@endsection
