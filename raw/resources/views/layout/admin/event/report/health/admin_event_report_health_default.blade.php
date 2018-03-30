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
            <div v-if="processed.pVal.medical.show">
                <h3>Antropometri</h3>
                Tinggi Badan Berdiri (TBB) : <br>
                <input type="text" id="sit-counter" v-model="processed.pVal.medical.tbb"> cm<br>
                Tinggi Badan Duduk (TBD) : <br>
                <input type="text" id="sit-counter" v-model="processed.pVal.medical.tbd"> cm<br>
                Rasio (TBB –TBD) / TBD : <br>
                @{{ratioEvaluator}}<br>
                (normal antara 0,95 - 0,99) <br>
                Berat Badan : <br>
                <input type="text" id="sit-counter" v-model="processed.pVal.medical.weight"> kg<br>
                BMI <br>
                @{{bmiEvaluator}} kg/m<sup>2</sup><br>
                BMI = Berat Badan : (Tinggi Badan Berdiri)<sup>2</sup><br>
                (normal antara 18 – 24,9)<br>
                {{--<label for="il-start">Mulai : </label><datetime v-model="processed.pVal.illinois.start" value-zone="Asia/Jakarta" zone="Asia/Jakarta" type="datetime"></datetime>
                Waktu tempuh : <br>
                <input type="text" id="il-elapsed-m" v-model="processed.pVal.illinois.elapsed.m"> <label for="il-elapsed-m">menit</label>
                <input type="text" id="il-elapsed-s" v-model="processed.pVal.illinois.elapsed.s"> <label for="il-elapsed-s">detik</label>
                <input type="text" id="il-elapsed-SSS" v-model="processed.pVal.illinois.elapsed.SSS"> <label for="il-elapsed-SSS">milidetik</label><br>
                Hasil : <br> @{{editIllinoisEvaluator}}
                <br>--}}
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
