@extends('root.root-theme-default')
<?php
/** @var \Collective\Html\FormBuilder $form */
$form = \Collective\Html\FormFacade::getFacadeRoot();
?>
@section('head-title')
    <title>SBMPTN</title>
@endsection

@section('head-description')
    <meta name="description" content="Landing Page">
@endsection

@section('head-css-pre')
    @parent
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
@endsection

@section('body-content')
    @parent
    <section class="hero is-info is-fullheight">
        <div class="hero-head">
            <nav class="navbar">
                <div class="container">
                    <div class="navbar-brand">
                        <a class="navbar-item" href="{{ url('/') }}">
                            <img src="{{asset('/img/logo-sbmptn.png')}}" alt="Logo">
                        </a>
                        <span class="navbar-burger burger" data-target="navbarMenu">
                          <span></span>
                          <span></span>
                          <span></span>
                        </span>
                    </div>
                    <div id="navbarMenu" class="navbar-menu">
                        <div class="navbar-end">
                            @if (\Illuminate\Support\Facades\Route::has('login'))
                                <div class="top-right links">
                                    @auth
                                        <span class="navbar-item">
                                            <a class="button is-white is-outlined" href="{{ url('/'.\Illuminate\Support\Facades\Auth::user()->getRole().'/home') }}">
                                              <span class="icon">
                                                <i class="fa fa-home"></i>
                                              </span>
                                              <span>Home</span>
                                            </a>
                                        </span>
                                    @else
                                        <span class="navbar-item">
                                            <a class="button is-white is-outlined" href="{{ route('login') }}">
                                              <span class="icon">
                                                <i class="fa fa-key"></i>
                                              </span>
                                              <span>Login</span>
                                            </a>
                                        </span>
                                    @endauth
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </nav>
        </div>
        <div class="hero-body">
            <div class="container has-text-centered">
                <div class="column is-6 is-offset-3">
                    <h1 class="title">
                        SBMPTN
                    </h1>
                    <h2 class="subtitle">
                        Sistem perekaman data ujian praktik Olahraga SBMPTN
                    </h2>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('head-css-post')
    @parent
    <link rel="stylesheet" href="{{asset('/css/layout/welcome.min.css')}}">
@endsection

@section('body-js-lower-post')
    @parent
    <script type="text/javascript" src="{{asset('/js/layout/welcome.min.js')}}"></script>
@endsection

