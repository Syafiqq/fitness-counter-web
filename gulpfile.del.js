module.exports = {
    delLight: function () {
        return [
            './public/**',
            './resources/views/**',
            '!./resources/views',
            '!./resources/views/vendor/**',
            '!./public',
            '!./public/assets',
            '!./public/assets/vendor',
            '!./public/assets/vendor/**'
        ];
    },

    delHard: function () {
        return [
            './public/assets/vendor/**'
        ];
    }
};
