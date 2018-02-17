(function ($) {
    $(function () {
        var app = new Vue({
            el: '#app',
            data: {
                home: $('meta[name=home]').attr("content"),
                event: $('meta[name=event]').attr("content"),
                role: $('meta[name=user-role]').attr("content"),
                qt_columns: ['queue', 'participant'],
                queues: [],
                qt_options: {
                    headings: {
                        queue: 'Antrian',
                        participant: 'Nomor Peserta',
                    },
                    sortable: ['queue', 'participant'],
                }
            },
            methods: {
                addNewPreset: function () {
                    NProgress.start();
                    createNewPreset(firebase, {event: this.event}).then(function (error) {
                        if (error == null)
                        {
                            DoNotify(['Pembuatan Counter berhasil'])
                        } else
                        {
                            DoNotify([error])
                        }
                        NProgress.done();
                    });
                }
            }
        });

        function listParticipant(event)
        {
            firebase.database().ref(DataMapper.Event(null, null, event)['events'] + '/preset_active').once('value').then(function (preset) {
                firebase.database().ref(DataMapper.PresetQueue(preset.val())['presets']).on('child_added', function (queue) {
                    app.queues.push(PojsoMapper.CompactPresetQueue(queue.key, queue.val())['presets']);
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
            document.body.className += 'loaded';
        });
    });
    /*
     * Run right away
     * */

})(jQuery);
