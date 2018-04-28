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
                    var that = this;
                    NProgress.start();
                    this.$swal({
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        title: 'Tunggu Sebentar',
                        onOpen: function () {
                            that.$swal.showLoading();
                            axios.get(
                                app.home + '/' + app.role + '/event/' + app.event + '/upload/participant/template'
                                , {}
                                , {
                                    responseType: 'blob',
                                    headers: {
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json;charset=UTF-8',
                                    }
                                }
                            )
                                .then(function (response) {
                                    console.log(response);
                                    that.$swal.close();
                                    if ('data' in response && 'headers' in response && 'content-disposition' in response.headers && 'content-type' in response.headers)
                                    {
                                        fileDownload(response.data, getFilename(response.headers['content-disposition'], response.headers['content-type']));
                                    }
                                    NProgress.done();

                                })
                                .catch(function (error) {
                                    that.$swal({
                                        type: 'error',
                                        title: 'Pemrosesan Gagal',
                                    });
                                    NProgress.done();
                                });
                        },
                        preConfirm: function () {

                        },
                    }).then(function (result) {
                        console.log("swal result" + result)
                    });
                },
                uploadParticipant: function () {
                    this.$refs.upload.click()
                }
            },

        });

        function listParticipant(event)
        {
            firebase.database().ref(DataMapper.Event(null, null, event)['events'] + '/participant').once('value').then(function (participant) {
                app.queues.splice(0);
                _.forEach(participant.val(), function (queue) {
                    if (queue != null && 'no' in queue && 'name' in queue && 'gender' in queue)
                    {
                        queue.gender = queue.gender === 'l' ? 'Laki - Laki' : 'Perempuan';
                        app.queues.push(queue);
                    }
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
