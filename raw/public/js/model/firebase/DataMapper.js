/**
 * This <fitness-counter.com> project created by :
 * Name         : syafiq
 * Date / Time  : 27 January 2018, 3:16 PM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */
var DataMapper = {
    Event: function (uid, id = undefined) {
        id = id === undefined ? '' : '/' + id;
        return {
            events: PathMapper.EVENTS + id,
            users: PathMapper.USERS + '/' + uid + '/' + PathMapper.EVENTS + id,
        };
    },
    Preset: function (uid = '-', event = '-', id = '-') {
        uid   = uid.length > 0 ? '/' + uid : uid;
        event = event.length > 0 ? '/' + event : event;
        id    = id.length > 0 ? '/' + id : id;
        return {
            presets: PathMapper.PRESETS + id,
            users: PathMapper.USERS + uid + '/' + PathMapper.EVENTS + event + '/preset',
        };
    }
};
