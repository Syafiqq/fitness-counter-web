(function ($) {
    $(function () {
        Vue.component('list-event', {
            props: ['event', 'event_id'],
            template: "<li><a :href=\"this.$parent.home +'/admin/event/' + event_id\">{{event.event}}</a></li>",
        });

        var app = new Vue({
            el: '#app',
            data: {
                is_process: true,
                f_event: '',
                f_slug: '',
                l_events: {},
                home: $('meta[name=home]').attr("content"),
                role: $('meta[name=user-role]').attr("content"),
            },
            methods: {
                eventFormCommit: function () {
                    NProgress.configure({parent: '.sweet-modal', showSpinner: false});
                    NProgress.start();
                    app.is_process = true;
                    var eventKey   = firebase.database().ref().child(DataMapper.Event()['events']).push().key;
                    createNewEvent(firebase, {
                        event: this.f_event,
                        slug: this.f_slug,
                        role: this.role
                    }, eventKey).then(function (error) {
                        if (error == null)
                        {
                            createNewPreset(firebase, {event: eventKey}).then(function () {
                                DoNotify(['Pembuatan Event berhasil']);
                                if (error == null)
                                {
                                    DoNotify(['Pembuatan Counter berhasil'])
                                } else
                                {
                                    DoNotify([error])
                                }
                            });
                        }
                        else
                        {
                            DoNotify([error])
                        }
                        NProgress.done();
                        app.is_process = false;
                    });
                },
                eventFormOpen: function () {
                    this.$refs.modal.open();
                },
                eventListInitial: function () {
                    var events = firebase.database().ref(DataMapper.Event(firebase.auth().currentUser.uid, app.role)['users']);
                    events.on('child_added', function (eventOverview) {
                        firebase.database().ref(DataMapper.Event(null, null, eventOverview.key)['events']).once('value').then(function (event) {
                            Vue.set(app.l_events, event.key, event.val())
                        });
                    });
                }
            }
        });
        firebase.auth().onAuthStateChanged(function (user) {
            if (user)
            {
                console.log(user.email);
                app.is_process = false;
                app.eventListInitial();
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
