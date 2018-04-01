@extends('layout.registrar.event.registrar_event_root_default')
@section('head-title')
    <title>Overview</title>
@endsection

@section('head-description')
    <meta name="description" content="Overview">
@endsection

@section('body-content')
    @parent
    <div id="app">
        <input type="text " placeholder="No. Peserta" v-model="f_participant" required :disabled="is_process">
        <br>
        <input type="radio" id="one" value="1" v-model="f_same">
        <label for="one">Wajah Mirip</label>
        <br>
        <input type="radio" id="two" value="0" v-model="f_same">
        <label for="two">Wajah Tidak Mirip</label>
        <br>
        <br>
        <input type="radio" id="three" value="l" v-model="f_gender">
        <label for="three">Laki-laki</label>
        <br>
        <input type="radio" id="four" value="p" v-model="f_gender">
        <label for="four">Perempuan</label>
        <br>
        <button @click="openModal" :disabled="is_process">Daftarkan</button>
    </div>
@endsection

@section('head-css-post')
    @parent
    <link rel="stylesheet" href="{{asset('/css/layout/registrar/event/overview/registrar_event_overview_default.min.css')}}">
@endsection

@section('body-js-lower-post')
    @parent
    <script type="text/javascript" src="{{asset('/js/layout/registrar/event/overview/registrar_event_overview_default.min.js')}}"></script>
@endsection

@push('additional-firebase-library')
    <script type="text/javascript" src="{{asset('/vendor/firebase/firebase-database.min.js')}}"></script>
@endpush
