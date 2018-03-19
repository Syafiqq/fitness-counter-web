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
                vault: {},
                qt_columns: ['pno', 'pnm', 'ile', 'ils', 'puc', 'pus', 'rne', 'rns', 'stc', 'sts', 'twc', 'tws', 'vtd', 'vts', 'edit'],
                queues: [],
                qt_options: {
                    uniqueKey: 'pno',
                    headings: {
                        pno: 'No',
                        pnm: 'Nama',
                        ile: 'I Waktu',
                        ils: 'I Skor',
                        puc: 'PU Total',
                        pus: 'PU Skor',
                        rne: 'R Waktu',
                        rns: 'R Skor',
                        stc: 'SU Total',
                        sts: 'SU Skor',
                        twc: 'T Total',
                        tws: 'T Skor',
                        vtd: 'VJ Selisih',
                        vts: 'VJ Skor',
                        edit: 'Edit',
                    },
                    sortable: ['pno', 'pnm', 'ile', 'ils', 'puc', 'pus', 'rne', 'rns', 'stc', 'sts', 'twc', 'tws', 'vtd', 'vts'],
                }
            },
            methods: {
                editParticipant: function (pno) {
                },
                calculateScore: function () {
                    var that = this;
                    NProgress.start();
                    this.$swal({
                        title: 'Tunggu Sebentar',
                        onOpen: () => {
                            this.$swal.showLoading();
                            return new Promise(function (resolve) {
                                var query = {};
                                _.forEach(app.queues, function (queue) {
                                    var raw       = app.vault[queue['pdk']][queue['pqu']];
                                    var commonKey = DataMapper.PresetQueue(app.preset, queue['pdk'], queue['pqu'])['presets'];

                                    if ('illinois' in raw && 'elapsed' in raw['illinois'])
                                    {
                                        raw['illinois']['result']             = evaluatorIllinois(queue['pgd'], raw['illinois']['elapsed']);
                                        queue['ils']                          = raw['illinois']['result'] == null ? '-' : raw['illinois']['result'];
                                        query[commonKey + '/illinois/result'] = raw['illinois']['result'];
                                    }
                                    if ('push' in raw && 'counter' in raw['push'])
                                    {
                                        raw['push']['result']             = evaulatorPushUp(queue['pgd'], raw['push']['counter']);
                                        queue['pus']                      = raw['push']['result'] == null ? '-' : raw['push']['result'];
                                        query[commonKey + '/push/result'] = raw['push']['result'];
                                    }
                                    if ('run' in raw && 'elapsed' in raw['run'])
                                    {
                                        raw['run']['result']             = evaluatorRun(queue['pgd'], raw['run']['elapsed']);
                                        queue['rns']                     = raw['run']['result'] == null ? '-' : raw['run']['result'];
                                        query[commonKey + '/run/result'] = raw['run']['result'];
                                    }
                                    if ('sit' in raw && 'counter' in raw['sit'])
                                    {
                                        raw['sit']['result']             = evaluatorSitUp(queue['pgd'], raw['sit']['counter']);
                                        queue['sts']                     = raw['sit']['result'] == null ? '-' : raw['sit']['result'];
                                        query[commonKey + '/sit/result'] = raw['sit']['result'];
                                    }
                                    if ('throwing' in raw && 'counter' in raw['throwing'])
                                    {
                                        raw['throwing']['result']             = evaluatorThrowingBall(queue['pgd'], raw['throwing']['counter']);
                                        queue['tws']                          = raw['throwing']['result'] == null ? '-' : raw['throwing']['result'];
                                        query[commonKey + '/throwing/result'] = raw['throwing']['result'];
                                    }
                                    if ('vertical' in raw && 'deviation' in raw['vertical'])
                                    {
                                        raw['vertical']['result']             = evaluatorVerticalJump(queue['pgd'], raw['vertical']['deviation']);
                                        queue['vts']                          = raw['vertical']['result'] == null ? '-' : raw['vertical']['result'];
                                        query[commonKey + '/vertical/result'] = raw['vertical']['result'];
                                    }
                                });
                                var callback = firebase.database().ref().update(query);
                                if (callback != null && typeof (callback) !== 'boolean')
                                {
                                    console.log(callback);
                                    callback.then(function (result) {
                                        console.log(result);
                                        app.is_process = false;
                                        NProgress.done();
                                        that.$swal({
                                            type: 'success',
                                            title: 'Perhitungan selesai',
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
                }
            }
        });

        function filterQueue(queue, result)
        {
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
                result['pgd'] = 'gender' in process ? process['gender'] : null;
            }
            if ('illinois' in queue)
            {
                var process   = queue.illinois;
                var elapsed   = 'elapsed' in process ? moment(process.elapsed, 'x') : undefined;
                result['ile'] = elapsed != null ? elapsed.format('m:ss') : '-';
                result['ils'] = 'result' in process ? process['result'] : '-';
            }
            if ('push' in queue)
            {
                var process   = queue.push;
                result['puc'] = 'counter' in process ? process['counter'] : '-';
                result['pus'] = 'result' in process ? process['result'] : '-';
            }
            if ('run' in queue)
            {
                var process   = queue.run;
                var elapsed   = 'elapsed' in process ? moment(process.elapsed, 'x') : undefined;
                result['rne'] = elapsed != null ? elapsed.format('m:ss') : '-';
                result['rns'] = 'result' in process ? process['result'] : '-';
            }
            if ('sit' in queue)
            {
                var process   = queue.sit;
                result['stc'] = 'counter' in process ? process['counter'] : '-';
                result['sts'] = 'result' in process ? process['result'] : '-';
            }
            if ('throwing' in queue)
            {
                var process   = queue.throwing;
                result['twc'] = 'counter' in process ? process['counter'] : '-';
                result['tws'] = 'result' in process ? process['result'] : '-';
            }
            if ('vertical' in queue)
            {
                var process   = queue.vertical;
                result['vtd'] = 'deviation' in process ? process['deviation'] : '-';
                result['vts'] = 'result' in process ? process['result'] : '-';
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
