@extends('layout.admin.event.admin_event_root_bulma')
@section('head-title')
    <title>Overview</title>
@endsection

@section('head-description')
    <meta name="description" content="Overview">
@endsection

@section('body-content')
    @parent
    <div id="app">
        <div class="container">
            <div class="columns">
                <div class="column is-12">
                    <div class="card events-card">
                        <header class="card-header">
                            <p class="card-header-title">
                                Peserta Hari Ini
                            </p>
                        </header>
                        <div class="card-table is-fullheight" style="height: 600px; max-height: 440px!important;">
                            <div class="content" style="margin: 20px">
                                <v-client-table :data="queues" :columns="qt_columns" :options="qt_options"></v-client-table>
                            </div>
                        </div>
                        <footer class="card-footer">
                            <a @click="addNewPreset" href="javascript:void(0)" class="card-footer-item">Buat Counter Baru</a>
                        </footer>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('head-css-post')
    @parent
    <link rel="stylesheet" href="{{asset('/css/layout/admin/event/overview/admin_event_overview_bulma.min.css')}}">
@endsection

@section('body-js-lower-post')
    @parent
    <script type="text/javascript" src="{{asset('/js/layout/admin/event/overview/admin_event_overview_bulma.min.js')}}"></script>
@endsection
