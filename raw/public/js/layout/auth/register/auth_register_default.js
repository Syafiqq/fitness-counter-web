(function ($) {
    $(function () {
        var app = new Vue({
            el: '#app',
            data: {
                is_process: false,
                f_name: undefined,
                f_email: undefined,
                f_password: undefined,
                home: $('meta[name=home]').attr("content"),
                uid: undefined,
            },
            methods: {
                doRegister: function () {

                }
            }
        });
    });
    /*
     * Run right away
     * */

})(jQuery);
