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
                        illinois: {show: false, start: '-', elapsed: {m: 0, s: 0, SSS: 0}},
                        push: {show: false, start: '-', counter: 0},
                        run: {show: false, start: '-', elapsed: {m: 0, s: 0, SSS: 0}},
                        sit: {show: false, start: '-', counter: 0},
                        throwing: {show: false, start: '-', counter: 0},
                        vertical: {show: false, initial: 0, try1: 0, try2: 0, try3: 0, deviation: 0},
                    }
                },
                vault: {},
                qt_columns: ['pno', 'pnm', 'ildp', 'pudp', 'rndp', 'stdp', 'twdp', 'vtdp', 'gt', 'edit'],
                queues: [],
                qt_options: {
                    uniqueKey: 'pno',
                    headings: {
                        pno: 'No',
                        pnm: 'Nama',
                        ildp: 'Illinois',
                        pudp: 'Push Up',
                        rndp: 'Lari 1600 m',
                        stdp: 'Sit Up',
                        twdp: 'Lempar Bola',
                        vtdp: 'Vertical Jump',
                        gt: 'Grand Total',
                        edit: 'Edit',
                    },
                    sortable: ['pno', 'pnm', 'ildp', 'pudp', 'rndp', 'stdp', 'twdp', 'vtdp', 'gt'],
                    customSorting: {
                        ildp: function (ascending) {
                            return function (a, b) {
                                return (ascending ? -1 : 1) * Math.sign((a.ils == '-' ? 0 : a.ils) - (b.ils == '-' ? 0 : b.ils));
                            }
                        },
                        pudp: function (ascending) {
                            return function (a, b) {
                                return (ascending ? -1 : 1) * Math.sign((a.pus == '-' ? 0 : a.pus) - (b.pus == '-' ? 0 : b.pus));
                            }
                        },
                        rndp: function (ascending) {
                            return function (a, b) {
                                return (ascending ? -1 : 1) * Math.sign((a.rns == '-' ? 0 : a.rns) - (b.rns == '-' ? 0 : b.rns));
                            }
                        },
                        stdp: function (ascending) {
                            return function (a, b) {
                                return (ascending ? -1 : 1) * Math.sign((a.sts == '-' ? 0 : a.sts) - (b.sts == '-' ? 0 : b.sts));
                            }
                        },
                        twdp: function (ascending) {
                            return function (a, b) {
                                return (ascending ? -1 : 1) * Math.sign((a.tws == '-' ? 0 : a.tws) - (b.tws == '-' ? 0 : b.tws));
                            }
                        },
                        vtdp: function (ascending) {
                            return function (a, b) {
                                return (ascending ? -1 : 1) * Math.sign((a.vts == '-' ? 0 : a.vts) - (b.vts == '-' ? 0 : b.vts));
                            }
                        }
                    },
                }
            },
            computed: {
                editIllinoisEvaluator: function () {
                    var elapsed = this.processed.pVal.illinois.elapsed;
                    return evaluatorIllinois(this.processed.aVal['pgd'], toMillis(Number(elapsed.m), Number(elapsed.s), Number(elapsed.SSS)));
                },
                editPushEvaluator: function () {
                    return evaulatorPushUp(this.processed.aVal['pgd'], (Number(this.processed.pVal.push.counter)));
                },
                editRunEvaluator: function () {
                    var elapsed = this.processed.pVal.run.elapsed;
                    return evaluatorRun(this.processed.aVal['pgd'], toMillis(Number(elapsed.m), Number(elapsed.s), Number(elapsed.SSS)));
                },
                editSitEvaluator: function () {
                    return evaluatorSitUp(this.processed.aVal['pgd'], (Number(this.processed.pVal.sit.counter)));
                },
                editThrowingEvaluator: function () {
                    return evaluatorThrowingBall(this.processed.aVal['pgd'], (Number(this.processed.pVal.throwing.counter)));
                },
                editVerticalEvaluator: function () {
                    return evaluatorVerticalJump(this.processed.aVal['pgd'], (Number(this.processed.pVal.vertical.deviation)));
                },
                editVerticalDeviator: function () {
                    var vertical = this.processed.pVal.vertical;
                    return vertical.deviation = Math.max(vertical.try1, vertical.try2, vertical.try3) - vertical.initial;
                }
            },
            methods: {
                saveChanges: function () {
                    var that = this;
                    this.$swal({
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        title: 'Tunggu Sebentar',
                        onOpen: () => {
                            this.$swal.showLoading();
                            return new Promise(function (resolve) {

                                saveEdit(app.processed.pVal, app.processed.oVal);
                                filterQueue(app.processed.oVal, app.processed.aVal);
                                collectQuery(app.processed.aVal, {});
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
                calculateScore: function () {
                    var that = this;
                    NProgress.start();
                    this.$swal({
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        title: 'Tunggu Sebentar',
                        onOpen: () => {
                            this.$swal.showLoading();
                            return new Promise(function (resolve) {
                                var query = {};
                                _.forEach(app.queues, function (aQueue) {
                                    collectQuery(aQueue, query);
                                });
                                var callback = firebase.database().ref().update(query);
                                if (callback != null && typeof (callback) !== 'boolean')
                                {
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
            },

        });

        function collectQuery(queue, query)
        {
            var raw       = app.vault[queue['pdk']][queue['pqu']];
            var commonKey = DataMapper.PresetQueue(app.preset, queue['pdk'], queue['pqu'])['presets'];

            if ('illinois' in raw && 'elapsed' in raw['illinois'] && raw['illinois']['elapsed'] !== '-')
            {
                raw['illinois']['result']             = evaluatorIllinois(queue['pgd'], raw['illinois']['elapsed']);
                queue['ils']                          = raw['illinois']['result'] == null ? '-' : raw['illinois']['result'];
                query[commonKey + '/illinois/result'] = raw['illinois']['result'];
            }
            if ('push' in raw && 'counter' in raw['push'] && raw['push']['counter'] !== '-')
            {
                raw['push']['result']             = evaulatorPushUp(queue['pgd'], raw['push']['counter']);
                queue['pus']                      = raw['push']['result'] == null ? '-' : raw['push']['result'];
                query[commonKey + '/push/result'] = raw['push']['result'];
            }
            if ('run' in raw && 'elapsed' in raw['run'] && raw['run']['elapsed'] !== '-')
            {
                raw['run']['result']             = evaluatorRun(queue['pgd'], raw['run']['elapsed']);
                queue['rns']                     = raw['run']['result'] == null ? '-' : raw['run']['result'];
                query[commonKey + '/run/result'] = raw['run']['result'];
            }
            if ('sit' in raw && 'counter' in raw['sit'] && raw['sit']['counter'] !== '-')
            {
                raw['sit']['result']             = evaluatorSitUp(queue['pgd'], raw['sit']['counter']);
                queue['sts']                     = raw['sit']['result'] == null ? '-' : raw['sit']['result'];
                query[commonKey + '/sit/result'] = raw['sit']['result'];
            }
            if ('throwing' in raw && 'counter' in raw['throwing'] && raw['throwing']['counter'] !== '-')
            {
                raw['throwing']['result']             = evaluatorThrowingBall(queue['pgd'], raw['throwing']['counter']);
                queue['tws']                          = raw['throwing']['result'] == null ? '-' : raw['throwing']['result'];
                query[commonKey + '/throwing/result'] = raw['throwing']['result'];
            }
            if ('vertical' in raw && 'deviation' in raw['vertical'] && raw['vertical']['deviation'] !== '-')
            {
                raw['vertical']['result']             = evaluatorVerticalJump(queue['pgd'], raw['vertical']['deviation']);
                queue['vts']                          = raw['vertical']['result'] == null ? '-' : raw['vertical']['result'];
                query[commonKey + '/vertical/result'] = raw['vertical']['result'];
            }
        }

        function toMillis(m, s, ms)
        {
            return (Number(m) * 60000) + (Number(s) * 1000) + Number(ms);
        }

        function saveEdit(queue, result)
        {
            if (result != null)
            {
                if ('illinois' in queue && queue.illinois.show)
                {
                    var process                   = queue.illinois;
                    result['illinois']['start']   = Number(moment(process.start, moment.ISO_8601).format('x'));
                    result['illinois']['elapsed'] = toMillis(process.elapsed.m, process.elapsed.s, process.elapsed.SSS);
                }
                if ('push' in queue && queue.push.show)
                {
                    var process               = queue.push;
                    result['push']['start']   = Number(moment(process.start, moment.ISO_8601).format('x'));
                    result['push']['counter'] = process.counter;
                }
                if ('run' in queue && queue.run.show)
                {
                    var process              = queue.run;
                    result['run']['start']   = Number(moment(process.start, moment.ISO_8601).format('x'));
                    result['run']['elapsed'] = toMillis(process.elapsed.m, process.elapsed.s, process.elapsed.SSS);
                }
                if ('sit' in queue && queue.sit.show)
                {
                    var process              = queue.sit;
                    result['sit']['start']   = Number(moment(process.start, moment.ISO_8601).format('x'));
                    result['sit']['counter'] = process.counter;
                }
                if ('throwing' in queue && queue.throwing.show)
                {
                    var process                   = queue.throwing;
                    result['throwing']['start']   = Number(moment(process.start, moment.ISO_8601).format('x'));
                    result['throwing']['counter'] = process.counter;
                }
                if ('vertical' in queue && queue.vertical.show)
                {
                    var process                     = queue.vertical;
                    result['vertical']['initial']   = process.initial;
                    result['vertical']['try1']      = process.try1;
                    result['vertical']['try2']      = process.try2;
                    result['vertical']['try3']      = process.try3;
                    result['vertical']['deviation'] = process.deviation;
                }
            }

            console.log(result);
            return result;
        }

        function filterEdit(queue, result)
        {
            result = result == null ? {} : result;
            if (result['illinois']['show'] = 'illinois' in queue)
            {
                var process                          = queue.illinois;
                var elapsed                          = 'elapsed' in process ? moment(process.elapsed, 'x') : undefined;
                result['illinois']['start']          = 'start' in process ? moment(process['start']).format() : null;
                result['illinois']['elapsed']['m']   = elapsed != null ? elapsed.format('m') : 0;
                result['illinois']['elapsed']['s']   = elapsed != null ? elapsed.format('s') : 0;
                result['illinois']['elapsed']['SSS'] = elapsed != null ? elapsed.format('SSS') : 0;
            }
            else
            {
                result['illinois']['start']          = null;
                result['illinois']['elapsed']['m']   = 0;
                result['illinois']['elapsed']['s']   = 0;
                result['illinois']['elapsed']['SSS'] = 0;
            }
            if (result['push']['show'] = 'push' in queue)
            {
                var process               = queue.push;
                result['push']['start']   = 'start' in process ? moment(process['start']).format() : null;
                result['push']['counter'] = 'counter' in process ? process['counter'] : 0;
            }
            else
            {
                result['push']['start']   = null;
                result['push']['counter'] = 0;
            }
            if (result['run']['show'] = 'run' in queue)
            {
                var process                     = queue.run;
                var elapsed                     = 'elapsed' in process ? moment(process.elapsed, 'x') : undefined;
                result['run']['start']          = 'start' in process ? moment(process['start']).format() : null;
                result['run']['elapsed']['m']   = elapsed != null ? elapsed.format('m') : 0;
                result['run']['elapsed']['s']   = elapsed != null ? elapsed.format('s') : 0;
                result['run']['elapsed']['SSS'] = elapsed != null ? elapsed.format('SSS') : 0;
            }
            else
            {
                result['run']['start']          = null;
                result['run']['elapsed']['m']   = 0;
                result['run']['elapsed']['s']   = 0;
                result['run']['elapsed']['SSS'] = 0;
            }
            if (result['sit']['show'] = 'sit' in queue)
            {
                var process              = queue.sit;
                result['sit']['start']   = 'start' in process ? moment(process['start']).format() : null;
                result['sit']['counter'] = 'counter' in process ? process['counter'] : 0;
            }
            else
            {
                result['sit']['start']   = null;
                result['sit']['counter'] = 0;
            }
            if (result['throwing']['show'] = 'throwing' in queue)
            {
                var process                   = queue.throwing;
                result['throwing']['start']   = 'start' in process ? moment(process['start']).format() : null;
                result['throwing']['counter'] = 'counter' in process ? process['counter'] : 0;
            }
            else
            {
                result['throwing']['start']   = null;
                result['throwing']['counter'] = 0;
            }
            if (result['vertical']['show'] = 'vertical' in queue)
            {
                var process                     = queue.vertical;
                result['vertical']['initial']   = 'initial' in process ? process['initial'] : 0;
                result['vertical']['try1']      = 'try1' in process ? process['try1'] : 0;
                result['vertical']['try2']      = 'try2' in process ? process['try2'] : 0;
                result['vertical']['try3']      = 'try3' in process ? process['try3'] : 0;
                result['vertical']['deviation'] = 'deviation' in process ? process['deviation'] : app.editVerticalDeviator();
            }
            else
            {
                result['vertical']['initial']   = 0;
                result['vertical']['try1']      = 0;
                result['vertical']['try2']      = 0;
                result['vertical']['try3']      = 0;
                result['vertical']['deviation'] = 0;
            }

            console.log(result);
            return result;
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
                result['pgd'] = 'gender' in process ? process['gender'] : null;
            }
            if ('illinois' in queue)
            {
                var process   = queue.illinois;
                var elapsed   = 'elapsed' in process ? moment(process.elapsed, 'x') : undefined;
                result['ile'] = elapsed != null ? elapsed.format('m:ss') : '-';
                if ('result' in process)
                {
                    result['ils'] = process['result'];
                    gt            = gt == null ? result['ils'] : (result['ils'] + gt);
                }
                else
                {
                    result['ils'] = '-';
                }
            }
            else
            {
                result['ile'] = '-';
                result['ils'] = '-';
            }
            result['ildp'] = result['ile'] + " (" + result['ils'] + ")";

            if ('push' in queue)
            {
                var process   = queue.push;
                result['puc'] = 'counter' in process ? process['counter'] : '-';
                if ('result' in process)
                {
                    result['pus'] = process['result'];
                    gt            = gt == null ? result['pus'] : (result['pus'] + gt);
                }
                else
                {
                    result['pus'] = '-';
                }
            }
            else
            {
                result['puc'] = '-';
                result['pus'] = '-';
            }
            result['pudp'] = result['puc'] + " (" + result['pus'] + ")";

            if ('run' in queue)
            {
                var process   = queue.run;
                var elapsed   = 'elapsed' in process ? moment(process.elapsed, 'x') : undefined;
                result['rne'] = elapsed != null ? elapsed.format('m:ss') : '-';
                if ('result' in process)
                {
                    result['rns'] = process['result'];
                    gt            = gt == null ? result['rns'] : (result['rns'] + gt);
                }
                else
                {
                    result['rns'] = '-';
                }
            }
            else
            {
                result['rne'] = '-';
                result['rns'] = '-';
            }
            result['rndp'] = result['rne'] + " (" + result['rns'] + ")";

            if ('sit' in queue)
            {
                var process   = queue.sit;
                result['stc'] = 'counter' in process ? process['counter'] : '-';
                if ('result' in process)
                {
                    result['sts'] = process['result'];
                    gt            = gt == null ? result['sts'] : (result['sts'] + gt);
                }
                else
                {
                    result['sts'] = '-';
                }
            }
            else
            {
                result['stc'] = '-';
                result['sts'] = '-';
            }
            result['stdp'] = result['stc'] + " (" + result['sts'] + ")";

            if ('throwing' in queue)
            {
                var process   = queue.throwing;
                result['twc'] = 'counter' in process ? process['counter'] : '-';
                if ('result' in process)
                {
                    result['tws'] = process['result'];
                    gt            = gt == null ? result['tws'] : (result['tws'] + gt);
                }
                else
                {
                    result['tws'] = '-';
                }
            }
            else
            {
                result['twc'] = '-';
                result['tws'] = '-';
            }
            result['twdp'] = result['twc'] + " (" + result['tws'] + ")";

            if ('vertical' in queue)
            {
                var process   = queue.vertical;
                result['vtd'] = 'deviation' in process ? process['deviation'] : '-';
                if ('result' in process)
                {
                    result['vts'] = process['result'];
                    gt            = gt == null ? result['vts'] : (result['vts'] + gt);
                }
                else
                {
                    result['vts'] = '-';
                }
            }
            else
            {
                result['vtd'] = '-';
                result['vts'] = '-';
            }
            result['vtdp'] = result['vtd'] + " (" + result['vts'] + ")";


            result['gt'] = gt == null ? '-' : gt;
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
