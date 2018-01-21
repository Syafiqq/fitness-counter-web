<?php
/** @var \Collective\Html\FormBuilder $form */
$form = \Collective\Html\FormFacade::getFacadeRoot();
?>
        <!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="{{asset('/css/layout/auth/login/auth_login_default.min.css')}}">
</head>
<body>
@if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong>Whoops!</strong>
        There were some problems with your input.
        <br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
{!! $form->open(['route' => 'auth.login.post', 'method' => 'post']) !!}
{!! nl2br(PHP_EOL) !!}
{!! $form->email('email', 'syafiq.rezpector@gmail.com', ['placeholder' => 'Email', 'required'=> true]) !!}
{!! nl2br(PHP_EOL) !!}
{!! $form->input('password', 'password', 'password', ['value' => 'password','placeholder' => 'Password', 'required'=> true]) !!}
{!! nl2br(PHP_EOL) !!}
{!! $form->button('Submit', ['type' => 'Submit']) !!}
{!! $form->close() !!}
<script src="{{asset('js/layout/auth/login/auth_login_default.min.js')}}"></script>
</body>
</html>
