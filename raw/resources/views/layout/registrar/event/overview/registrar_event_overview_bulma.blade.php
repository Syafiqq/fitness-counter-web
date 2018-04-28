@extends('layout.registrar.event.registrar_event_root_bulma')
@section('head-title')
    <title>Overview</title>
@endsection

@section('head-description')
    <meta name="description" content="Overview">
@endsection

@section('body-authenticated-navbar')
@endsection
@section('body-content')
    @parent
    <div id="app">
        <?php
        $group = strtolower(\Illuminate\Support\Facades\Auth::user()->getRole());
        $roles = \Illuminate\Support\Facades\App::call(\App\Helper\UserHelper::class . "::getUserRole", [\Illuminate\Support\Facades\Auth::user()]);
        unset($roles['tester']);
        foreach ($roles as $krole => &$role)
        {
            $role = ucfirst($krole);
        }
        ?>
        <section class="hero is-info is-fullheight">
            <div class="hero-head">
                <nav class="navbar is-info">
                    <div class="container">
                        <div class="navbar-brand">
                            <a class="navbar-item brand-text" href="{{route("$group.dashboard.home")}}">
                                <strong id="navbar-title">@yield('body-navbar-title')</strong>
                            </a>
                            <div id="burger" class="navbar-burger burger" data-target="navMenu">
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
                                                <a class="navbar-item is-black" href="{{route('auth.switch.role', [$krole])}}">
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
            </div>
            <div class="hero-body">
                <div class="container has-text-centered">
                    <div class="column is-6 is-offset-3">
                        <div class="box">
                            <div class="field">
                                <div class="control">
                                    <label class="radio" style="vertical-align: center">
                                        <input type="radio" id="one" value="1" v-model="f_same">
                                        Wajah Mirip
                                    </label>
                                    <label class="radio" style="vertical-align: center">
                                        <input type="radio" id="two" value="0" v-model="f_same">
                                        Wajah Tidak Mirip
                                    </label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="control">
                                    <label class="radio" style="vertical-align: center">
                                        <input type="radio" id="three" value="l" v-model="f_gender">
                                        Laki - Laki
                                    </label>
                                    <label class="radio" style="vertical-align: center">
                                        <input type="radio" id="four" value="p" v-model="f_gender">
                                        Perempuan
                                    </label>
                                </div>
                            </div>
                            <div class="field is-grouped">
                                <p class="control is-expanded">
                                    <input @keyup.enter="openModal" class="input" type="text " placeholder="No. SBMPTN" v-model="f_participant" required :disabled="is_process">
                                </p>
                                <p class="control">
                                    <a @click="openModal" :disabled="is_process" class="button is-info">
                                        Daftarkan
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('head-css-post')
    @parent
    <link rel="stylesheet" href="{{asset('/css/layout/registrar/event/overview/registrar_event_overview_bulma.min.css')}}">
@endsection

@section('body-js-lower-post')
    @parent
    <script type="text/javascript" src="{{asset('/js/layout/registrar/event/overview/registrar_event_overview_bulma.min.js')}}"></script>
@endsection

@push('additional-firebase-library')
    <script type="text/javascript" src="{{asset('/vendor/firebase/firebase-database.min.js')}}"></script>
@endpush
