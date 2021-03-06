@extends('root.root-authenticated-theme-default')
@section('head-title')
    <title>Dashboard</title>
@endsection

@section('head-description')
    <meta name="description" content="Dashboard">
@endsection

@section('body-content')
    @parent
    Ini Trainer
@endsection

@section('head-css-post')
    @parent
    <link rel="stylesheet" href="{{asset('/css/layout/trainer/dashboard/home/trainer_dashboard_home_default.min.css')}}">
@endsection

@section('body-js-lower-post')
    @parent
    <script type="text/javascript" src="{{asset('/js/layout/trainer/dashboard/home/trainer_dashboard_home_default.min.js')}}"></script>
@endsection
