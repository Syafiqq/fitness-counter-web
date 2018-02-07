@extends('root.root-authenticated-theme-default')
@section('head-title')
    <title>Dashboard</title>
@endsection

@section('head-description')
    <meta name="description" content="Dashboard">
@endsection

@section('body-content')
    @parent
    <div id="app">
        <ol>
            <list-event
                    v-for="(value, key) in l_events"
                    :event="value"
                    :event_id="key"
                    :key="key"
            >
            </list-event>
        </ol>
    </div>
@endsection

@section('head-css-post')
    @parent
    <link rel="stylesheet" href="{{asset('/css/layout/registrar/dashboard/home/registrar_dashboard_home_default.min.css')}}">
@endsection

@section('body-js-lower-post')
    @parent
    <script type="text/javascript" src="{{asset('/js/layout/registrar/dashboard/home/registrar_dashboard_home_default.min.js')}}"></script>
@endsection

@push('additional-firebase-library')
    <script type="text/javascript" src="{{asset('/vendor/firebase/firebase-database.min.js')}}"></script>
@endpush
