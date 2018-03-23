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
        <modal name="hello-world" height="auto" :scrollable="true">
            {{-- @formatter:off --}}
            <h3>Illinois</h3>
            <label for="il-start">Mulai : </label><datetime v-model="processed.pVal.illinois.start" value-zone="Asia/Jakarta" zone="Asia/Jakarta" type="datetime"></datetime>
            Waktu tempuh : <br>
            <input type="text" id="il-elapsed-m" v-model="processed.pVal.illinois.elapsed.m"> <label for="il-elapsed-m">menit</label>
            <input type="text" id="il-elapsed-s" v-model="processed.pVal.illinois.elapsed.s"> <label for="il-elapsed-s">detik</label>
            <input type="text" id="il-elapsed-SSS" v-model="processed.pVal.illinois.elapsed.SSS"> <label for="il-elapsed-SSS">milidetik</label><br>
            Hasil : <br> @{{editIllinoisEvaluator}}
            <br>
            <h3>Push Up</h3>
            <label for="push-start">Mulai : </label><datetime v-model="processed.pVal.push.start" value-zone="Asia/Jakarta" zone="Asia/Jakarta" type="datetime"></datetime>
            Total : <br>
            <input type="text" id="push-counter" v-model="processed.pVal.push.counter"><br>
            Hasil : <br> @{{editPushEvaluator}}
            <br>
            <button @click="saveChanges">Simpan</button>
            {{-- @formatter:on --}}
        </modal>
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
        <v-client-table :data="queues" :columns="qt_columns" :options="qt_options">
            <button slot="edit" slot-scope="props" @click="editParticipant(props.row)">Edit</button>
        </v-client-table>
    </div>
@endsection

@section('head-css-post')
    @parent
    <link rel="stylesheet" href="{{asset('/css/layout/admin/event/report/evaluation/admin_event_report_evaluation_default.min.css')}}">
    {{--<link rel="stylesheet" href="{{asset('/vendor/vue2-timepicker/dist/vue2-timepicker.min.css')}}">--}}
@endsection

@section('body-js-lower-post')
    @parent
    {{--<script type="text/javascript" src="{{asset('/vendor/vue2-timepicker/dist/vue2-timepicker.min.js')}}"></script>--}}
    <script type="text/javascript" src="{{asset('/js/model/firebase/TesterEvaluator.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('/js/layout/admin/event/report/evaluation/admin_event_report_evaluation_default.min.js')}}"></script>
@endsection
