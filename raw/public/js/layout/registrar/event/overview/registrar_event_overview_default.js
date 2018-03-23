(function ($) {
    $(function () {
        var app = new Vue({
            el: '#app',
            data: {
                is_process: true,
                f_participant: undefined,
                f_same: undefined,
                preset: undefined,
                home: $('meta[name=home]').attr("content"),
                event: $('meta[name=event]').attr("content"),
                role: $('meta[name=user-role]').attr("content"),
                token: $('meta[name=user-token]').attr("content"),
                uid: undefined,
            },
            methods: {

                openModal: function () {
                    if (app.preset != null && app.f_participant != null && app.f_same != null)
                    {
                        app.is_process = true;
                        this.$swal({
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            title: 'Peserta : [' + app.f_participant + '] - [' + (Number(app.f_same) === 0 ? 'Tidak ' : '') + 'Mirip]',
                            html: 'Apakah Anda Ingin Mendaftarkan Peserta : <strong>' + app.f_participant + '</strong><br> Dengan Wajah :<strong>' + (Number(app.f_same) === 0 ? 'Tidak' : '') + ' Mirip</strong>',
                            showCancelButton: true,
                            confirmButtonText: 'Ya',
                            showLoaderOnConfirm: true,
                            preConfirm: function () {
                                return new Promise(function (resolve) {
                                    var _stamp = moment('2018-03-13', 'YYYY-MM-DD');
                                    NProgress.configure({parent: '.swal2-modal', showSpinner: false});
                                    NProgress.start();
                                    axios.post(
                                        app.home + '/' + app.role + '/event/' + app.event + '/queue/add'
                                        , {
                                            event: app.event,
                                            preset: app.preset,
                                            participant: app.f_participant,
                                            stamp: (_stamp = _stamp == null ? moment('2018-03-13', 'YYYY-MM-DD') : _stamp).format('YYYY-MM-DD')
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
                                            if (response.data != null && 'code' in response.data && response.data.code === 200 && response.data.data.queue !== 0)
                                            {
                                                response.data.data.same = Number(app.f_same);
                                                response.data.data.date = (_stamp = _stamp == null ? moment('2018-03-13', 'YYYY-MM-DD') : _stamp).format('YYYY-MM-DD');
                                                registerCallback = createNewPresetQueue(firebase, {
                                                    queue: response.data.data.queue,
                                                    participant: response.data.data,
                                                    stamp: (_stamp = _stamp == null ? moment('2018-03-13', 'YYYY-MM-DD') : _stamp).format('YYYYMMDD')
                                                }, app.preset)
                                            }
                                            if (registerCallback != null)
                                            {
                                                registerCallback.then(function () {
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
                                            if (error.response != null && error.response.data != null && error.response.data.code === 422)
                                            {
                                                DoNotify(responseData = error.response.data.data);
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
                        }).then(function (result) {
                            console.log(result);
                            result = result.value;
                            if (result != null && 'code' in result)
                            {
                                if (result.code === 200)
                                {
                                    if (result.data.queue === 0)
                                    {
                                        app.$swal({
                                            type: 'error',
                                            title: 'Oops...',
                                            text: 'Anda sudah mengikuti event ini sebelumnya',
                                        })
                                    }
                                    else
                                    {
                                        app.$swal({
                                            type: 'success',
                                            title: 'Nomor Antrian Anda: ' + result.data.queue
                                        });
                                    }
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
            removeCurtain();
        });
    });
    /*
     * Run right away
     * */

})(jQuery);
