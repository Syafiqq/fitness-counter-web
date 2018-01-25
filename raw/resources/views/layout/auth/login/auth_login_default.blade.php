@extends('root.root-theme-default')
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
    {!! $form->open(['route' => 'auth.login.post', 'method' => 'post']) !!}
    {!! nl2br(PHP_EOL) !!}
    {!! $form->email('email', 'syafiq.rezpector@gmail.com', ['placeholder' => 'Email', 'required'=> true]) !!}
    {!! nl2br(PHP_EOL) !!}
    {!! $form->input('password', 'password', 'password', ['value' => 'password','placeholder' => 'Password', 'required'=> true]) !!}
    {!! nl2br(PHP_EOL) !!}
    {!! $form->button('Submit', ['type' => 'Submit']) !!}
    {!! $form->close() !!}
@endsection

@section('head-css-post')
    @parent
    <link rel="stylesheet" href="{{asset('/css/layout/auth/login/auth_login_default.min.css')}}">
@endsection

@section('body-js-lower-post')
    @parent
    <script type="text/javascript" src="{{asset('js/layout/auth/login/auth_login_default.min.js')}}"></script>
@endsection

