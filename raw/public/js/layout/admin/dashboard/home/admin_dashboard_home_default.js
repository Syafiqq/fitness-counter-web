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
            },
            methods: {
                eventFormCommit: function () {
                    NProgress.configure({parent: '.sweet-modal', showSpinner: false});
                    NProgress.start();
                    app.is_process = true;

                    var event   = PojsoMapper.Event(app.f_event, app.f_slug, firebase.auth().currentUser.uid);
                    var query   = {};
                    var mapping = DataMapper.Event(
                        firebase.auth().currentUser.uid,
                        firebase.database().ref().child(DataMapper.Event(firebase.auth().currentUser.uid)['events']).push().key);
                    $.each(mapping, function (key, value) {
                        switch (key)
                        {
                            case 'events' :
                            {
                                query[value] = event['events']
                            }
                                break;
                            default :
                            {
                                query[value] = event['users']
                            }
                                break;
                        }
                    });

                    return firebase.database().ref().update(query, function (error) {
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
                },
                eventListInitial: function () {
                    var events = firebase.database().ref(DataMapper.Event(firebase.auth().currentUser.uid)['users']);
                    events.on('child_added', function (eventOverview) {
                        firebase.database().ref(DataMapper.Event(firebase.auth().currentUser.uid, eventOverview.key)['events']).once('value').then(function (event) {
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
            document.body.className += 'loaded';
        });
    });
    /*
     * Run right away
     * */

})(jQuery);
