(function ($) {
    $(function () {
        $('select#role-changer').on('change', function () {
            location.href = $('meta[name=home]').attr("content") + "/auth/switch/" + $(this).val();
        });
    });
    /*
     * Run right away
     * */

})(jQuery);
