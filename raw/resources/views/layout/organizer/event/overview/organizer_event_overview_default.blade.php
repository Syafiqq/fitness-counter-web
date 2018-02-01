@extends('root.root-authenticated-theme-default')
@section('head-title')
    @parent
    <title>Overview</title>
@endsection

@section('head-meta')
    @parent
    <meta name="event" content="{!! $meta['event'] !!}">
@endsection

@section('head-description')
    @parent
    <meta name="description" content="Overview">
@endsection

@section('body-content')
    @parent
    Event Overview
@endsection

@section('head-js-post')
    @parent
@endsection

@section('head-css-post')
    @parent
    <link rel="stylesheet" href="{{asset('/css/layout/organizer/event/overview/organizer_event_overview_default.min.css')}}">
@endsection

@section('body-js-lower-post')
    @parent
    <script type="text/javascript" src="{{asset('/js/layout/organizer/event/overview/organizer_event_overview_default.min.js')}}"></script>
@endsection

@push('additional-firebase-library')
    <script type="text/javascript" src="{{asset('/vendor/firebase/firebase-database.min.js')}}"></script>
@endpush
