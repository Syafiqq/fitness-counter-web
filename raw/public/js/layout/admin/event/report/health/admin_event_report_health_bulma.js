(function ($) {
    $(function () {
        moment.locale('id');
        var app = new Vue({
            el: '#app',
            data: {
                home: $('meta[name=home]').attr("content"),
                event: $('meta[name=event]').attr("content"),
                role: $('meta[name=user-role]').attr("content"),
                preset: undefined,
                is_process: true,
                processed: {
                    aVal: {'pgd': 'p'},
                    oVal: {},
                    pVal: {
                        participant: {show: false, same: 0},
                        medical: {
                            show: false,
                            tbb: null, tbd: null, ratio: 0, weight: null, bmi: 0,
                            posture: null, gait: null,
                            pulse: null, pressure: {mm: null, hg: null}, ictus: null, heart: null,
                            frequency: null, retraction: null, r_location: null, breath: null, b_pipeline: null,
                            vision: null, hearing: null, verbal: null,
                            conclusion: null
                        }
                    }
                },
                vault: {},
                qt_columns: ['pno', 'pnm', 'pfs', 'abmi', 'ppos', 'kvh', 'pbr', 'vma', 'cco', 'action'],
                queues: [],
                qt_options: {
                    uniqueKey: 'pno',
                    headings: {
                        pno: 'No',
                        pnm: 'Nama',
                        pfs: 'Wajah',
                        abmi: 'BMI',
                        ppos: 'Postur',
                        kvh: 'Jantung',
                        pbr: 'Pernapasan',
                        vma: 'Mata',
                        cco: 'Kesimpulan',
                        action: 'Aksi',
                    },
                    sortable: ['pno', 'pnm', 'pfs', 'abmi', 'ppos', 'kvh', 'pbr', 'vma', 'cco'],
                }
            },
            computed: {
                ratioEvaluator: function () {
                    var process = this.processed.pVal.medical;
                    var result  = calculateRatio(process.tbb, process.tbd);
                    return result == null ? null : toNumber(result, 2);
                },
                bmiEvaluator: function () {
                    var process = this.processed.pVal.medical;
                    var result  = calculateBmi(process.weight, process.tbb);
                    return result == null ? null : toNumber(result, 2);
                },
            },
            methods: {
                saveChanges: function () {
                    var that = this;
                    this.$swal({
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        title: 'Tunggu Sebentar',
                        onOpen: function () {
                            that.$swal.showLoading();
                            return new Promise(function (resolve) {
                                saveEdit(app.processed.pVal, app.processed.oVal);
                                filterQueue(app.processed.oVal, app.processed.aVal);
                                var key      = DataMapper.PresetQueue(app.preset, app.processed.aVal['pdk'], app.processed.aVal['pqu'])['presets'];
                                var query    = {};
                                query[key]   = app.processed.oVal;
                                var callback = firebase.database().ref().update(query);
                                if (callback != null && typeof (callback) !== 'boolean')
                                {
                                    callback.then(function (result) {
                                        console.log(result);
                                        app.is_process = false;
                                        NProgress.done();
                                        that.$modal.hide('editable-modal');
                                        that.$swal({
                                            type: 'success',
                                            title: 'Perubahan Berhasil',
                                        })
                                    })
                                }
                            })
                        },
                        preConfirm: function () {

                        },
                    }).then(function (result) {
                        console.log("swal result" + result)
                    });
                },
                editParticipant: function (aVal) {
                    this.processed['aVal'] = aVal;
                    this.processed['oVal'] = app.vault[aVal['pdk']][aVal['pqu']];
                    filterEdit(this.processed['oVal'], this.processed['pVal']);
                    if (_.filter(this.processed.pVal, function (o) {
                        return o.show;
                    }).length > 0)
                    {
                        this.$modal.show('editable-modal');
                    }
                },
                downloadReportList: function () {
                    var that = this;
                    NProgress.start();
                    this.$swal({
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        title: 'Tunggu Sebentar',
                        onOpen: function () {
                            that.$swal.showLoading();
                            axios.post(
                                app.home + '/' + app.role + '/event/' + app.event + '/publish/health/list'
                                , {}
                                , {
                                    responseType: 'blob',
                                    headers: {
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json;charset=UTF-8',
                                    }
                                }
                            )
                                .then(function (response) {
                                    console.log(response);
                                    that.$swal.close();
                                    if ('data' in response && 'headers' in response && 'content-disposition' in response.headers && 'content-type' in response.headers)
                                    {
                                        fileDownload(response.data, getFilename(response.headers['content-disposition'], response.headers['content-type']));
                                    }
                                    NProgress.done();

                                })
                                .catch(function (error) {
                                    that.$swal({
                                        type: 'error',
                                        title: 'Pemrosesan Gagal',
                                    });
                                    NProgress.done();
                                });
                        },
                        preConfirm: function () {

                        },
                    }).then(function (result) {
                        console.log("swal result" + result)
                    });
                },
                downloadReportBunch: function () {
                    var that = this;
                    NProgress.start();
                    this.$swal({
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        title: 'Tunggu Sebentar',
                        onOpen: function () {
                            that.$swal.showLoading();
                            axios.post(
                                app.home + '/' + app.role + '/event/' + app.event + '/publish/health/bunch'
                                , {}
                                , {
                                    responseType: 'blob',
                                    headers: {
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json;charset=UTF-8',
                                    }
                                }
                            )
                                .then(function (response) {
                                    console.log(response);
                                    that.$swal.close();
                                    if ('data' in response && 'headers' in response && 'content-disposition' in response.headers && 'content-type' in response.headers)
                                    {
                                        fileDownload(response.data, getFilename(response.headers['content-disposition'], response.headers['content-type']));
                                    }
                                    NProgress.done();

                                })
                                .catch(function (error) {
                                    that.$swal({
                                        type: 'error',
                                        title: 'Pemrosesan Gagal',
                                    });
                                    NProgress.done();
                                });
                        },
                        preConfirm: function () {

                        },
                    }).then(function (result) {
                        console.log("swal result" + result)
                    });
                },
                downloadReportOnce: function (val) {
                    var that = this;
                    if (val.pno != null)
                    {
                        NProgress.start();
                        this.$swal({
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            title: 'Tunggu Sebentar',
                            onOpen: function () {
                                that.$swal.showLoading();
                                axios.post(
                                    app.home + '/' + app.role + '/event/' + app.event + '/publish/health/once'
                                    , {
                                        participant: val.pno,
                                    }
                                    , {
                                        responseType: 'blob',
                                        headers: {
                                            'Accept': 'application/json',
                                            'Content-Type': 'application/json;charset=UTF-8',
                                        }
                                    }
                                )
                                    .then(function (response) {
                                        console.log(response);
                                        that.$swal.close();
                                        if ('data' in response && 'headers' in response && 'content-disposition' in response.headers && 'content-type' in response.headers)
                                        {
                                            fileDownload(response.data, getFilename(response.headers['content-disposition'], response.headers['content-type']));
                                        }
                                        NProgress.done();

                                    })
                                    .catch(function (error) {
                                        that.$swal({
                                            type: 'error',
                                            title: 'Pemrosesan Gagal',
                                        });
                                        NProgress.done();
                                    });
                            },
                            preConfirm: function () {

                            },
                        }).then(function (result) {
                            console.log("swal result" + result)
                        });
                    }
                }
            },

        });

        function saveEdit(queue, result)
        {
            if (result != null)
            {
                var gender = result.participant.gender;
                if ('participant' in queue && queue.participant.show)
                {
                    var process                   = queue.participant;
                    result['participant']['same'] = Number(process.same);
                }
                if ('medical' in queue && queue.medical.show)
                {
                    var process                     = queue.medical;
                    // Anthropometric
                    result['medical']['tbb']        = process.tbb == null ? null : toNumber(process.tbb);
                    result['medical']['tbd']        = process.tbd == null ? null : toNumber(process.tbd);
                    result['medical']['ratio']      = (process.tbb == null || process.tbd == null) ? null : toNumber(calculateRatio(result['medical']['tbb'], result['medical']['tbd']), 2);
                    result['medical']['weight']     = process.weight == null ? null : toNumber(process.weight);
                    result['medical']['bmi']        = (process.weight == null || process.tbb == null) ? null : toNumber(calculateBmi(result['medical']['weight'], result['medical']['tbb']), 2);
                    // Posture and Gait
                    result['medical']['posture']    = process.posture;
                    result['medical']['gait']       = process.gait;
                    // Cardiovascular
                    result['medical']['pulse']      = process.pulse == null ? null : toNumber(process.pulse);
                    result['medical']['pressure']   = (process.pressure.mm == null || process.pressure.hg == null) ? null : (toNumber(process.pressure.mm) + ' / ' + toNumber(process.pressure.hg));
                    result['medical']['ictus']      = process.ictus;
                    result['medical']['heart']      = process.heart;
                    // Respiratory
                    result['medical']['frequency']  = process.frequency == null ? null : toNumber(process.frequency);
                    result['medical']['retraction'] = process.retraction;
                    result['medical']['r_location'] = process.retraction === '+' ? process.r_location : null;
                    result['medical']['breath']     = process.breath;
                    result['medical']['b_pipeline'] = process.b_pipeline;
                    // Verbal
                    result['medical']['vision']     = process.vision;
                    result['medical']['hearing']    = process.hearing;
                    result['medical']['verbal']     = process.verbal;
                    // Conclusion
                    result['medical']['conclusion'] = process.conclusion == null ? null : (process.conclusion === 'Disarankan');
                }
            }

            console.log(result);
            return result;
        }

        function calculateRatio(tbb, tbd)
        {
            return (tbb == null || tbd == null) ? null : ((Number(tbb) - Number(tbd)) / Number(tbd));
        }

        function calculateBmi(weight, tbb)
        {
            return (weight == null || tbb == null) ? null : (weight / Math.pow(tbb / 100, 2));
        }

        function filterEdit(queue, result)
        {
            result = result == null ? {} : result;
            if (result['participant']['show'] = 'participant' in queue)
            {
                var process                   = queue.participant;
                result['participant']['same'] = 'same' in process ? Number(process.same) : null;
            }
            else
            {
                result['participant']['same'] = null;
            }
            if (result['medical']['show'] = 'medical' in queue)
            {
                var process                         = queue.medical;
                // Anthropometric
                result['medical']['tbb']            = 'tbb' in process ? toNumber(process.tbb) : null;
                result['medical']['tbd']            = 'tbd' in process ? toNumber(process.tbd) : null;
                result['medical']['ratio']          = 'ratio' in process ? toNumber(process.ratio, 2) : calculateRatio(result['medical']['tbb'], result['medical']['tbd']);
                result['medical']['weight']         = 'weight' in process ? toNumber(process.weight) : null;
                result['medical']['bmi']            = 'bmi' in process ? toNumber(process.bmi, 2) : calculateBmi(result['medical']['weight'], result['medical']['tbb']);
                // Posture and Gait
                result['medical']['posture']        = 'posture' in process ? process.posture : null;
                result['medical']['gait']           = 'gait' in process ? process.gait : null;
                // Cardiovascular
                result['medical']['pulse']          = 'pulse' in process ? toNumber(process.pulse) : null;
                var mmhg                            = 'pressure' in process ? process.pressure.split(' / ') : [];
                result['medical']['pressure']['mm'] = mmhg.length > 0 ? toNumber(mmhg[0]) : null;
                result['medical']['pressure']['hg'] = mmhg.length > 1 ? toNumber(mmhg[1]) : null;
                result['medical']['ictus']          = 'ictus' in process ? process.ictus : null;
                result['medical']['heart']          = 'heart' in process ? process.heart : null;
                // Respiratory
                result['medical']['frequency']      = 'frequency' in process ? toNumber(process.frequency) : null;
                result['medical']['retraction']     = 'retraction' in process ? process.retraction : null;
                result['medical']['r_location']     = 'r_location' in process ? process.r_location : null;
                result['medical']['breath']         = 'breath' in process ? process.breath : null;
                result['medical']['b_pipeline']     = 'b_pipeline' in process ? process.b_pipeline : null;
                // Verbal
                result['medical']['vision']         = 'vision' in process ? process.vision : null;
                result['medical']['hearing']        = 'hearing' in process ? process.hearing : null;
                result['medical']['verbal']         = 'verbal' in process ? process.verbal : null;
                // Conclusion
                result['medical']['conclusion']     = 'conclusion' in process ? (process.conclusion ? 'Disarankan' : 'Tidak Disarankan') : null;
            }
            else
            {
                result['medical']['tbb']            = null;
                result['medical']['tbd']            = null;
                result['medical']['ratio']          = 0;
                result['medical']['weight']         = null;
                result['medical']['bmi']            = 0;
                // Posture and Gait
                result['medical']['posture']        = null;
                result['medical']['gait']           = null;
                // Cardiovascular
                result['medical']['pulse']          = null;
                result['medical']['pressure']['mm'] = null;
                result['medical']['pressure']['hg'] = null;
                result['medical']['ictus']          = null;
                result['medical']['heart']          = null;
                // Respiratory
                result['medical']['frequency']      = null;
                result['medical']['retraction']     = null;
                result['medical']['r_location']     = null;
                result['medical']['breath']         = null;
                result['medical']['b_pipeline']     = null;
                // Verbal
                result['medical']['vision']         = null;
                result['medical']['hearing']        = null;
                result['medical']['verbal']         = null;
                // Conclusion
                result['medical']['conclusion']     = null;
            }

            console.log(result);
            return result;
        }

        function toNumber(value, depth)
        {
            depth = depth == null ? 0 : depth;
            return Number(value).toFixed(depth)
        }

        function filterQueue(queue, result)
        {
            var gt = null;
            result = result == null ? {} : result;
            if ('participant' in queue)
            {
                var process   = queue.participant;
                var _stamp    = 'date' in process ? moment(process.date, 'YYYY-MM-DD') : undefined;
                result['pdr'] = _stamp != null ? _stamp.format('Do MMMM YYYY') : '-';
                result['pdk'] = _stamp != null ? _stamp.format('YYYYMMDD') : '-';
                result['pno'] = 'no' in process ? process['no'] : '-';
                result['pnm'] = 'name' in process ? process['name'] : '-';
                result['pqu'] = 'queue' in process ? process['queue'] : '-';
                result['pfs'] = 'same' in process ? Number(process['same']) === 1 ? 'Mirip' : 'Tidak Mirip' : '-';
                result['pgd'] = 'gender' in process ? process['gender'] : null;
            }
            if ('medical' in queue)
            {
                var process    = queue.medical;
                result['abmi'] = 'bmi' in process ? toNumber(process.bmi, 2) : '-';
                result['ppos'] = 'posture' in process ? process.posture : '-';
                result['kvh']  = 'heart' in process ? process.heart : '-';
                result['pbr']  = 'breath' in process ? process.breath : '-';
                result['vma']  = 'vision' in process ? process.vision : '-';
                result['cco']  = 'conclusion' in process ? process.conclusion ? 'Disarankan' : 'Tidak Disarankan' : 'Tidak Disarankan';
            }
            else
            {
                result['abmi'] = '-';
                result['ppos'] = '-';
                result['kvh']  = '-';
                result['pbr']  = '-';
                result['vma']  = '-';
                result['cco']  = '-';
            }
            return result;
        }

        function listParticipant(event)
        {
            firebase.database().ref(DataMapper.Event(null, null, event)['events'] + '/preset_active').once('value').then(function (preset) {
                app.preset = preset.val();
                firebase.database().ref(DataMapper.PresetQueue(preset.val())['presets']).on('child_added', function (datedQueue) {
                    if (!(datedQueue.key in app.vault))
                    {
                        app.vault[datedQueue.key] = {};
                    }
                    _.forEach(datedQueue.val(), function (queue) {
                        if (queue != null && queue.participant.queue != null)
                        {
                            app.queues.push(filterQueue(queue));
                            app.vault[datedQueue.key][queue.participant.queue] = queue;
                        }
                    });
                });
            });
        }

        firebase.auth().onAuthStateChanged(function (user) {
            if (user)
            {
                console.log(user.email);
                listParticipant(app.event);
            } else
            {
                // User is signed out.
                // ...
            }
            removeCurtain();
        });
    });
    /*
     * Run right away
     * */

})(jQuery);
