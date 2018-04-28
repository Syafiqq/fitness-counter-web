(function ($) {
    $(function () {
        firebase.auth().onAuthStateChanged(function (user) {
            if (user)
            {
                firebase.database().ref(DataMapper.Event(null, null, $('meta[name=event]').attr("content"))['events'] + "/event").once('value').then(function (snapshot) {
                    $('strong#navbar-title').html(snapshot.val());
                });
            }
            else
            {

            }
        });
    });
    /*
     * Run right away
     * */

})(jQuery);
