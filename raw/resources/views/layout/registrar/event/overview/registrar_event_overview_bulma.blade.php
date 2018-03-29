@extends('layout.registrar.event.registrar_event_root_bulma')
@section('head-title')
    <title>Overview</title>
@endsection

@section('head-description')
    <meta name="description" content="Overview">
@endsection

@section('body-content')
    @parent
    <div id="app">
        <section class="hero is-info is-large">
            <div class="hero-body">
                <div class="container has-text-centered">
                    <div class="column is-6 is-offset-3">
                        <div class="box">
                            <div class="field">
                                <div class="control">
                                    <label class="radio" style="vertical-align: center">
                                        <input type="radio" id="one" value="1" v-model="f_same">
                                        Wajah Mirip
                                    </label>
                                    <label class="radio" style="vertical-align: center">
                                        <input type="radio" id="two" value="0" v-model="f_same">
                                        Wajah Tidak Mirip
                                    </label>
                                </div>
                            </div>
                            <div class="field is-grouped">
                                <p class="control is-expanded">
                                    <input @keyup.enter="openModal" class="input" type="text " placeholder="No. Peserta" v-model="f_participant" required :disabled="is_process">
                                </p>
                                <p class="control">
                                    <a @click="openModal" :disabled="is_process" class="button is-info">
                                        Daftarkan
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('head-css-post')
    @parent
    <link rel="stylesheet" href="{{asset('/css/layout/registrar/event/overview/registrar_event_overview_bulma.min.css')}}">
@endsection

@section('body-js-lower-post')
    @parent
    <script type="text/javascript" src="{{asset('/js/layout/registrar/event/overview/registrar_event_overview_bulma.min.js')}}"></script>
@endsection

@push('additional-firebase-library')
    <script type="text/javascript" src="{{asset('/vendor/firebase/firebase-database.min.js')}}"></script>
@endpush
