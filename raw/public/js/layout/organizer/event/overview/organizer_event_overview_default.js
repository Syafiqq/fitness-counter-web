(function ($) {
    $(function () {
        firebase.auth().onAuthStateChanged(function (user) {
            if (user)
            {
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
