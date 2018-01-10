module.exports = {
    assetsVendorResource: function (negated, mime) {
        return [
            (negated ? '!' : '') + './node_modules/jquery/dist/**' + (mime === null ? '' : '/' + mime),
            (negated ? '!' : '') + './node_modules/html5-boilerplate/dist/css/**' + (mime === null ? '' : '/' + mime),
            (negated ? '!' : '') + './node_modules/html5-boilerplate/dist/js/**' + (mime === null ? '' : '/' + mime),
            (negated ? '!' : '') + './node_modules/font-awesome/css/**' + (mime === null ? '' : '/' + mime),
            (negated ? '!' : '') + './node_modules/font-awesome/fonts/**' + (mime === null ? '' : '/' + mime),
            (negated ? '!' : '') + './node_modules/nprogress/**' + (mime === null ? '' : '/' + mime),
            (negated ? '!' : '') + './node_modules/ionicons/dist/**' + (mime === null ? '' : '/' + mime),
            (negated ? '!' : '') + './node_modules/fastclick/lib/**' + (mime === null ? '' : '/' + mime),
            (negated ? '!' : '') + './node_modules/html5shiv/dist/**' + (mime === null ? '' : '/' + mime),
            (negated ? '!' : '') + './node_modules/respond.js/dest/**' + (mime === null ? '' : '/' + mime),
        ];
    }
};
