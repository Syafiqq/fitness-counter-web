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
                },
                notifyFileInput: function (event) {
                    console.log(event.target.files);
                    var that = this;
                    if (event.target.files.length > 0)
                    {
                        var file = event.target.files[0];
                        this.$swal({
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            title: 'Tunggu Sebentar',
                            onOpen: function () {
                                var formData = new FormData();
                                formData.append('upload', file);
                                that.$swal.showLoading();
                                axios.post(
                                    app.home + '/' + app.role + '/event/' + app.event + '/upload/participant/upload'
                                    , formData
                                    , {
                                        headers: {
                                            'Accept': 'application/json',
                                            'Content-Type': 'multipart/form-data',
                                        }
                                    }
                                )
                                    .then(function (response) {
                                        listParticipant(app.event);
                                        that.$swal({
                                            type: 'success',
                                            title: 'Upload Data Berhasil',
                                        });
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
                    }
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
        var fileSelector = 'input[type="file"]';
        $(fileSelector).change(app.notifyFileInput.bind(this));
        $(fileSelector).on('click', function () {
            $(this).val("");
        })
    });
    /*
     * Run right away
     * */

})(jQuery);
