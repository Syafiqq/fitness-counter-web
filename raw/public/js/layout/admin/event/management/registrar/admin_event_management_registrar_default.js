(function ($) {
    $(function () {
        var app = new Vue({
            el: '#app',
            data: {
                home: $('meta[name=home]').attr("content"),
                event: $('meta[name=event]').attr("content"),
                role: $('meta[name=user-role]').attr("content"),
                qt_columns: ['name', 'participate'],
                registrar: [],
                qt_options: {
                    headings: {
                        name: 'Nama',
                        participate: 'Hak Akses',
                    },
                    sortable: ['queue'],
                }
            },
            methods: {
                save: function () {
                    NProgress.start();
                    var query = {};
                    _.forEach(this.registrar, function (value) {
                        query[DataMapper.Event(value.uid, 'registrar', app.event)['users']] = value.participate
                    });
                    firebase.database().ref().update(query).then(function (error) {
                        if (error == null)
                        {
                            DoNotify(['Perubahan Hak Akses Berhasil'])
                        } else
                        {
                            DoNotify([error])
                        }
                        NProgress.done();
                    });
                },
                check: function (uid, participate) {
                    _.filter(app.registrar, {uid: uid})[0].participate = !participate
                }
            },
        });

        function listRegistrar()
        {
            firebase.database().ref(DataMapper.Users()['users']).orderByChild('roles/registrar').equalTo(true).on("child_added", function (snapshot) {
                app.registrar.push(PojsoMapper.UserManagement(snapshot.key, app.event, 'registrar', snapshot.val()).registrar)
            });
        }

        firebase.auth().onAuthStateChanged(function (user) {
            if (user)
            {
                console.log(user.email);
                listRegistrar();
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
