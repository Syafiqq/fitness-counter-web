@extends('layout.admin.event.admin_event_root_default')
@section('head-title')
    <title>Overview</title>
@endsection

@section('head-description')
    <meta name="description" content="Overview">
@endsection

@section('body-content')
    @parent
    <div id="app">
        <button @click="addNewPreset"> Buat Counter Baru</button>
        <hr>
    </div>@endsection

@section('head-css-post')
    @parent
    <link rel="stylesheet" href="{{asset('/css/layout/admin/event/overview/admin_event_overview_default.min.css')}}">
@endsection

@section('body-js-lower-post')
    @parent
    <script type="text/javascript" src="{{asset('/js/layout/admin/event/overview/admin_event_overview_default.min.js')}}"></script>
@endsection

@push('additional-firebase-library')
    <script type="text/javascript" src="{{asset('/vendor/firebase/firebase-database.min.js')}}"></script>
@endpush
