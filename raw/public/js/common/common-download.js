/**
 * This <fitness-counter.com> project created by :
 * Name         : syafiq
 * Date / Time  : 21 April 2018, 1:12 PM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

getFilename = function (data) {
    var filename = "";
    if (data && data.indexOf('attachment') !== -1)
    {
        var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
        var matches       = filenameRegex.exec(data);
        if (matches != null && matches[1])
        {
            filename = matches[1].replace(/['"]/g, '');
        }
    }
    return filename;
};

fileDownload = function (data, filename, mime) {
    var blob = new Blob([data], {type: mime || 'application/octet-stream'});
    if (typeof window.navigator.msSaveBlob !== 'undefined')
    {
        // IE workaround for "HTML7007: One or more blob URLs were
        // revoked by closing the blob for which they were created.
        // These URLs will no longer resolve as the data backing
        // the URL has been freed."
        window.navigator.msSaveBlob(blob, filename);
    }
    else
    {
        var blobURL            = window.URL.createObjectURL(blob);
        var tempLink           = document.createElement('a');
        tempLink.style.display = 'none';
        tempLink.href          = blobURL;
        tempLink.setAttribute('download', filename);

        // Safari thinks _blank anchor are pop ups. We only want to set _blank
        // target if the browser does not support the HTML5 download attribute.
        // This allows you to download files in desktop safari if pop up blocking
        // is enabled.
        if (typeof tempLink.download === 'undefined')
        {
            tempLink.setAttribute('target', '_blank');
        }

        document.body.appendChild(tempLink);
        tempLink.click();
        document.body.removeChild(tempLink);
        window.URL.revokeObjectURL(blobURL);
    }
};
