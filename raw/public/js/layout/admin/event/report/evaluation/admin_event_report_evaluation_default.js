(function ($) {
    $(function () {
        moment.locale('id');
        var app = new Vue({
            el: '#app',
            data: {
                home: $('meta[name=home]').attr("content"),
                event: $('meta[name=event]').attr("content"),
                role: $('meta[name=user-role]').attr("content"),
                vault: {},
                qt_columns: ['pno', 'pnm', 'ile', 'ils', 'puc', 'pus', 'rne', 'rns', 'stc', 'sts', 'twc', 'tws', 'vtd', 'vts'],
                queues: [],
                qt_options: {
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
                    },
                    sortable: ['pno', 'pnm', 'ile', 'ils', 'puc', 'pus', 'rne', 'rns', 'stc', 'sts', 'twc', 'tws', 'vtd', 'vts'],
                }
            },
            methods: {
                calculateScore: function () {
                    /*NProgress.start();
                    createNewPreset(firebase, {event: this.event}).then(function (error) {
                        if (error == null)
                        {
                            DoNotify(['Pembuatan Counter berhasil'])
                        } else
                        {
                            DoNotify([error])
                        }
                        NProgress.done();
                    });*/
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
            }
            if ('illinois' in queue)
            {
                var process   = queue.illinois;
                var elapsed   = 'elapsed' in process ? moment(process.elapsed, 'x') : undefined;
                result['ile'] = elapsed != null ? elapsed.format('m:ss') : '-';
                result['ils'] = 'score' in process ? process['score'] : '-';
            }
            if ('push' in queue)
            {
                var process   = queue.push;
                result['puc'] = 'counter' in process ? process['counter'] : '-';
                result['pus'] = 'score' in process ? process['score'] : '-';
            }
            if ('run' in queue)
            {
                var process   = queue.run;
                var elapsed   = 'elapsed' in process ? moment(process.elapsed, 'x') : undefined;
                result['rne'] = elapsed != null ? elapsed.format('m:ss') : '-';
                result['rns'] = 'score' in process ? process['score'] : '-';
            }
            if ('sit' in queue)
            {
                var process   = queue.sit;
                result['stc'] = 'counter' in process ? process['counter'] : '-';
                result['sts'] = 'score' in process ? process['score'] : '-';
            }
            if ('throwing' in queue)
            {
                var process   = queue.throwing;
                result['twc'] = 'counter' in process ? process['counter'] : '-';
                result['tws'] = 'score' in process ? process['score'] : '-';
            }
            if ('vertical' in queue)
            {
                var process   = queue.vertical;
                result['vtd'] = 'deviation' in process ? process['deviation'] : '-';
                result['vts'] = 'score' in process ? process['score'] : '-';
            }
            return result;
        }

        function listParticipant(event)
        {
            firebase.database().ref(DataMapper.Event(null, null, event)['events'] + '/preset_active').once('value').then(function (preset) {
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
