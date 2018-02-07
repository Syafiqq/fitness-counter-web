(function ($) {
    $(function () {
        var app = new Vue({
            el: '#app',
            data: {
                home: $('meta[name=home]').attr("content"),
                event: $('meta[name=event]').attr("content"),
                role: $('meta[name=user-role]').attr("content"),
            },
            methods: {
                addNewPreset: function () {
                    NProgress.start();
                    var presetKey = firebase.database().ref().child(DataMapper.Preset(null, null)['presets']).push().key;
                    var query     = {};
                    var mapping   = DataMapper.Preset(
                        app.event,
                        presetKey);
                    var presets   = PojsoMapper.Preset(app.event, presetKey);
                    $.each(mapping, function (key, value) {
                        switch (key)
                        {// @formatter:off
                            case 'presets' : {query[value] = presets[key]} break;
                            case 'users_event_presets' : {query[value] = true} break;
                            case 'users_event_preset' : {query[value] = presets['users']} break;
                        }// @formatter:on
                    });
                    return firebase.database().ref().update(query, function (error) {
                        if (error !== undefined)
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
        firebase.auth().onAuthStateChanged(function (user) {
            if (user)
            {
                console.log(user.email);
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
