(function ($) {
    $(function () {
        var app = new Vue({
            el: '#app',
            data: {
                is_process: false,
                f_name: undefined,
                f_email: undefined,
                f_password: undefined,
                home: $('meta[name=home]').attr("content"),
                uid: undefined,
            },
            methods: {
                doRegister: function () {
                    NProgress.start();
                    firebase.auth().createUserWithEmailAndPassword(this.f_email, this.f_password).catch(function (error) {
                        var errorCode    = error.code;
                        var errorMessage = error.message;
                        // @formatter:off
                        switch (errorCode)
                        {
                            case 'auth/email-already-in-use' : DoNotify(['Email sudah digunakan']); break;
                            case 'auth/invalid-email' : DoNotify(['Email tidak valid']); break;
                            case 'auth/operation-not-allowed' : DoNotify(['Regisrasi gagal dilakukan']); break;
                            case 'auth/weak-password' : DoNotify(['Password terlalu lemah']); break;
                            default : DoNotify([errorMessage]); break;
                        }
                        // @formatter:on
                    }).then(function (result) {
                        if (result != null)
                        {
                            firebase.auth().signInWithEmailAndPassword(app.f_email, app.f_password).catch(function (error) {
                            }).then(function (result) {
                                var registerRole = function (uid) {
                                    return new Promise(function (resolve) {
                                        createNewUser(firebase, {
                                            role: 'registrar',
                                            name: app.f_name
                                        }, uid).then(function (error) {
                                            if (error != null)
                                            {
                                                return registerRole().then(function () {
                                                    resolve(true);
                                                })
                                            }
                                            else
                                            {
                                                resolve(true);
                                            }
                                        })
                                    });
                                };

                                if (result != null)
                                {
                                    registerRole(firebase.auth().currentUser.uid).then(function (result) {
                                        firebase.auth().signOut().then(function (result) {
                                        }).catch(function (error) {
                                        });
                                    });
                                }
                            });
                            DoNotify(['Pendaftaran Berhasil']);
                        }
                        NProgress.done();
                    });
                }
            }
        });
    });
    /*
     * Run right away
     * */

})(jQuery);
