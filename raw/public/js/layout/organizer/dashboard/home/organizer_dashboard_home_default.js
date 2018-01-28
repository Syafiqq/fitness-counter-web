(function ($) {
    $(function () {
        var app = new Vue({
            el: '#app',
            data: {
                is_logged_out: true,
                f_event: '',
                f_slug: '',
            },
            methods: {
                eventFormCommit: function () {
                    console.log(this.f_event, this.f_slug);
                    NProgress.configure({parent: '.sweet-modal', showSpinner: false});
                    NProgress.start();
                    this.is_logged_out = true;
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
                app.is_logged_out = false;
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
