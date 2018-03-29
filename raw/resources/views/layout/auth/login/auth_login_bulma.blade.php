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
    <section class="hero is-success is-fullheight">
        <div class="hero-body">
            <div class="container has-text-centered">
                <div class="column is-4 is-offset-4">
                    <h3 class="title has-text-grey">Login</h3>
                    <p class="subtitle has-text-grey">Please login to proceed.</p>
                    <div class="box">
                        <figure class="avatar">
                            <img src="{{asset('/img/logo-sbmptn.png')}}" width="128">
                        </figure>
                        {!! $form->open(['route' => 'auth.login.post', 'method' => 'post']) !!}
                        <div class="field">
                            <div class="control">
                                {!! $form->email('email', 'syafiq.rezpector@gmail.com', ['class' => 'input is-large', 'placeholder' => 'Email', 'required'=> true, 'autofocus' =>true]) !!}
                            </div>
                        </div>

                        <div class="field">
                            <div class="control">
                                {!! $form->input('password', 'password', 'password', ['class' => 'input is-large','value' => 'password','placeholder' => 'Password', 'required'=> true]) !!}
                            </div>
                        </div>
                        {!! $form->button('Submit', ['type' => 'Submit', 'class' =>'button is-block is-info is-large is-fullwidth']) !!}
                        {!! $form->close() !!}
                    </div>
                    <p class="has-text-grey">
                        <a href="{{route('register')}}">Belum Punya Akun, Daftarkan ?</a>
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection


@section('head-css-pre')
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
    @parent
@endsection

@section('head-css-post')
    @parent
    <link rel="stylesheet" href="{{asset('/css/layout/auth/login/auth_login_bulma.min.css')}}">
@endsection

@section('body-js-lower-post')
    @parent
    <script type="text/javascript" src="{{asset('js/layout/auth/login/auth_login_bulma.min.js')}}"></script>
@endsection

