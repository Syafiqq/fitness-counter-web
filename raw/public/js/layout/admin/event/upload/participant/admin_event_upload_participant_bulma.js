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
                queues: [],
                qt_columns: ['no', 'name', 'gender'],
                qt_options: {
                    uniqueKey: 'no',
                    headings: {
                        no: 'No SBMPTN',
                        name: 'Nama',
                        gender: 'Jenis Kelamin',
                    },
                    sortable: ['no', 'name', 'gender'],
                }
            },
            computed: {},
            methods: {
                downloadTemplate: function () {

                },
                uploadParticipant: function () {
                    this.$refs.upload.click()
                }
            },

        });

        function listParticipant(event)
        {

        }

        firebase.auth().onAuthStateChanged(function (user) {
            if (user)
            {
                console.log(user.email);
                //listParticipant(app.event);
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
