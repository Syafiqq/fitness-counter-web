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
                                            <p class="help is-info">BMI = Berat Badan : (Tinggi Badan Berdiri)</p>
                                        </div>
                                    </div>
                                </article>
                                <br>
                                <br>
                                <article class="message">
                                    <div class="message-header">
                                        <p class="message-title">Postur dan Anggota Gerak</p>
                                    </div>
                                    <div class="message-body">
                                        <div class="field">
                                            <label class="label">Postur</label>
                                            <div class="control">
                                                <label class="radio">
                                                    <input id="posture-1" type="radio" value="Normal" v-model="processed.pVal.medical.posture">
                                                    Normal
                                                </label>
                                                <br>
                                                <label class="radio">
                                                    <input id="posture-2" type="radio" value="Skoliosis" v-model="processed.pVal.medical.posture">
                                                    Skoliosis
                                                </label>
                                                <br>
                                                <label class="radio">
                                                    <input id="posture-3" type="radio" value="Kifosis" v-model="processed.pVal.medical.posture">
                                                    Kifosis
                                                </label>
                                                <br>
                                                <label class="radio">
                                                    <input id="posture-4" type="radio" value="Lordosis" v-model="processed.pVal.medical.posture">
                                                    Lordosis
                                                </label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label class="label">Anggota gerak :</label>
                                            <div class="control">
                                                <label class="radio">
                                                    <input id="gait-1" type="radio" value="Normal" v-model="processed.pVal.medical.gait">
                                                    Normal
                                                </label>
                                                <br>
                                                <label class="radio">
                                                    <input id="gait-2" type="radio" value="Deformitas" v-model="processed.pVal.medical.gait">
                                                    Deformitas
                                                </label>
                                                <br>
                                                <label class="radio">
                                                    <input id="gait-3" type="radio" value="Kelemahan" v-model="processed.pVal.medical.gait">
                                                    Kelemahan
                                                </label>
                                                <br>
                                                <label class="radio">
                                                    <input id="gait-4" type="radio" value="Kelainan Gait" v-model="processed.pVal.medical.gait">
                                                    Kelainan Gait
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                                <br>
                                <br>
                                <article class="message">
                                    <div class="message-header">
                                        <p class="message-title">Fungsi Kardiovaskular *)</p>
                                    </div>
                                    <div class="message-body">
                                        <div class="field">
                                            <label class="label">Denyut nadi istirahat : </label>
                                            <div class="field has-addons">
                                                <p class="control is-expanded">
                                                    <input type="text" class="input" id="medical-pulse" v-model="processed.pVal.medical.pulse"><br>
                                                </p>
                                                <p class="control">
                                                    <a class="button is-static">
                                                        x/ menit (normal < 100)
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label class="label">Tekanan darah S/D : </label>
                                            <div class="field has-addons">
                                                <p class="control is-expanded">
                                                    <input type="text" class="input" id="medical-pressure-mm" v-model="processed.pVal.medical.pressure.mm">
                                                </p>
                                                <p class="control">
                                                    <a class="button is-static">
                                                        mm
                                                    </a>
                                                </p>
                                                <p class="control is-expanded">
                                                    <input type="text" class="input" id="medical-pressure-hg" v-model="processed.pVal.medical.pressure.hg">
                                                </p>
                                                <p class="control">
                                                    <a class="button is-static">
                                                        Hg
                                                    </a>
                                                </p>
                                            </div>
                                            <p class="help is-info">(normal S: 70 – 90 mmHg, D: 110 – 130)</p>
                                        </div>
                                        <div class="field">
                                            <label class="label">Ictus Cordis : </label>
                                            <div class="control">
                                                <label class="radio">
                                                    <input id="ictus-1" type="radio" value="+" v-model="processed.pVal.medical.ictus">
                                                    +
                                                </label>
                                                <label class="radio">
                                                    <input id="ictus-2" type="radio" value="–" v-model="processed.pVal.medical.ictus">
                                                    –
                                                </label>
                                            </div>
                                            <p class="help is-info">(pilih salah satu)</p>
                                        </div>
                                        <div class="field">
                                            <label class="label">Suara Jantung : </label>
                                            <div class="control">
                                                <label class="radio">
                                                    <input id="heart-1" type="radio" value="Normal" v-model="processed.pVal.medical.heart">
                                                    Normal
                                                </label>
                                                <label class="radio">
                                                    <input id="heart-2" type="radio" value="Obstruksi" v-model="processed.pVal.medical.heart">
                                                    Obstruksi
                                                </label>
                                            </div>
                                            <p class="help is-info">(pilih salah satu)</p>
                                        </div>
                                    </div>
                                </article>
                                <br>
                                <br>
                                <article class="message">
                                    <div class="message-header">
                                        <p class="message-title">Fungsi Pernapasan *)</p>
                                    </div>
                                    <div class="message-body">
                                        <div class="field">
                                            <label class="label">Frekuensi pernapasan : </label>
                                            <div class="field has-addons">
                                                <p class="control is-expanded">
                                                    <input type="text" class="input" id="medical-frequency" v-model="processed.pVal.medical.frequency">
                                                </p>
                                                <p class="control">
                                                    <a class="button is-static">
                                                        x/ menit (normal: 18 – 24)
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label class="label">Tanda retraksi : </label>
                                            <div class="control">
                                                <label class="radio">
                                                    <input id="retraction-1" type="radio" value="+" v-model="processed.pVal.medical.retraction">
                                                    +
                                                </label>
                                                <label class="radio">
                                                    <input id="retraction-2" type="radio" value="–" v-model="processed.pVal.medical.retraction">
                                                    –
                                                </label>
                                            </div>
                                            <p class="help is-info">(pilih salah satu)</p>
                                        </div>
                                        <div class="field" v-if="processed.pVal.medical.retraction === '+'">
                                            <label class="label">Lokasi retraksi : </label>
                                            <div class="field">
                                                <p class="control is-expanded">
                                                    <input type="text" class="input" id="medical-r_location" v-model="processed.pVal.medical.r_location"><br>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label class="label">Suara napas : </label>
                                            <div class="control">
                                                <label class="radio">
                                                    <input id="breath-1" type="radio" value="Normal" v-model="processed.pVal.medical.breath">
                                                    Normal
                                                </label>
                                                <label class="radio">
                                                    <input id="breath-2" type="radio" value="Tidak" v-model="processed.pVal.medical.breath">
                                                    Tidak
                                                </label>
                                            </div>
                                            <p class="help is-info">(pilih salah satu)</p>
                                        </div>
                                        <div class="field">
                                            <label class="label">Saluran napas : </label>
                                            <div class="control">
                                                <label class="radio">
                                                    <input id="b_pipeline-1" type="radio" value="Normal" v-model="processed.pVal.medical.b_pipeline">
                                                    Normal
                                                </label>
                                                <label class="radio">
                                                    <input id="b_pipeline-2" type="radio" value="Obstruksi" v-model="processed.pVal.medical.b_pipeline">
                                                    Obstruksi
                                                </label>
                                            </div>
                                            <p class="help is-info">(pilih salah satu)</p>
                                        </div>
                                    </div>
                                </article>
                                <br>
                                <br>
                                <article class="message">
                                    <div class="message-header">
                                        <p class="message-title">Indera dan Verbal</p>
                                    </div>
                                    <div class="message-body">
                                        <div class="field">
                                            <label class="label">Mata (penglihatan) :</label>
                                            <div class="control">
                                                <label class="radio">
                                                    <input id="vision-1" type="radio" value="Normal" v-model="processed.pVal.medical.vision">
                                                    Normal
                                                </label>
                                                <br>
                                                <label class="radio">
                                                    <input id="vision-2" type="radio" value="Juling" v-model="processed.pVal.medical.vision">
                                                    Juling
                                                </label>
                                                <br>
                                                <label class="radio">
                                                    <input id="vision-3" type="radio" value="Plus / Minus / Silinder" v-model="processed.pVal.medical.vision">
                                                    Plus / Minus / Silinder
                                                </label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label class="label">Telinga (pendengaran) : </label>
                                            <div class="control">
                                                <label class="radio">
                                                    <input id="hearing-1" type="radio" value="Normal" v-model="processed.pVal.medical.hearing">
                                                    Normal
                                                </label>
                                                <br>
                                                <label class="radio">
                                                    <input id="hearing-2" type="radio" value="Tuli" v-model="processed.pVal.medical.hearing">
                                                    Tuli
                                                </label>
                                                <br>
                                                <label class="radio">
                                                    <input id="hearing-3" type="radio" value="Serumen Obstruktif" v-model="processed.pVal.medical.hearing">
                                                    Serumen Obstruktif
                                                </label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label class="label">Verbal : </label>
                                            <div class="control">
                                                <label class="radio">
                                                    <input id="verbal-1" type="radio" value="Normal" v-model="processed.pVal.medical.verbal">
                                                    Normal
                                                </label>
                                                <br>
                                                <label class="radio">
                                                    <input id="verbal-2" type="radio" value="Latah / Gagap" v-model="processed.pVal.medical.verbal">
                                                    Latah / Gagap
                                                </label>
                                                <br>
                                                <label class="radio">
                                                    <input id="verbal-3" type="radio" value="Tuna Wicara" v-model="processed.pVal.medical.verbal">
                                                    Tuna Wicara
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                                <br>
                                <br>
                                <article class="message">
                                    <div class="message-header">
                                        <p class="message-title">Kesimpulan</p>
                                    </div>
                                    <div class="message-body">
                                        <div class="field">
                                            <label class="label">Setelah mempertimbangkan hasil pemeriksaan, yang bersangkutan dinyatakan :</label>
                                            <div class="control">
                                                <label class="radio">
                                                    <input id="conclusion-1" type="radio" value="Disarankan" v-model="processed.pVal.medical.conclusion">
                                                    Disarankan
                                                </label>
                                                <br>
                                                <label class="radio">
                                                    <input id="conclusion-2" type="radio" value="Tidak Disarankan" v-model="processed.pVal.medical.conclusion">
                                                    Tidak Disarankan
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            </div>
                            <div class="field is-grouped is-grouped-right" style="margin-top: 20px;">
                                <p class="control">
                                    <a @click="saveChanges" class="button is-primary">
                                        Simpan
                                    </a>
                                </p>
                                <p class="control">
                                    <a @click="$modal.hide('editable-modal')" class="button is-light">
                                        Cancel
                                    </a>
                                </p>
                            </div>
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
