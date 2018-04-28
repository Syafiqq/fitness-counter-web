@extends('layout.admin.event.admin_event_root_bulma')
@section('head-title')
    <title>Upload Peserta</title>
@endsection

@section('head-description')
    <meta name="description" content="Upload Peserta">
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
                                Daftar Peserta
                            </p>
                        </header>
                        <div class="card-table is-fullheight">
                            <div class="content" style="margin: 20px">
                                <v-client-table :data="queues" :columns="qt_columns" :options="qt_options">
                                </v-client-table>
                            </div>
                        </div>
                        <footer class="card-footer">
                            <a @click="downloadTemplate" href="javascript:void(0)" class="card-footer-item is-success">
                                <span class="icon"><i class="fa fa-download"></i></span>
                                Download Template
                            </a>
                            <input type="file" name="upload" id="upload" style="display: none" ref="upload" accept="text/csv, .csv">
                            <a @click="uploadParticipant" href="javascript:void(0)" class="card-footer-item is-info">
                                <span class="icon"><i class="fa fa-upload"></i></span>
                                Upload Data
                            </a>
                        </footer>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('head-css-post')
    @parent
    <link rel="stylesheet" href="{{asset('/css/layout/admin/event/upload/participant/admin_event_upload_participant_bulma.min.css')}}">
@endsection

@section('body-js-lower-post')
    @parent
    <script type="text/javascript" src="{{asset('/js/common/common-download.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('/js/layout/admin/event/upload/participant/admin_event_upload_participant_bulma.min.js')}}"></script>
@endsection
