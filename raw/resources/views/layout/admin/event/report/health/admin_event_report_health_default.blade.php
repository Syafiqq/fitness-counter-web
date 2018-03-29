@extends('layout.admin.event.admin_event_root_default')
@section('head-title')
    <title>Health Report</title>
@endsection

@section('head-description')
    <meta name="description" content="Health Report">
@endsection

@section('body-content')
    @parent
    <div id="app">
        <modal name="editable-modal" height="auto" :scrollable="true" :max-height="600" :click-to-close="false">
            {{-- @formatter:off --}}
            <div v-if="processed.pVal.participant.show">
                <h3>Fisik</h3>
                Kemiripan Wajah : <br>
                <input type="radio" id="one" value="1" v-model="processed.pVal.participant.same">
                <label for="one">Mirip</label>
                <br>
                <input type="radio" id="two" value="0" v-model="processed.pVal.participant.same">
                <label for="two">Tidak Mirip</label>
                <br>
            </div>
            <div v-if="processed.pVal.illinois.show">
                <h3>Illinois</h3>
                <label for="il-start">Mulai : </label><datetime v-model="processed.pVal.illinois.start" value-zone="Asia/Jakarta" zone="Asia/Jakarta" type="datetime"></datetime>
                Waktu tempuh : <br>
                <input type="text" id="il-elapsed-m" v-model="processed.pVal.illinois.elapsed.m"> <label for="il-elapsed-m">menit</label>
                <input type="text" id="il-elapsed-s" v-model="processed.pVal.illinois.elapsed.s"> <label for="il-elapsed-s">detik</label>
                <input type="text" id="il-elapsed-SSS" v-model="processed.pVal.illinois.elapsed.SSS"> <label for="il-elapsed-SSS">milidetik</label><br>
                Hasil : <br> @{{editIllinoisEvaluator}}
                <br>
            </div>
            <div v-if="processed.pVal.push.show">
                <h3>Push Up</h3>
                <label for="push-start">Mulai : </label><datetime v-model="processed.pVal.push.start" value-zone="Asia/Jakarta" zone="Asia/Jakarta" type="datetime"></datetime>
                Total : <br>
                <input type="text" id="push-counter" v-model="processed.pVal.push.counter"><br>
                Hasil : <br> @{{editPushEvaluator}}
                <br>
            </div>
            <div v-if="processed.pVal.run.show">
                <h3>Lari 1600 m</h3>
                <label for="run-start">Mulai : </label><datetime v-model="processed.pVal.run.start" value-zone="Asia/Jakarta" zone="Asia/Jakarta" type="datetime"></datetime>
                Waktu tempuh : <br>
                <input type="text" id="run-elapsed-m" v-model="processed.pVal.run.elapsed.m"> <label for="run-elapsed-m">menit</label>
                <input type="text" id="run-elapsed-s" v-model="processed.pVal.run.elapsed.s"> <label for="run-elapsed-s">detik</label>
                <input type="text" id="run-elapsed-SSS" v-model="processed.pVal.run.elapsed.SSS"> <label for="run-elapsed-SSS">milidetik</label><br>
                Hasil : <br> @{{editRunEvaluator}}
                <br>
            </div>
            <div v-if="processed.pVal.sit.show">
                <h3>Sit Up</h3>
                <label for="sit-start">Mulai : </label><datetime v-model="processed.pVal.sit.start" value-zone="Asia/Jakarta" zone="Asia/Jakarta" type="datetime"></datetime>
                Total : <br>
                <input type="text" id="sit-counter" v-model="processed.pVal.sit.counter"><br>
                Hasil : <br> @{{editSitEvaluator}}
                <br>
            </div>
            <div v-if="processed.pVal.throwing.show">
                <h3>Lempar Tangkap Bola</h3>
                <label for="throwing-start">Mulai : </label><datetime v-model="processed.pVal.throwing.start" value-zone="Asia/Jakarta" zone="Asia/Jakarta" type="datetime"></datetime>
                Total : <br>
                <input type="text" id="throwing-counter" v-model="processed.pVal.throwing.counter"><br>
                Hasil : <br> @{{editThrowingEvaluator}}
                <br>
            </div>
            <div v-if="processed.pVal.vertical.show">
                <h3>Vertical Jump</h3>
                Awalan : <br>
                <input type="text" id="vertical-counter" v-model="processed.pVal.vertical.initial"><br>
                Percobaan 1 : <br>
                <input type="text" id="vertical-counter" v-model="processed.pVal.vertical.try1"><br>
                Percobaan 2 : <br>
                <input type="text" id="vertical-counter" v-model="processed.pVal.vertical.try2"><br>
                Percobaan 3 : <br>
                <input type="text" id="vertical-counter" v-model="processed.pVal.vertical.try3"><br>
                Selisih : <br> @{{editVerticalDeviator}}
                <br>
                Hasil : <br> @{{editVerticalEvaluator}}
                <br>
            </div>
            <button @click="saveChanges">Simpan</button>
            <button @click="$modal.hide('editable-modal')">Cancel</button>
            {{-- @formatter:on --}}
            <div slot="top-right">
                <button @click="$modal.hide('editable-modal')">
                    X
                </button>
            </div>
        </modal>
        <button @click="downloadReport">Download Laporan</button>
        <hr>
        <v-client-table :data="queues" :columns="qt_columns" :options="qt_options">
            <button slot="edit" slot-scope="props" @click="editParticipant(props.row)">Edit</button>
        </v-client-table>
    </div>
@endsection

@section('head-css-post')
    @parent
    <link rel="stylesheet" href="{{asset('/css/layout/admin/event/report/health/admin_event_report_health_default.min.css')}}">
@endsection

@section('body-js-lower-post')
    @parent
    <script type="text/javascript" src="{{asset('/js/model/firebase/TesterEvaluator.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('/js/layout/admin/event/report/health/admin_event_report_health_default.min.js')}}"></script>
@endsection
