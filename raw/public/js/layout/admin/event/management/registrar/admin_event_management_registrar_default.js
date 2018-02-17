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
                    console.log(app.registrar)
                },
                check: function (uid, participate) {
                    _.filter(app.registrar, {uid: uid})[0].participate = !participate
                }
            },
        });

        function listRegistrar()
        {
            firebase.database().ref(DataMapper.Users()['users']).orderByChild('roles/registrar').equalTo(true).on("child_added", function (snapshot) {
                app.registrar.push(PojsoMapper.UserManagement(snapshot.key, snapshot.val()).registrar)
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
