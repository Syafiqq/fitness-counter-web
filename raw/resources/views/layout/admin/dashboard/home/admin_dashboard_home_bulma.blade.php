@extends('root.root-authenticated-theme-bulma')
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
            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label">Nama Event</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <p class="control is-expanded has-icons-left">
                            <input class="input" type="text" name="event" placeholder="Event" v-model="f_event" required :disabled="is_process">
                        </p>
                    </div>
                </div>
            </div>
            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label">ID Event</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <p class="control is-expanded has-icons-left">
                            <input class="input" type="text" name="slug" placeholder="Event-ID" v-model="f_slug" required :disabled="is_process">
                        </p>
                    </div>
                </div>
            </div>
            <button class="button is-link" slot="button" @click="eventFormCommit" :disabled="is_process">Submit</button>
        </sweet-modal>
        <div class="container">
            <div class="columns">
                <div class="column is-12">
                    <div class="card events-card">
                        <header class="card-header">
                            <p class="card-header-title">
                                Events
                            </p>
                        </header>
                        <div class="card-table is-fullheight" style="height: 600px; max-height: 440px!important;">
                            <div class="content">
                                <table class="table is-fullwidth is-striped">
                                    <tbody>
                                    <tr is="list-event" v-for="(value, key) in l_events"
                                        :event="value"
                                        :event_id="key"
                                        :key="key"></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <footer class="card-footer">
                            <a @click="eventFormOpen" href="javascript:void(0)" class="card-footer-item">Buat Event Baru</a>
                        </footer>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('head-css-post')
    @parent
    <link rel="stylesheet" href="{{asset('/css/layout/admin/dashboard/home/admin_dashboard_home_bulma.min.css')}}">
@endsection

@section('body-js-lower-post')
    @parent
    <script type="text/javascript" src="{{asset('/js/layout/admin/dashboard/home/admin_dashboard_home_bulma.min.js')}}"></script>
@endsection

@push('additional-firebase-library')
    <script type="text/javascript" src="{{asset('/vendor/firebase/firebase-database.min.js')}}"></script>
@endpush
