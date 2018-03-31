@extends('layout.admin.event.admin_event_root_bulma')
@section('head-title')
    <title>Health Report</title>
@endsection

@section('head-description')
    <meta name="description" content="Health Report">
@endsection

@section('body-content')
    @parent
    <div id="app">
        <modal name="editable-modal" :adaptive="true"
               :max-width="1000"
               :max-height="600"
               width="75%"
               height="auto" :click-to-close="false" style="overflow-y: scroll!important; padding: 50px 0;">
            {{-- @formatter:off --}}
            <div style="margin: 20px">
                <div class="container">
                    <div class="columns">
                        <div class="column is-10">
                            <div class="buttons has-addons is-right">
                                <span class="button" @click="$modal.hide('editable-modal')">
                                    <span class="icon">
                                        <i class="fa fa-window-close" aria-hidden="true"></i>
                                    </span>
                                </span>
                            </div>
                            <div v-if="processed.pVal.participant.show">
                                <article class="message">
                                    <div class="message-header">
                                        <p class="message-title">Fisik</p>
                                    </div>
                                    <div class="message-body">
                                        <div class="field">
                                            <label class="label">Kemiripan Wajah</label>
                                            <div class="control">
                                                <label class="radio">
                                                    <input type="radio" id="one" value="1" v-model="processed.pVal.participant.same">
                                                    Mirip
                                                </label>
                                                <label class="radio">
                                                    <input type="radio" id="two" value="0" v-model="processed.pVal.participant.same">
                                                    Tidak Mirip
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            </div>
                            <div v-if="processed.pVal.medical.show">
                                <br>
                                <br>
                                <article class="message">
                                    <div class="message-header">
                                        <p class="message-title">Antropometri</p>
                                    </div>
                                    <div class="message-body">
                                        <div class="field">
                                            <label class="label">Tinggi Badan Berdiri (TBB) :</label>
                                            <div class="field has-addons">
                                                 <p class="control is-expanded">
                                                    <input class="input" type="text" id="medical-tbb" v-model="processed.pVal.medical.tbb">
                                                </p>
                                                <p class="control">
                                                    <a class="button is-static">
                                                        cm
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label class="label">Tinggi Badan Duduk (TBD) :</label>
                                            <div class="field has-addons">
                                                 <p class="control is-expanded">
                                                    <input class="input" type="text" id="medical-tbd" v-model="processed.pVal.medical.tbd">
                                                </p>
                                                <p class="control">
                                                    <a class="button is-static">
                                                        cm
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label class="label">Rasio (TBB –TBD) / TBD :</label>
                                            <div class="field has-addons">
                                                 <p class="control is-expanded">
                                                    <input class="input" type="text" id="medical-tbd" v-model="ratioEvaluator" disabled>
                                                </p>
                                                <p class="control">
                                                    <a class="button is-static">
                                                        (normal antara 0,95 - 0,99)
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="field">
                                            <label class="label">Berat Badan : </label>
                                            <div class="field has-addons">
                                                 <p class="control is-expanded">
                                                    <input class="input" type="text" id="medical-weight" v-model="processed.pVal.medical.weight">
                                                </p>
                                                <p class="control">
                                                    <a class="button is-static">
                                                        kg
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label class="label">BMI</label>
                                            <div class="field has-addons">
                                                 <p class="control is-expanded">
                                                    <input class="input" type="text" id="medical-tbd" v-model="bmiEvaluator" disabled>
                                                </p>
                                                <p class="control">
                                                    <a class="button is-static">
                                                        (normal antara 18 – 24,9)
                                                    </a>
                                                </p>
                                            </div>
                                            BMI = Berat Badan : (Tinggi Badan Berdiri)
                                        </div>
                                    </div>
                                </article>
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
                        </div>
                    </div>
                </div>
            </div>
        </modal>
        <div class="container-fluid">
            <div class="columns">
                <div class="column is-12">
                    <div class="card events-card">
                        <header class="card-header">
                            <p class="card-header-title">
                                Laporan Evaluasi
                            </p>
                        </header>
                        <div class="card-table is-fullheight" style="height: 900px; max-height: 900px!important;">
                            <div class="content" style="margin: 20px">
                                <v-client-table :data="queues" :columns="qt_columns" :options="qt_options">
                                    <div slot="action" slot-scope="props">
                                        <a @click="editParticipant(props.row)" href="javascript:void(0)" class="is-success">
                                            <span class="icon"><i class="fa fa-edit"></i></span>
                                        </a>
                                        <a @click="downloadReportOnce(props.row)" href="javascript:void(0)" class="is-info">
                                            <span class="icon"><i class="fa fa-download"></i></span>
                                        </a>
                                    </div>
                                </v-client-table>
                            </div>
                        </div>
                        <footer class="card-footer">
                            <a @click="downloadReportList" href="javascript:void(0)" class="card-footer-item is-success">
                                <span class="icon"><i class="fa fa-download"></i></span>
                                Laporan Kesimpulan
                            </a>
                            <a @click="downloadReportBunch" href="javascript:void(0)" class="card-footer-item is-info">
                                <span class="icon"><i class="fa fa-download"></i></span>
                                Laporan Individu
                            </a>
                        </footer>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('head-css-post')
    @parent
    <link rel="stylesheet" href="{{asset('/css/layout/admin/event/report/health/admin_event_report_health_bulma.min.css')}}">
@endsection

@section('body-js-lower-post')
    @parent
    <script type="text/javascript" src="{{asset('/js/model/firebase/TesterEvaluator.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('/js/layout/admin/event/report/health/admin_event_report_health_bulma.min.js')}}"></script>
@endsection
