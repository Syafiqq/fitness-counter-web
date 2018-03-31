@extends('root.root-authenticated-theme-default')

@push('pre-add-auth-header-menu')
    {!! link_to_route(\Illuminate\Support\Facades\Auth::user()->getRole().'.event.overview', 'Overview', [$meta['event']], []) !!}
    {!! link_to_route(\Illuminate\Support\Facades\Auth::user()->getRole().'.event.management.registrar', 'Management Registrar', [$meta['event']], []) !!}
    {!! link_to_route(\Illuminate\Support\Facades\Auth::user()->getRole().'.event.management.tester', 'Management Tester', [$meta['event']], []) !!}
    {!! link_to_route(\Illuminate\Support\Facades\Auth::user()->getRole().'.event.report.evaluation', 'Report Penilaian', [$meta['event']], []) !!}
    {!! link_to_route(\Illuminate\Support\Facades\Auth::user()->getRole().'.event.report.health', 'Report Kesehatan', [$meta['event']], []) !!}
@endpush

@section('head-meta')
    @parent
    @include('layout.common.event.event_meta')
@endsection

@push('additional-firebase-library')
    <script type="text/javascript" src="{{asset('/vendor/firebase/firebase-database.min.js')}}"></script>
@endpush
