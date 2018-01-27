@extends('root.root-theme-default')
@section('head-description')
    @parent
    <meta name="home" content="{!! url('/') !!}">
    @auth
        <meta name="user-role" content="{!! \Illuminate\Support\Facades\Auth::user()->getRole() !!}">
    @endauth
@endsection
@section('body-content')
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
