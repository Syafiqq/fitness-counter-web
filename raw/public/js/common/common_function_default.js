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
            DoNotify(sessionFlashdata['notify']);
        }
    }

})(jQuery);

function DoNotify(response)
{
    for (var i = -1, is = response.length; ++i < is;)
    {
        toastr.info(response[i]);
    }
}

function removeCurtain()
{
    if (!document.body.classList.contains("loaded"))
    {
        document.body.className += 'loaded';
    }
}
