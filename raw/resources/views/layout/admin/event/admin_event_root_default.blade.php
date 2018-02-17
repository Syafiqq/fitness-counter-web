@extends('root.root-authenticated-theme-default')

@push('pre-add-auth-header-menu')
    {!! link_to_route(\Illuminate\Support\Facades\Auth::user()->getRole().'.event.overview', 'Overview', [$meta['event']], []) !!}
@endpush

@section('head-meta')
    @parent
    @include('layout.common.event.event_meta')
@endsection
