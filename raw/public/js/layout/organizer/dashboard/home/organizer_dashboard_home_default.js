(function ($) {
    $(function () {
        var app = new Vue({
            el: '#app',
            data: {},
            methods: {
                testModal: function () {
                    this.$refs.modal.open();
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
