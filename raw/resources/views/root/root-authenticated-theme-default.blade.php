@extends('root.root-theme-default')
@section('body-content')
    @parent
    @if(\Illuminate\Support\Facades\Auth::check())
        <?php
        $roles = \Illuminate\Support\Facades\App::call(\App\Helper\UserHelper::class . "::getUserRole", [\Illuminate\Support\Facades\Auth::user()]);
        foreach ($roles as $krole => &$role)
        {
            $role = ucfirst($krole);
        }
        ?>
        {!! \Collective\Html\FormFacade::select('role', $roles, \Illuminate\Support\Facades\Auth::user()->getRole(), ['id' => 'role-changer']) !!}
        {!! link_to_route('logout', 'Logout', [], ['style'=> 'margin-left:10px;']) !!}
    @endif

    <hr>
@endsection

@section('body-js-lower-post')
    @parent
    <script type="text/javascript" src="{{asset('/js/common/authenticated_theme_default.min.js')}}"></script>
@endsection

