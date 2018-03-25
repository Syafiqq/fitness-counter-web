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
        <?php
        $group = strtolower(\Illuminate\Support\Facades\Auth::user()->getRole());
        $roles = \Illuminate\Support\Facades\App::call(\App\Helper\UserHelper::class . "::getUserRole", [\Illuminate\Support\Facades\Auth::user()]);
        unset($roles['tester']);
        foreach ($roles as $krole => &$role)
        {
            $role = ucfirst($krole);
        }
        ?>
        <nav class="navbar is-white">
            <div class="container">
                <div class="navbar-brand">
                    <a class="navbar-item brand-text" href="{{route("$group.dashboard.home")}}">
                        <strong>SBMPTN</strong>
                    </a>
                    <div class="navbar-burger burger" data-target="navMenu">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
                <div id="navMenu" class="navbar-menu">
                    <div class="navbar-start">
                        @stack('pre-add-auth-header-menu-start')
                    </div>
                    <div class="navbar-end">
                        @stack('pre-add-auth-header-menu-end')
                        @if(count($roles) > 1)
                            <div class="navbar-item has-dropdown is-hoverable">
                                <a class="navbar-link" href="javascript:void(0)">
                                    Peran
                                </a>
                                <div class="navbar-dropdown is-boxed">
                                    @foreach ($roles as $krole => &$role)
                                        <a class="navbar-item" href="{{route('auth.switch.role', [$krole])}}">
                                            {{$role}}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        {!! link_to_route('logout', 'Logout', [], ['class' => 'navbar-item', 'style'=> 'margin-left:10px;']) !!}
                    </div>
                </div>
            </div>
        </nav>
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
@endsection
