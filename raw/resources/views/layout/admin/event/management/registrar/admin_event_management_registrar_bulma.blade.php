@extends('layout.admin.event.admin_event_root_bulma')
@section('head-title')
    <title>Registrar</title>
@endsection

@section('head-description')
    <meta name="description" content="Registrar Management">
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
                                Management Hak Akses Registrar
                            </p>
                        </header>
                        <div class="card-table is-fullheight" style="height: 600px; max-height: 440px!important;">
                            <div class="content" style="margin: 20px">
                                <v-client-table :data="registrar" :columns="qt_columns" :options="qt_options">
                                    <toggle-button :value="false"
                                                   :sync="true"
                                                   :labels="true"
                                                   slot="participate"
                                                   slot-scope="props"
                                                   v-model="props.row.participate"
                                                   @change="check(props.row.uid, props.row.participate)">
                                    </toggle-button>
                                </v-client-table>
                            </div>
                        </div>
                        <footer class="card-footer">
                            <a @click="save" href="javascript:void(0)" class="card-footer-item is-success">Save</a>
                        </footer>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('head-css-post')
    @parent
    <link rel="stylesheet" href="{{asset('/css/layout/admin/event/management/registrar/admin_event_management_registrar_bulma.min.css')}}">
@endsection

@section('body-js-lower-post')
    @parent
    <script type="text/javascript" src="{{asset('/js/layout/admin/event/management/registrar/admin_event_management_registrar_bulma.min.js')}}"></script>
@endsection
