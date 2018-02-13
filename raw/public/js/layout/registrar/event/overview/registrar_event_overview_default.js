(function ($) {
    $(function () {
        var app = new Vue({
            el: '#app',
            data: {
                is_process: true,
                f_participant: undefined,
                preset: undefined,
                home: $('meta[name=home]').attr("content"),
                event: $('meta[name=event]').attr("content"),
                role: $('meta[name=user-role]').attr("content"),
                token: $('meta[name=user-token]').attr("content"),
                uid: undefined,
            },
            methods: {

                openModal: function () {
                    if (app.preset != null && app.f_participant != null)
                    {
                        app.is_process = true;
                        this.$swal({
                            title: 'Peserta : [' + app.f_participant + ']',
                            text: 'Apakah Anda Ingin Mendaftarkan Peserta : ' + app.f_participant,
                            showCancelButton: true,
                            confirmButtonText: 'Ya',
                            showLoaderOnConfirm: true,
                            preConfirm: () => {
                                return new Promise((resolve) => {
                                    NProgress.configure({parent: '.swal2-modal', showSpinner: false});
                                    NProgress.start();
                                    axios.post(
                                        app.home + '/' + app.role + '/event/' + app.event + '/queue/add'
                                        , {
                                            preset: app.preset,
                                            participant: app.f_participant
                                        }
                                        , {
                                            headers: {
                                                'Accept': 'application/json',
                                                'Content-Type': 'application/json;charset=UTF-8',
                                            }
                                        }
                                    )
                                        .then(function (response) {
                                            var resolveCallback  = function (response) {
                                                app.is_process = false;
                                                NProgress.done();
                                                resolve(response.data);
                                            };
                                            var registerCallback = undefined;
                                            if (response.data != null && 'code' in response.data)
                                            {
                                                if (response.data.code === 200)
                                                {
                                                    registerCallback = createNewPresetQueue(firebase, {
                                                        queue: response.data.data.queue,
                                                        participant: app.f_participant
                                                    }, app.preset)
                                                }
                                            }
                                            if (registerCallback != null)
                                            {
                                                registerCallback.then(() => {
                                                    resolveCallback(response);
                                                });
                                            }
                                            else
                                            {
                                                resolveCallback(response);
                                            }
                                        })
                                        .catch(function (error) {
                                            var responseData = null;
                                            if (error.response != null && error.response.data != null)
                                            {
                                                if (error.response.data.code === 422)
                                                {
                                                    DoNotify(error.response.data.data);
                                                }
                                                responseData = error.response.data;
                                            }
                                            else
                                            {
                                                responseData = PojsoMapper.JsonResponse(500, 'Internal Server Error');
                                            }
                                            NProgress.done();
                                            app.is_process = false;
                                            resolve(responseData);
                                        });
                                })
                            },
                        }).then((result) => {
                            console.log(result);
                            result = result.value;
                            if (result != null && 'code' in result)
                            {
                                if (result.code === 200)
                                {
                                    app.$swal({
                                        type: 'success',
                                        title: 'Nomor Antrian Anda: ' + result.data.queue
                                    });
                                }
                                else
                                {
                                    app.$swal({
                                        type: 'error',
                                        title: 'Oops...',
                                        text: result['status'],
                                    })
                                }
                            }
                            else
                            {
                                app.is_process = false;
                            }
                        });

                    }
                    else
                    {
                        DoNotify(['Data Tidak Lengkap'])
                    }
                },
                eventListInitial: function () {
                    firebase.database().ref(DataMapper.Event(null, null, app.event)['events'] + "/preset_active").once('value').then(function (event) {
                        app.preset     = event.val();
                        app.is_process = false;

                        if (app.preset == null)
                        {
                            DoNotify(['Tidak Ada Event Yang Aktif'])
                        }
                        else
                        {
                            app.is_process = false;
                        }
                    });
                }
            }
        });
        firebase.auth().onAuthStateChanged(function (user) {
            if (user)
            {
                app.uid = user.uid;
                app.eventListInitial();
                console.log(user.email);
            }
            else
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
