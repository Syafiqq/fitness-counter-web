@extends('root.root-firebase-default')
<?php
/** @var \Collective\Html\FormBuilder $form */
$form = \Collective\Html\FormFacade::getFacadeRoot();
?>
@section('head-title')
    <title>Register</title>
@endsection

@section('head-description')
    <meta name="description" content="Register">
@endsection

@section('body-content')
    @parent
    <div id="app">
        <section class="hero is-success is-fullheight">
            <div class="hero-body">
                <div class="container has-text-centered">
                    <div class="column is-4 is-offset-4">
                        <h3 class="title has-text-grey">Register</h3>
                        <p class="subtitle has-text-grey">Please Register to have access.</p>
                        <div class="box">
                            <figure class="avatar">
                                <img src="{{asset('/img/ic_launcher_r_round.png')}}" width="128">
                            </figure>
                            <div class="field">
                                <div class="control">
                                    <input class="input is-large" type="text" placeholder="Nama" v-model="f_name" required :disabled="is_process" autofocus="true">
                                </div>
                            </div>
                            <div class="field">
                                <div class="control">
                                    <input class="input is-large" type="email" placeholder="Email" v-model="f_email" required :disabled="is_process">
                                </div>
                            </div>
                            <div class="field">
                                <div class="control">
                                    <input class="input is-large" type="password" placeholder="Password" v-model="f_password" required :disabled="is_process">
                                </div>
                            </div>
                            <button class="button is-block is-info is-large is-fullwidth" @click="doRegister" :disabled="is_process">Daftar</button>
                        </div>
                        <p class="has-text-grey">
                            <a href="{{route('login')}}">Sudah Punya Akun, Masuk ?</a>
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('head-css-post')
    @parent
    <link rel="stylesheet" href="{{asset('/css/layout/auth/register/auth_register_bulma.min.css')}}">
@endsection

@section('body-js-lower-post')
    @parent
    <script type="text/javascript" src="{{asset('/js/layout/auth/register/auth_register_bulma.min.js')}}"></script>
@endsection

@push('additional-firebase-library')
    <script type="text/javascript" src="{{asset('/vendor/firebase/firebase-database.min.js')}}"></script>
@endpush
