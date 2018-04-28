@extends('root.root-authenticated-theme-bulma')

@section('head-meta')
    @parent
    @include('layout.common.event.event_meta')
@endsection
@section('body-js-lower-post')
    @parent
    @auth
        <script type="text/javascript" src="{{asset('/js/layout/registrar/event/registrar_event_root_bulma.min.js')}}"></script>
    @endauth
@endsection
