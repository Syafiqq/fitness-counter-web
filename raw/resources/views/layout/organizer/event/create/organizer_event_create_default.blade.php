@extends('root.root-authenticated-theme-default')
<?php
/** @var \Collective\Html\FormBuilder $form */
$form = \Collective\Html\FormFacade::getFacadeRoot();
?>
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
    <div id="app">
        <h1>Creaete Event</h1>
        {!! $form->text('event', null, ['placeholder' => 'Event', 'required'=> true, 'v-bind:disabled'=>'is_logged_out']) !!}
        {!! nl2br(PHP_EOL) !!}
        {!! $form->text('slug', null, ['placeholder' => 'Slug', 'required'=> true, 'v-bind:disabled'=>'is_logged_out']) !!}
        {!! nl2br(PHP_EOL) !!}
        {!! $form->button('Submit', ['type' => 'Submit', 'v-bind:disabled'=>'is_logged_out']) !!}
    </div>
@endsection

@section('head-js-post')
    @parent
    @include('component.js.vue')
@endsection

@section('head-css-post')
    @parent
    <link rel="stylesheet" href="{{asset('/css/layout/organizer/event/create/organizer_event_create_default.min.css')}}">
@endsection

@section('body-js-lower-post')
    @parent
    <script type="text/javascript" src="{{asset('/js/layout/organizer/event/create/organizer_event_create_default.min.js')}}"></script>
@endsection
