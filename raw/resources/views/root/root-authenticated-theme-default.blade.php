@extends('root.root-theme-default')
@section('body-content')
    @parent
    @if(\Illuminate\Support\Facades\Auth::check())
        {!! link_to_route('logout', 'Logout') !!}
    @endif

    <hr>
@endsection
