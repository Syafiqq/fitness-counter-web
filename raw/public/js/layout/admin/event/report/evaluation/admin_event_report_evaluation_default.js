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
                    _.forEach(this.queues, function (queue) {
                        var raw = app.vault[queue['pdk']][queue['pqu']];
                        if ('illinois' in raw && 'elapsed' in raw['illinois'])
                        {
                            raw['illinois']['result'] = evaluatorIllinois(queue['pgd'], raw['illinois']['elapsed']);
                            queue['ils']              = raw['illinois']['result'] == null ? '-' : raw['illinois']['result'];
                        }
                        if ('push' in raw && 'counter' in raw['push'])
                        {
                            raw['push']['result'] = evaulatorPushUp(queue['pgd'], raw['push']['counter']);
                            queue['pus']          = raw['push']['result'] == null ? '-' : raw['push']['result'];
                        }
                        if ('run' in raw && 'elapsed' in raw['run'])
                        {
                            raw['run']['result'] = evaluatorRun(queue['pgd'], raw['run']['elapsed']);
                            queue['rns']         = raw['run']['result'] == null ? '-' : raw['run']['result'];
                        }
                        if ('sit' in raw && 'counter' in raw['sit'])
                        {
                            raw['sit']['result'] = evaluatorSitUp(queue['pgd'], raw['sit']['counter']);
                            queue['sts']         = raw['sit']['result'] == null ? '-' : raw['sit']['result'];
                        }
                        if ('throwing' in raw && 'counter' in raw['throwing'])
                        {
                            raw['throwing']['result'] = evaluatorThrowingBall(queue['pgd'], raw['throwing']['counter']);
                            queue['tws']              = raw['throwing']['result'] == null ? '-' : raw['throwing']['result'];
                        }
                        if ('vertical' in raw && 'deviation' in raw['vertical'])
                        {
                            raw['vertical']['result'] = evaluatorVerticalJump(queue['pgd'], raw['vertical']['deviation']);
                            queue['vts']              = raw['vertical']['result'] == null ? '-' : raw['vertical']['result'];
                        }
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
