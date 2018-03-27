@extends('layout.admin.event.admin_event_root_bulma')
@section('head-title')
    <title>Evaluation Report</title>
@endsection

@section('head-description')
    <meta name="description" content="Evaluation Report">
@endsection

@section('body-content')
    @parent
    <div id="app">
        <modal name="editable-modal" height="auto" :width="480" :click-to-close="false" style="overflow-y: scroll!important;">
            <div>
                <div v-if="processed.pVal.participant.show">
                    <h3>Fisik</h3>
                    Kemiripan Wajah :
                    <br>
                    <input type="radio" id="one" value="1" v-model="processed.pVal.participant.same">
                    <label for="one">Mirip</label>
                    <br>
                    <input type="radio" id="two" value="0" v-model="processed.pVal.participant.same">
                    <label for="two">Tidak Mirip</label>
                    <br>
                </div>
                <div v-if="processed.pVal.illinois.show">
                    <h3>Illinois</h3>
                    <label for="il-start">Mulai :</label>
                    <datetime v-model="processed.pVal.illinois.start" value-zone="Asia/Jakarta" zone="Asia/Jakarta" type="datetime"></datetime>
                    Waktu tempuh :
                    <br>
                    <input type="text" id="il-elapsed-m" v-model="processed.pVal.illinois.elapsed.m">
                    <label for="il-elapsed-m">menit</label>
                    <input type="text" id="il-elapsed-s" v-model="processed.pVal.illinois.elapsed.s">
                    <label for="il-elapsed-s">detik</label>
                    <input type="text" id="il-elapsed-SSS" v-model="processed.pVal.illinois.elapsed.SSS">
                    <label for="il-elapsed-SSS">milidetik</label>
                    <br>
                    Hasil :
                    <br>
                    @{{editIllinoisEvaluator}}
                    <br>
                </div>
                <div v-if="processed.pVal.push.show">
                    <h3>Push Up</h3>
                    <label for="push-start">Mulai :</label>
                    <datetime v-model="processed.pVal.push.start" value-zone="Asia/Jakarta" zone="Asia/Jakarta" type="datetime"></datetime>
                    Total :
                    <br>
                    <input type="text" id="push-counter" v-model="processed.pVal.push.counter">
                    <br>
                    Hasil :
                    <br>
                    @{{editPushEvaluator}}
                    <br>
                </div>
                <div v-if="processed.pVal.run.show">
                    <h3>Lari 1600 m</h3>
                    <label for="run-start">Mulai :</label>
                    <datetime v-model="processed.pVal.run.start" value-zone="Asia/Jakarta" zone="Asia/Jakarta" type="datetime"></datetime>
                    Waktu tempuh :
                    <br>
                    <input type="text" id="run-elapsed-m" v-model="processed.pVal.run.elapsed.m">
                    <label for="run-elapsed-m">menit</label>
                    <input type="text" id="run-elapsed-s" v-model="processed.pVal.run.elapsed.s">
                    <label for="run-elapsed-s">detik</label>
                    <input type="text" id="run-elapsed-SSS" v-model="processed.pVal.run.elapsed.SSS">
                    <label for="run-elapsed-SSS">milidetik</label>
                    <br>
                    Hasil :
                    <br>
                    @{{editRunEvaluator}}
                    <br>
                </div>
                <div v-if="processed.pVal.sit.show">
                    <h3>Sit Up</h3>
                    <label for="sit-start">Mulai :</label>
                    <datetime v-model="processed.pVal.sit.start" value-zone="Asia/Jakarta" zone="Asia/Jakarta" type="datetime"></datetime>
                    Total :
                    <br>
                    <input type="text" id="sit-counter" v-model="processed.pVal.sit.counter">
                    <br>
                    Hasil :
                    <br>
                    @{{editSitEvaluator}}
                    <br>
                </div>
                <div v-if="processed.pVal.throwing.show">
                    <h3>Lempar Tangkap Bola</h3>
                    <label for="throwing-start">Mulai :</label>
                    <datetime v-model="processed.pVal.throwing.start" value-zone="Asia/Jakarta" zone="Asia/Jakarta" type="datetime"></datetime>
                    Total :
                    <br>
                    <input type="text" id="throwing-counter" v-model="processed.pVal.throwing.counter">
                    <br>
                    Hasil :
                    <br>
                    @{{editThrowingEvaluator}}
                    <br>
                </div>
                <div v-if="processed.pVal.vertical.show">
                    <h3>Vertical Jump</h3>
                    Awalan :
                    <br>
                    <input type="text" id="vertical-counter" v-model="processed.pVal.vertical.initial">
                    <br>
                    Percobaan 1 :
                    <br>
                    <input type="text" id="vertical-counter" v-model="processed.pVal.vertical.try1">
                    <br>
                    Percobaan 2 :
                    <br>
                    <input type="text" id="vertical-counter" v-model="processed.pVal.vertical.try2">
                    <br>
                    Percobaan 3 :
                    <br>
                    <input type="text" id="vertical-counter" v-model="processed.pVal.vertical.try3">
                    <br>
                    Selisih :
                    <br>
                    @{{editVerticalDeviator}}
                    <br>
                    Hasil :
                    <br>
                    @{{editVerticalEvaluator}}
                    <br>
                </div>
                <button @click="saveChanges">Simpan</button>
                <button @click="$modal.hide('editable-modal')">Cancel</button>
                <div slot="top-right">
                    <button @click="$modal.hide('editable-modal')">
                        X
                    </button>
                </div>
            </div>
        </modal>
        <v-dialog name="dialog"></v-dialog>
        <div class="container">
            <div class="columns">
                <div class="column is-12">
                    <div class="card events-card">
                        <header class="card-header">
                            <p class="card-header-title">
                                Laporan Evaluasi
                            </p>
                            <a @click="calculateScore" href="javascript:void(0)" class="card-header-icon" aria-label="more options">
                                Hitung
                                <span class="icon">
                                <i class="fa fa-calculator" aria-hidden="true"></i>
                              </span>
                            </a>
                        </header>
                        <div class="card-table is-fullheight" style="height: 600px; max-height: 600px!important;">
                            <div class="content" style="margin: 20px">
                                <v-client-table :data="queues" :columns="qt_columns" :options="qt_options">
                                    <a @click="editParticipant(props.row)" slot="edit" slot-scope="props" href="javascript:void(0)" class="is-success">
                                        <span class="icon"><i class="fa fa-edit"></i></span>
                                    </a>
                                </v-client-table>
                            </div>
                        </div>
                        <footer class="card-footer">
                            <a @click="downloadReport" href="javascript:void(0)" class="card-footer-item is-success">
                                <span class="icon"><i class="fa fa-download"></i></span>
                                Download Laporan
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
    <link rel="stylesheet" href="{{asset('/css/layout/admin/event/report/evaluation/admin_event_report_evaluation_bulma.min.css')}}">
@endsection

@section('body-js-lower-post')
    @parent
    <script type="text/javascript" src="{{asset('/js/model/firebase/TesterEvaluator.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('/js/layout/admin/event/report/evaluation/admin_event_report_evaluation_bulma.min.js')}}"></script>
@endsection
