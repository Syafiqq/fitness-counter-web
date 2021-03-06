@extends('layout.admin.event.admin_event_root_default')
@section('head-title')
    <title>Tester</title>
@endsection

@section('head-description')
    <meta name="description" content="Tester Management">
@endsection

@section('body-content')
    @parent
    <div id="app">
        <v-client-table :data="tester" :columns="qt_columns" :options="qt_options">
            <input slot="participate" slot-scope="props" type="checkbox" id="checkbox" v-model="props.row.participate" @click="check(props.row.uid, props.row.participate)">
        </v-client-table>
        <button @click="save">Save</button>
    </div>
@endsection

@section('head-css-post')
    @parent
    <link rel="stylesheet" href="{{asset('/css/layout/admin/event/management/tester/admin_event_management_tester_default.min.css')}}">
@endsection

@section('body-js-lower-post')
    @parent
    <script type="text/javascript" src="{{asset('/js/layout/admin/event/management/tester/admin_event_management_tester_default.min.js')}}"></script>
@endsection
