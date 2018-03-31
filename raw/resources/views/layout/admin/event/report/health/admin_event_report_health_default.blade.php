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
                <input type="text" id="medical-tbb" v-model="processed.pVal.medical.tbb"> cm<br>
                Tinggi Badan Duduk (TBD) : <br>
                <input type="text" id="medical-tbd" v-model="processed.pVal.medical.tbd"> cm<br>
                Rasio (TBB –TBD) / TBD : <br>
                @{{ratioEvaluator}}<br>
                (normal antara 0,95 - 0,99) <br>
                Berat Badan : <br>
                <input type="text" id="medical-weight" v-model="processed.pVal.medical.weight"> kg<br>
                BMI <br>
                @{{bmiEvaluator}} kg/m<sup>2</sup><br>
                BMI = Berat Badan : (Tinggi Badan Berdiri)<sup>2</sup><br>
                (normal antara 18 – 24,9)<br>

                <h3>Postur dan Anggota Gerak</h3>
                Postur : <br>
                <input id="posture-1" type="radio" value="Normal" v-model="processed.pVal.medical.posture">
                <label for="posture-1">Normal</label>
                <br>
                <input id="posture-2" type="radio" value="Skoliosis" v-model="processed.pVal.medical.posture">
                <label for="posture-2">Skoliosis</label>
                <br>
                <input id="posture-3" type="radio" value="Kifosis" v-model="processed.pVal.medical.posture">
                <label for="posture-3">Kifosis</label>
                <br>
                <input id="posture-4" type="radio" value="Lordosis" v-model="processed.pVal.medical.posture">
                <label for="posture-4">Lordosis</label>
                <br>

                Anggota gerak : <br>
                <input id="gait-1" type="radio" value="Normal" v-model="processed.pVal.medical.gait">
                <label for="gait-1">Normal</label>
                <br>
                <input id="gait-2" type="radio" value="Deformitas" v-model="processed.pVal.medical.gait">
                <label for="gait-2">Deformitas</label>
                <br>
                <input id="gait-3" type="radio" value="Kelemahan" v-model="processed.pVal.medical.gait">
                <label for="gait-3">Kelemahan</label>
                <br>
                <input id="gait-4" type="radio" value="Kelainan Gait" v-model="processed.pVal.medical.gait">
                <label for="gait-4">Kelainan Gait</label>
                <br>

                <h3>Fungsi Kardiovaskular *)</h3>
                Denyut nadi istirahat : <br>
                <input type="text" id="medical-pulse" v-model="processed.pVal.medical.pulse"> x/ menit (normal < 100)<br>
                Tekanan darah S/D : <br>
                <input type="text" id="medical-pressure-mm" v-model="processed.pVal.medical.pressure.mm"> mm
                <input type="text" id="medical-pressure-hg" v-model="processed.pVal.medical.pressure.hg"> hg<br>
                (normal S: 70 – 90 mmHg, D: 110 – 130)<br>
                Ictus Cordis : <br>
                <input id="ictus-1" type="radio" value="+" v-model="processed.pVal.medical.ictus">
                <label for="ictus-1">+</label>
                <input id="ictus-2" type="radio" value="–" v-model="processed.pVal.medical.ictus">
                <label for="ictus-2">–</label>
                (pilih salah satu)<br>
                Suara Jantung : <br>
                <input id="heart-1" type="radio" value="Normal" v-model="processed.pVal.medical.heart">
                <label for="heart-1">Normal</label>
                <input id="heart-2" type="radio" value="Obstruksi" v-model="processed.pVal.medical.heart">
                <label for="heart-2">Obstruksi</label>
                (pilih salah satu)<br>

                <h3>Fungsi Pernapasan *)</h3>
                Frekuensi pernapasan : <br>
                <input type="text" id="medical-frequency" v-model="processed.pVal.medical.frequency"> x/ menit (normal: 18 – 24)<br>
                Tanda retraksi : <br>
                <input id="retraction-1" type="radio" value="+" v-model="processed.pVal.medical.retraction">
                <label for="retraction-1">+</label>
                <input id="retraction-2" type="radio" value="–" v-model="processed.pVal.medical.retraction">
                <label for="retraction-2">–</label>
                (pilih salah satu)<br>
                Lokasi retraksi : <br>
                <input type="text" id="medical-r_location" v-model="processed.pVal.medical.r_location"><br>
                Suara napas : <br>
                <input id="breath-1" type="radio" value="Normal" v-model="processed.pVal.medical.breath">
                <label for="breath-1">Normal</label>
                <input id="breath-2" type="radio" value="Tidak" v-model="processed.pVal.medical.breath">
                <label for="breath-2">Tidak</label>
                (pilih salah satu)<br>
                Saluran napas : <br>
                <input id="b_pipeline-1" type="radio" value="Normal" v-model="processed.pVal.medical.b_pipeline">
                <label for="b_pipeline-1">Normal</label>
                <input id="b_pipeline-2" type="radio" value="Obstruksi" v-model="processed.pVal.medical.b_pipeline">
                <label for="b_pipeline-2">Obstruksi</label>
                (pilih salah satu)<br>

                <h3>Indera dan Verbal</h3>
                Mata (penglihatan) : <br>
                <input id="vision-1" type="radio" value="Normal" v-model="processed.pVal.medical.vision">
                <label for="vision-1">Normal</label>
                <br>
                <input id="vision-2" type="radio" value="Juling" v-model="processed.pVal.medical.vision">
                <label for="vision-2">Juling</label>
                <br>
                <input id="vision-3" type="radio" value="Plus / Minus / Silinder" v-model="processed.pVal.medical.vision">
                <label for="vision-3">Plus / Minus / Silinder</label>
                <br>

                Telinga (pendengaran) : <br>
                <input id="hearing-1" type="radio" value="Normal" v-model="processed.pVal.medical.hearing">
                <label for="hearing-1">Normal</label>
                <br>
                <input id="hearing-2" type="radio" value="Tuli" v-model="processed.pVal.medical.hearing">
                <label for="hearing-2">Tuli</label>
                <br>
                <input id="hearing-3" type="radio" value="Serumen Obstruktif" v-model="processed.pVal.medical.hearing">
                <label for="hearing-3">Serumen Obstruktif</label>
                <br>

                Verbal : <br>
                <input id="verbal-1" type="radio" value="Normal" v-model="processed.pVal.medical.verbal">
                <label for="verbal-1">Normal</label>
                <br>
                <input id="verbal-2" type="radio" value="Latah / Gagap" v-model="processed.pVal.medical.verbal">
                <label for="verbal-2">Latah / Gagap</label>
                <br>
                <input id="verbal-3" type="radio" value="Tuna Wicara" v-model="processed.pVal.medical.verbal">
                <label for="verbal-3">Tuna Wicara</label>
                <br>

                <h3>Kesimpulan</h3>
                Setelah mempertimbangkan hasil pemeriksaan, yang bersangkutan dinyatakan :<br>
                <input id="conclusion-1" type="radio" value="Disarankan" v-model="processed.pVal.medical.conclusion">
                <label for="conclusion-1">Disarankan</label>
                <br>
                <input id="conclusion-2" type="radio" value="Tidak Disarankan" v-model="processed.pVal.medical.conclusion">
                <label for="conclusion-2">Tidak Disarankan</label>
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
        <button @click="downloadReportList">Download Laporan Keputusan</button>
        <button @click="downloadReportBunch">Download Laporan Individu</button>
        <hr>
        <v-client-table :data="queues" :columns="qt_columns" :options="qt_options">
            <button slot="action" slot-scope="props" @click="editParticipant(props.row)">Edit</button>
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
