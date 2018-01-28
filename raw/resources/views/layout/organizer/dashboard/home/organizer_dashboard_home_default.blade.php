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
    <div id="app">
        <sweet-modal ref="modal">This is an alert.</sweet-modal>
        <button @click="testModal">TestModal</button>
    </div>
@endsection

@section('head-js-post')
    @parent
@endsection

@section('head-css-post')
    @parent
    <link rel="stylesheet" href="{{asset('/css/layout/organizer/dashboard/home/organizer_dashboard_home_default.min.css')}}">
@endsection

@section('body-js-lower-post')
    @parent
    <script type="text/javascript" src="{{asset('/js/layout/organizer/dashboard/home/organizer_dashboard_home_default.min.js')}}"></script>
@endsection
