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
