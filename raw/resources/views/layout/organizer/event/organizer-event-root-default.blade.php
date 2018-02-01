@extends('root.root-authenticated-theme-default')

@section('head-meta')
    @parent
    <meta name="event" content="{!! $meta['event'] !!}">
@endsection
