(function ($) {
    $(function () {
        var app = new Vue({
            el: '#app',
            data: {
                home: $('meta[name=home]').attr("content"),
                event: $('meta[name=event]').attr("content"),
                role: $('meta[name=user-role]').attr("content"),
                columns: ['id', 'name', 'age'],
                tableData: [
                    {id: 1, name: "John", age: "20"},
                    {id: 2, name: "Jane", age: "24"},
                    {id: 3, name: "Susan", age: "16"},
                    {id: 4, name: "Chris", age: "55"},
                    {id: 5, name: "Dan", age: "40"}
                ],
                options: {
                    // see the options API
                }
            },
            methods: {
                addNewPreset: function () {
                    NProgress.start();
                    createNewPreset(firebase, {event: this.event}).then(function (error) {
                        if (error == null)
                        {
                            DoNotify(['Pembuatan Counter berhasil'])
                        } else
                        {
                            DoNotify([error])
                        }
                        NProgress.done();
                    });
                }
            }
        });

        function listParticipant(event)
        {
            firebase.database().ref(DataMapper.Event(null, null, event)['events'] + '/preset_active').once('value').then(function (preset) {
                console.log(preset.val())
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
            document.body.className += 'loaded';
        });
    });
    /*
     * Run right away
     * */

})(jQuery);
