@extends('layout.admin.event.admin_event_root_default')
@section('head-title')
    <title>Evaluation Report</title>
@endsection

@section('head-description')
    <meta name="description" content="Evaluation Report">
@endsection

@section('body-content')
    @parent
    Ini Evaluation Report
@endsection

@section('head-css-post')
    @parent
    <link rel="stylesheet" href="{{asset('/css/layout/admin/event/report/evaluation/admin_event_report_evaluation_default.min.css')}}">
@endsection

@section('body-js-lower-post')
    @parent
    <script type="text/javascript" src="{{asset('/js/layout/admin/event/report/evaluation/admin_event_report_evaluation_default.min.js')}}"></script>
@endsection
