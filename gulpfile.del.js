module.exports = {
    delLight: function () {
        return [
            './public/**',
            './resources/views/**',
            '!./resources/views',
            '!./resources/views/vendor/**',
            '!./public',
            '!./public/assets',
            '!./public/vendor',
            '!./public/vendor/**'
        ];
    },

    delHard: function () {
        return [
            './public/vendor/**'
        ];
    }
};
