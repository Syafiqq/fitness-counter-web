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
        <modal name="editable-modal" :adaptive="true"
               :max-width="1000"
               :max-height="600"
               width="75%"
               height="auto" :click-to-close="false" style="overflow-y: scroll!important; padding: 50px 0;">
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
                            <div v-if="processed.pVal.illinois.show">
                                <br>
                                <br>
                                <article class="message">
                                    <div class="message-header">
                                        <p class="message-title">Illinois</p>
                                    </div>
                                    <div class="message-body">
                                        <div class="field">
                                            <label class="label">Mulai :</label>
                                            <div class="control">
                                                <datetime input-class="input" v-model="processed.pVal.illinois.start" value-zone="Asia/Jakarta" zone="Asia/Jakarta" type="datetime"></datetime>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label class="label">Waktu Tempuh :</label>
                                            <div class="field has-addons">
                                                <p class="control">
                                                    <input type="text" class="input" id="il-elapsed-m" v-model="processed.pVal.illinois.elapsed.m">
                                                </p>
                                                <p class="control">
                                                    <a class="button is-static">
                                                        menit
                                                    </a>
                                                </p>
                                                <p class="control">
                                                    <input type="text" class="input" id="il-elapsed-s" v-model="processed.pVal.illinois.elapsed.s">
                                                </p>
                                                <p class="control">
                                                    <a class="button is-static">
                                                        detik
                                                    </a>
                                                </p>
                                                <p class="control">
                                                    <input type="text" class="input" id="il-elapsed-SSS" v-model="processed.pVal.illinois.elapsed.SSS">
                                                </p>
                                                <p class="control">
                                                    <a class="button is-static">
                                                        milidetik
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <a class="button is-static">
                                                Hasil : @{{editIllinoisEvaluator}}
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                            <div v-if="processed.pVal.push.show">
                                <br>
                                <br>
                                <article class="message">
                                    <div class="message-header">
                                        <p class="message-title">Push Up</p>
                                    </div>
                                    <div class="message-body">
                                        <div class="field">
                                            <label class="label">Mulai :</label>
                                            <div class="control">
                                                <datetime input-class="input" v-model="processed.pVal.push.start" value-zone="Asia/Jakarta" zone="Asia/Jakarta" type="datetime"></datetime>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label class="label">Total :</label>
                                            <div class="control">
                                                <input class="input" type="text" id="push-counter" v-model="processed.pVal.push.counter">
                                            </div>
                                        </div>
                                        <div class="field">
                                            <a class="button is-static">
                                                Hasil : @{{editPushEvaluator}}
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                            <div v-if="processed.pVal.run.show">
                                <br>
                                <br>
                                <article class="message">
                                    <div class="message-header">
                                        <p class="message-title">Lari 1600 m</p>
                                    </div>
                                    <div class="message-body">
                                        <div class="field">
                                            <label class="label">Mulai :</label>
                                            <div class="control">
                                                <datetime input-class="input" v-model="processed.pVal.run.start" value-zone="Asia/Jakarta" zone="Asia/Jakarta" type="datetime"></datetime>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label class="label">Waktu Tempuh :</label>
                                            <div class="field has-addons">
                                                <p class="control">
                                                    <input type="text" class="input" id="run-elapsed-m" v-model="processed.pVal.run.elapsed.m">
                                                </p>
                                                <p class="control">
                                                    <a class="button is-static">
                                                        menit
                                                    </a>
                                                </p>
                                                <p class="control">
                                                    <input type="text" class="input" id="run-elapsed-s" v-model="processed.pVal.run.elapsed.s">
                                                </p>
                                                <p class="control">
                                                    <a class="button is-static">
                                                        detik
                                                    </a>
                                                </p>
                                                <p class="control">
                                                    <input type="text" class="input" id="run-elapsed-SSS" v-model="processed.pVal.run.elapsed.SSS">
                                                </p>
                                                <p class="control">
                                                    <a class="button is-static">
                                                        milidetik
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <a class="button is-static">
                                                Hasil : @{{editRunEvaluator}}
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                            <div v-if="processed.pVal.sit.show">
                                <article class="message">
                                    <div class="message-header">
                                        <p class="message-title">Sit Up</p>
                                    </div>
                                    <div class="message-body">
                                        <div class="field">
                                            <label class="label">Mulai :</label>
                                            <div class="control">
                                                <datetime input-class="input" v-model="processed.pVal.sit.start" value-zone="Asia/Jakarta" zone="Asia/Jakarta" type="datetime"></datetime>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label class="label">Total :</label>
                                            <div class="control">
                                                <input class="input" type="text" id="sit-counter" v-model="processed.pVal.sit.counter">
                                            </div>
                                        </div>
                                        <div class="field">
                                            <a class="button is-static">
                                                Hasil : @{{editSitEvaluator}}
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                            <div v-if="processed.pVal.throwing.show">
                                <br>
                                <br>
                                <article class="message">
                                    <div class="message-header">
                                        <p class="message-title">Lempar Tangkap Bola</p>
                                    </div>
                                    <div class="message-body">
                                        <div class="field">
                                            <label class="label">Mulai :</label>
                                            <div class="control">
                                                <datetime input-class="input" v-model="processed.pVal.throwing.start" value-zone="Asia/Jakarta" zone="Asia/Jakarta" type="datetime"></datetime>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label class="label">Total :</label>
                                            <div class="control">
                                                <input class="input" type="text" id="throwing-counter" v-model="processed.pVal.throwing.counter">
                                            </div>
                                        </div>
                                        <div class="field">
                                            <a class="button is-static">
                                                Hasil : @{{editThrowingEvaluator}}
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                            <div v-if="processed.pVal.vertical.show">
                                <br>
                                <br>
                                <article class="message">
                                    <div class="message-header">
                                        <p class="message-title">Vertical Jump</p>
                                    </div>
                                    <div class="message-body">
                                        <div class="field">
                                            <label class="label">Awalan :</label>
                                            <div class="control">
                                                <input type="text" class="input" id="vertical-counter" v-model="processed.pVal.vertical.initial">
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label class="label">Percobaan 1 :</label>
                                            <div class="control">
                                                <input type="text" class="input" id="vertical-counter" v-model="processed.pVal.vertical.try1">
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label class="label">Percobaan 2 :</label>
                                            <div class="control">
                                                <input type="text" class="input" id="vertical-counter" v-model="processed.pVal.vertical.try2">
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label class="label">Percobaan 3 :</label>
                                            <div class="control">
                                                <input type="text" class="input" id="vertical-counter" v-model="processed.pVal.vertical.try3">
                                            </div>
                                        </div>
                                        <div class="field">
                                            <a class="button is-static">
                                                Selisih : @{{editVerticalDeviator}}
                                            </a>
                                        </div>
                                        <div class="field">
                                            <a class="button is-static">
                                                Hasil : @{{editVerticalEvaluator}}
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                            <br>
                            <br>
                            <div class="field is-grouped is-grouped-right">
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
                            <a @click="calculateScore" href="javascript:void(0)" class="card-header-icon" aria-label="more options">
                                Hitung
                                <span class="icon">
                                <i class="fa fa-calculator" aria-hidden="true"></i>
                              </span>
                            </a>
                        </header>
                        <div class="card-table is-fullheight" style="height: 900px; max-height: 900px!important;">
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
