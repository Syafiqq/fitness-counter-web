(function ($) {
    $(function () {

    });
    /*
     * Run right away
     * */

    if ((sessionFlashdata !== undefined) && (sessionFlashdata !== null))
    {
        if ((sessionFlashdata['notify'] !== undefined))
        {
            apiResponseNotify(sessionFlashdata['notify']);
        }
    }

})(jQuery);

function apiResponseNotify(response)
{
    for (var i = -1, is = response.length; ++i < is;)
    {
        toastr.info(response[i]);
    }
}
