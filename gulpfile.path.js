module.exports = {
    assetsVendorResource: function (negated, ext) {
        return [
            (negated ? '!' : '') + './node_modules/html5-boilerplate/dist/css/**' + (ext === null ? '' : '/' + ext),
            (negated ? '!' : '') + './node_modules/html5-boilerplate/dist/js/**' + (ext === null ? '' : '/' + ext),
            (negated ? '!' : '') + './node_modules/respond.js/dest/**' + (ext === null ? '' : '/' + ext),
            (negated ? '!' : '') + './node_modules/firebase/**' + (ext === null ? '' : '/' + ext),
            (negated ? '!' : '') + './node_modules/vue/**' + (ext === null ? '' : '/' + ext),
        ];
    }
};
