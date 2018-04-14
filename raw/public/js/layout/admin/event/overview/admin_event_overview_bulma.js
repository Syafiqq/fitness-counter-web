(function ($) {
    $(function () {
        var app = new Vue({
            el: '#app',
            data: {
                home: $('meta[name=home]').attr("content"),
                event: $('meta[name=event]').attr("content"),
                role: $('meta[name=user-role]').attr("content"),
                qt_columns: ['queue', 'no', 'name', 'gender', 'same'],
                queues: [],
                qt_options: {
                    headings: {
                        queue: 'Antrian',
                        no: 'Nomor',
                        name: 'Nama',
                        gender: 'Jenis Kelamin',
                        same: 'Kemiripan',
                    },
                    sortable: ['queue', 'no', 'name', 'gender', 'same'],
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
                firebase.database().ref(DataMapper.PresetQueue(preset.val(), moment().format('YYYYMMDD'))['presets']).on('child_added', function (queue) {
                    var participant = queue.val()['participant'];
                    // @formatter:off
                    if (participant['gender'] != null) participant['gender'] = String(participant['gender']) === 'l' ? 'Laki - Laki' : 'Perempuan';
                    if (participant['same'] != null) participant['same'] = Number(participant['same']) === 1 ? 'Mirip' : 'Tidak Mirip';
                    // @formatter:on
                    app.queues.push(participant);
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
