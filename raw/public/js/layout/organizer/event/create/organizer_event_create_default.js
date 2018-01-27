(function ($) {
    $(function () {
        var app = new Vue({
            el: '#app',
            data: {
                is_logged_out: true,
                f_event: '',
                f_slug: ''
            },
            methods: {
                createEvent: function () {
                    console.log(this.f_event, this.f_slug);
                    this.is_logged_out = true;
                    NProgress.start();
                }
            }
        });
        firebase.auth().onAuthStateChanged(function (user) {
            app.is_logged_out = !user;
            if (user)
            {
                console.log(user.email);
            } else
            {
                DoNotify('User Is Sign Out');
            }
        });
    });
    /*
     * Run right away
     * */

})(jQuery);
