(function ($) {
    $(function () {
        var app = new Vue({
            el: '#app',
            data: {
                home: $('meta[name=home]').attr("content"),
                event: $('meta[name=event]').attr("content"),
                role: $('meta[name=user-role]').attr("content"),
                uid: undefined,
            },
            methods: {}
        });
        firebase.auth().onAuthStateChanged(function (user) {
            if (user)
            {
                app.uid = user.uid;
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
