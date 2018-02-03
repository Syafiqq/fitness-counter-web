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
        <sweet-modal ref="modal" title="Buat Event Baru">
            <input type="text" name="event" placeholder="Event" v-model="f_event" required :disabled="is_process">
            <br>
            <input type="text" name="slug" placeholder="Event-ID" v-model="f_slug" required :disabled="is_process">
            <br>
            <button slot="button" @click="eventFormCommit" :disabled="is_process">Submit</button>
        </sweet-modal>
        <button @click="eventFormOpen">Buat Event Baru</button>
        <hr>
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
    <link rel="stylesheet" href="{{asset('/css/layout/organizer/dashboard/home/organizer_dashboard_home_default.min.css')}}">
@endsection

@section('body-js-lower-post')
    @parent
    <script type="text/javascript" src="{{asset('/js/layout/organizer/dashboard/home/organizer_dashboard_home_default.min.js')}}"></script>
@endsection

@push('additional-firebase-library')
    <script type="text/javascript" src="{{asset('/vendor/firebase/firebase-database.min.js')}}"></script>
@endpush
