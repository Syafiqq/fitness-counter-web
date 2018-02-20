@extends('root.root-firebase-default')
@section('head-meta')
    @parent
    @auth
        <meta name="user-role" content="{!! \Illuminate\Support\Facades\Auth::user()->getRole() !!}">
    @endauth
@endsection
@section('body-content')
    @include('layout.common.preloader.common_preloader_default')
    @parent
    @auth
        @stack('pre-add-auth-header-menu')
        <?php
        $roles = \Illuminate\Support\Facades\App::call(\App\Helper\UserHelper::class . "::getUserRole", [\Illuminate\Support\Facades\Auth::user()]);
        foreach ($roles as $krole => &$role)
        {
            $role = ucfirst($krole);
        }
        ?>
        {!! \Collective\Html\FormFacade::select('role', $roles, \Illuminate\Support\Facades\Auth::user()->getRole(), ['id' => 'role-changer']) !!}
        {!! link_to_route('logout', 'Logout', [], ['style'=> 'margin-left:10px;']) !!}
        <hr>
    @endauth
@endsection
@section('head-css-pre')
    <link rel="stylesheet" href="{{asset('/css/layout/common/preloader/common_preloader_default.min.css')}}">
    @parent
@endsection
@section('body-js-lower-post')
    @parent
    @auth
        <script type="text/javascript">
            firebase.auth().signInWithCustomToken("{!! \Illuminate\Support\Facades\Auth::user()->getToken()['token'] !!}").catch(function (error) {
                console.log(error.code, error.message);
            });
        </script>
    @endauth
    <script type="text/javascript" src="{{asset('/js/common/authenticated_theme_default.min.js')}}"></script>
@endsection
