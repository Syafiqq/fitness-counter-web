(function ($) {
    $(function () {
        Vue.component('list-event', {
            props: ['event', 'event_id'],
            template: "<li><a :href=\"this.$parent.home + '/' + this.$parent.role + '/event/' + event_id\">{{event.event}}</a></li>",
        });
        var app = new Vue({
            el: '#app',
            data: {
                l_events: {},
                home: $('meta[name=home]').attr("content"),
                role: $('meta[name=user-role]').attr("content"),
            },
            methods: {
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
