(function ($) {
    $(function () {
        var app = new Vue({
            el: '#app',
            data: {
                is_process: true,
                f_event: '',
                f_slug: '',
            },
            methods: {
                eventFormCommit: function () {
                    NProgress.configure({parent: '.sweet-modal', showSpinner: false});
                    NProgress.start();
                    app.is_process = true;

                    var event    = PojsoMapper.Event(app.f_event, app.f_slug);
                    var computed = {};
                    var mapping  = DataMapper.Event(
                        firebase.auth().currentUser.uid,
                        firebase.database().ref().child(DataMapper.Event(firebase.auth().currentUser.uid)['events']).push().key);
                    $.each(mapping, function (key, value) {
                        switch (key)
                        {
                            case 'events' :
                            {
                                computed[value]        = Object.assign({}, event);
                                computed[value]['uid'] = firebase.auth().currentUser.uid;
                            }
                                break;
                            default :
                            {
                                computed[value] = Object.assign({}, event);
                            }
                                break;
                        }
                    });

                    return firebase.database().ref().update(computed, function (error) {
                        if (error !== undefined)
                        {
                            DoNotify(['Pembuatan Event berhasil'])
                        } else
                        {
                            DoNotify([error])
                        }
                        NProgress.done();
                        app.is_process = false;
                    });
                },
                eventFormOpen: function () {
                    this.$refs.modal.open();
                }
            }
        });
        firebase.auth().onAuthStateChanged(function (user) {
            if (user)
            {
                console.log(user.email);
                app.is_process = false;
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
