(function ($) {
    $(function () {
        firebase.auth().onAuthStateChanged(function (user) {
            if (user)
            {
                console.log(user.email);
            } else
            {
                // User is signed out.
                // ...
            }
        });
    });
    /*
     * Run right away
     * */

})(jQuery);
