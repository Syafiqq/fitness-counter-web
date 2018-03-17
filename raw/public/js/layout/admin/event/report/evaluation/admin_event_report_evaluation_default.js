(function ($) {
    $(function () {
        var app = new Vue({
            el: '#app',
            data: {
                home: $('meta[name=home]').attr("content"),
                event: $('meta[name=event]').attr("content"),
                role: $('meta[name=user-role]').attr("content"),
                vault: {},
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
        });

        function listParticipant(event)
        {
            firebase.database().ref(DataMapper.Event(null, null, event)['events'] + '/preset_active').once('value').then(function (preset) {
                firebase.database().ref(DataMapper.PresetQueue(preset.val())['presets']).on('child_added', function (datedQueue) {
                    app.vault[datedQueue.key] = {};
                    _.forEach(datedQueue.val(), function (queue) {
                        if (queue != null && queue.participant.queue != null)
                        {
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
