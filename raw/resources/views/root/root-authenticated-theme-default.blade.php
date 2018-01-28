@extends('root.root-theme-default')
@section('head-description')
    @parent
    <meta name="home" content="{!! url('/') !!}">
    @auth
        <meta name="user-role" content="{!! \Illuminate\Support\Facades\Auth::user()->getRole() !!}">
    @endauth
@endsection
@section('body-content')
    <div id="loader-wrapper">
        <div id="loader"></div>
    </div>
    @parent
    @auth
        <?php
        $roles = \Illuminate\Support\Facades\App::call(\App\Helper\UserHelper::class . "::getUserRole", [\Illuminate\Support\Facades\Auth::user()]);
        foreach ($roles as $krole => &$role)
        {
            $role = ucfirst($krole);
        }
        ?>
        {!! \Collective\Html\FormFacade::select('role', $roles, \Illuminate\Support\Facades\Auth::user()->getRole(), ['id' => 'role-changer']) !!}
        {!! link_to_route('logout', 'Logout', [], ['style'=> 'margin-left:10px;']) !!}
    @endauth

    <hr>
@endsection
@section('head-css-pre')
    <style type="text/css">
        #loader-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1000;
        }

        #loader {
            display: block;
            position: relative;
            left: 50%;
            top: 50%;
            width: 150px;
            height: 150px;
            margin: -75px 0 0 -75px;
            border-radius: 50%;
            border: 3px solid transparent;
            border-top-color: #3498db;
            -webkit-animation: spin 2s linear infinite; /* Chrome, Opera 15+, Safari 5+ */
            animation: spin 2s linear infinite; /* Chrome, Firefox 16+, IE 10+, Opera */
        }

        #loader:before {
            content: "";
            position: absolute;
            top: 5px;
            left: 5px;
            right: 5px;
            bottom: 5px;
            border-radius: 50%;
            border: 3px solid transparent;
            border-top-color: #e74c3c;
            -webkit-animation: spin 3s linear infinite; /* Chrome, Opera 15+, Safari 5+ */
            animation: spin 3s linear infinite; /* Chrome, Firefox 16+, IE 10+, Opera */
        }

        #loader:after {
            content: "";
            position: absolute;
            top: 15px;
            left: 15px;
            right: 15px;
            bottom: 15px;
            border-radius: 50%;
            border: 3px solid transparent;
            border-top-color: #f9c922;
            -webkit-animation: spin 1.5s linear infinite; /* Chrome, Opera 15+, Safari 5+ */
            animation: spin 1.5s linear infinite; /* Chrome, Firefox 16+, IE 10+, Opera */
        }

        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg); /* Chrome, Opera 15+, Safari 3.1+ */
                -ms-transform: rotate(0deg); /* IE 9 */
                transform: rotate(0deg); /* Firefox 16+, IE 10+, Opera */
            }
            100% {
                -webkit-transform: rotate(360deg); /* Chrome, Opera 15+, Safari 3.1+ */
                -ms-transform: rotate(360deg); /* IE 9 */
                transform: rotate(360deg); /* Firefox 16+, IE 10+, Opera */
            }
        }

        @keyframes spin {
            0% {
                -webkit-transform: rotate(0deg); /* Chrome, Opera 15+, Safari 3.1+ */
                -ms-transform: rotate(0deg); /* IE 9 */
                transform: rotate(0deg); /* Firefox 16+, IE 10+, Opera */
            }
            100% {
                -webkit-transform: rotate(360deg); /* Chrome, Opera 15+, Safari 3.1+ */
                -ms-transform: rotate(360deg); /* IE 9 */
                transform: rotate(360deg); /* Firefox 16+, IE 10+, Opera */
            }
        }
    </style>
@endsection
@section('head-js-post')
    @parent
    @auth
        <script type="text/javascript" src="{{asset('/vendor/firebase/firebase-app.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('/vendor/firebase/firebase-auth.min.js')}}"></script>
        @stack('additional-firebase-library')
        <script type="text/javascript">
            firebase.initializeApp({
                apiKey: "AIzaSyD_xXi_xZo25ASGgFODWv9av5lLLPHRWeg",
                authDomain: "fitness-counter-research.firebaseapp.com",
                databaseURL: "https://fitness-counter-research.firebaseio.com",
                projectId: "fitness-counter-research",
                storageBucket: "fitness-counter-research.appspot.com",
                messagingSenderId: "691550840999"
            });
            firebase.auth().signInWithCustomToken("{!! \Illuminate\Support\Facades\Auth::user()->getToken()['token'] !!}").catch(function (error) {
                console.log(error.code, error.message);
            });
        </script>
    @endauth
@endsection
@section('body-js-lower-post')
    @parent
    <script type="text/javascript" src="{{asset('/js/common/authenticated_theme_default.min.js')}}"></script>
@endsection
