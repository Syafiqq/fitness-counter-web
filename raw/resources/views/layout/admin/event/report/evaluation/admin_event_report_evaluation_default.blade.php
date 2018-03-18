@extends('layout.admin.event.admin_event_root_default')
@section('head-title')
    <title>Evaluation Report</title>
@endsection

@section('head-description')
    <meta name="description" content="Evaluation Report">
@endsection

@section('body-content')
    @parent
    <div id="app">
        <button @click="calculateScore">Hitung Skor</button>
        <hr>
        <table>
            <thead>
            <tr>
                <th>Singkatan</th>
                <th>Keterangan</th>
            </tr>
            </thead>
            <tbody>
            {{-- @formatter:off --}}
            <tr><td>I</td>  <td>Illinois</td>                 </tr>
            <tr><td>PU</td> <td>Push Up</td>                  </tr>
            <tr><td>R</td>  <td>Run 1600m</td>                </tr>
            <tr><td>SU</td> <td>Sit Up</td>                   </tr>
            <tr><td>T</td>  <td>Lempar tangkap bola</td>      </tr>
            <tr><td>VJ</td> <td>Vertical Jump</td>            </tr>
            {{-- @formatter:on --}}
            </tbody>
        </table>
        <v-client-table :data="queues" :columns="qt_columns" :options="qt_options"></v-client-table>
    </div>
@endsection

@section('head-css-post')
    @parent
    <link rel="stylesheet" href="{{asset('/css/layout/admin/event/report/evaluation/admin_event_report_evaluation_default.min.css')}}">
@endsection

@section('body-js-lower-post')
    @parent
    <script type="text/javascript" src="{{asset('/js/model/firebase/TesterEvaluator.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('/js/layout/admin/event/report/evaluation/admin_event_report_evaluation_default.min.js')}}"></script>
@endsection
