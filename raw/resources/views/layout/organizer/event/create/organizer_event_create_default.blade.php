@extends('root.root-authenticated-theme-default')
@section('head-title')
    @parent
    <title>Event</title>
@endsection

@section('head-description')
    @parent
    <meta name="description" content="Event">
@endsection

@section('body-content')
    @parent
    <h1>Creaete Event</h1>
@endsection

@section('head-css-post')
    @parent
    <link rel="stylesheet" href="{{asset('/css/layout/organizer/event/create/organizer_event_create_default.min.css')}}">
@endsection

@section('body-js-lower-post')
    @parent
    <script type="text/javascript" src="{{asset('/js/layout/organizer/event/create/organizer_event_create_default.min.js')}}"></script>
@endsection
