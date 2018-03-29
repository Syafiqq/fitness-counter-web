@extends('root.root-authenticated-theme-bulma')

@push('pre-add-auth-header-menu-start')
    {!! link_to_route(\Illuminate\Support\Facades\Auth::user()->getRole().'.event.overview', 'Overview', [$meta['event']], ['class' => 'navbar-item']) !!}
    <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link" href="javascript:void(0)">
            Managemen User
        </a>
        <div class="navbar-dropdown is-boxed">
            {!! link_to_route(\Illuminate\Support\Facades\Auth::user()->getRole().'.event.management.registrar', 'Managemen Registrar', [$meta['event']], ['class' => 'navbar-item']) !!}
            {!! link_to_route(\Illuminate\Support\Facades\Auth::user()->getRole().'.event.management.tester', 'Managemen Tester', [$meta['event']], ['class' => 'navbar-item']) !!}
        </div>
    </div>
    <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link" href="javascript:void(0)">
            Pelaporan
        </a>
        <div class="navbar-dropdown is-boxed">
            {!! link_to_route(\Illuminate\Support\Facades\Auth::user()->getRole().'.event.report.evaluation', 'Report Penilaian', [$meta['event']], ['class' => 'navbar-item']) !!}
        </div>
    </div>
@endpush

@section('head-meta')
    @parent
    @include('layout.common.event.event_meta')
@endsection

@push('additional-firebase-library')
    <script type="text/javascript" src="{{asset('/vendor/firebase/firebase-database.min.js')}}"></script>
@endpush
