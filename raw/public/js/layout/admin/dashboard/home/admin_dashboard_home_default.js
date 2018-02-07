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

                    var eventKey = firebase.database().ref().child(DataMapper.Event()['events']).push().key;
                    var event    = PojsoMapper.Event(app.f_event, app.f_slug, firebase.auth().currentUser.uid);
                    var query    = {};
                    var mapping  = DataMapper.Event(
                        firebase.auth().currentUser.uid,
                        app.role,
                        eventKey
                    );
                    $.each(mapping, function (key, value) {
                        switch (key)
                        {// @formatter:off
                            case 'events' : {query[value] = event['events']} break;
                            case 'users' : {query[value] = event['users']} break;
                        }// @formatter:on
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
                    var events = firebase.database().ref(DataMapper.Event(firebase.auth().currentUser.uid, app.role)['users']);
                    events.on('child_added', function (eventOverview) {
                        console.log(eventOverview.key, eventOverview.val());
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
            document.body.className += 'loaded';
        });
    });
    /*
     * Run right away
     * */

})(jQuery);
