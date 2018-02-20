@extends('root.root-firebase-default')
<?php
/** @var \Collective\Html\FormBuilder $form */
$form = \Collective\Html\FormFacade::getFacadeRoot();
?>
@section('head-title')
    <title>Login</title>
@endsection

@section('head-description')
    <meta name="description" content="Login">
@endsection

@section('body-content')
    @parent
    <div id="app">
        <input type="text" placeholder="Nama" v-model="f_name" required :disabled="is_process">
        <br>
        <input type="email" placeholder="Email" v-model="f_email" required :disabled="is_process">
        <br>
        <input type="password" placeholder="Password" v-model="f_password" required :disabled="is_process">
        <br>
        <button @click="doRegister" :disabled="is_process">Daftar</button>
    </div>
@endsection

@section('head-css-post')
    @parent
    <link rel="stylesheet" href="{{asset('/css/layout/auth/register/auth_register_default.min.css')}}">
@endsection

@section('body-js-lower-post')
    @parent
    <script type="text/javascript" src="{{asset('/js/layout/auth/register/auth_register_default.min.js')}}"></script>
@endsection

