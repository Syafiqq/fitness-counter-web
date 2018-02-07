/**
 * This <fitness-counter.com> project created by :
 * Name         : syafiq
 * Date / Time  : 28 January 2018, 5:31 PM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

var PojsoMapper = {
    Event: function (event, slug, uid) {
        return {
            events: {event: event, slug: slug, uid: uid, createdAt: firebase.database.ServerValue.TIMESTAMP},
            users: true,
        }
    },
    Preset: function (uid, preset) {
        return {
            presets: {uid: uid, createAt: firebase.database.ServerValue.TIMESTAMP},
            users: preset
        }
    }
};
