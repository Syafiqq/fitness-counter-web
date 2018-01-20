module.exports = {
    assetsVendorResource: function (negated, ext) {
        return [
            (negated ? '!' : '') + './node_modules/jquery/dist/**' + (ext === null ? '' : '/' + ext),
            (negated ? '!' : '') + './node_modules/html5-boilerplate/dist/css/**' + (ext === null ? '' : '/' + ext),
            (negated ? '!' : '') + './node_modules/html5-boilerplate/dist/js/**' + (ext === null ? '' : '/' + ext),
            (negated ? '!' : '') + './node_modules/font-awesome/css/**' + (ext === null ? '' : '/' + ext),
            (negated ? '!' : '') + './node_modules/font-awesome/fonts/**' + (ext === null ? '' : '/' + ext),
            (negated ? '!' : '') + './node_modules/nprogress/**' + (ext === null ? '' : '/' + ext),
            (negated ? '!' : '') + './node_modules/ionicons/dist/**' + (ext === null ? '' : '/' + ext),
            (negated ? '!' : '') + './node_modules/fastclick/lib/**' + (ext === null ? '' : '/' + ext),
            (negated ? '!' : '') + './node_modules/html5shiv/dist/**' + (ext === null ? '' : '/' + ext),
            (negated ? '!' : '') + './node_modules/respond.js/dest/**' + (ext === null ? '' : '/' + ext),
            (negated ? '!' : '') + './node_modules/firebase/**' + (ext === null ? '' : '/' + ext)
        ];
    }
};
