(function ($) {
    $(function () {
        var app = new Vue({
            el: '#app',
            data: {
                is_logged_out: true
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
