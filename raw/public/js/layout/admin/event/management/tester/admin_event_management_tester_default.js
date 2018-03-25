(function ($) {
    $(function () {
        var app = new Vue({
            el: '#app',
            data: {
                home: $('meta[name=home]').attr("content"),
                event: $('meta[name=event]').attr("content"),
                role: $('meta[name=user-role]').attr("content"),
                qt_columns: ['name', 'participate'],
                tester: [],
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
                    _.forEach(this.tester, function (value) {
                        query[DataMapper.Event(value.uid, 'tester', app.event)['users']] = value.participate === false ? null : value.participate
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
                    _.filter(app.tester, {uid: uid})[0].participate = !participate
                }
            },
        });

        function listTester()
        {
            firebase.database().ref(DataMapper.Users()['users']).orderByChild('roles/tester').equalTo(true).on("child_added", function (snapshot) {
                app.tester.push(PojsoMapper.UserManagement(snapshot.key, app.event, 'tester', snapshot.val()).role)
            });
        }

        firebase.auth().onAuthStateChanged(function (user) {
            if (user)
            {
                console.log(user.email);
                listTester();
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
