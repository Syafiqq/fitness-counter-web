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
    Preset: function (event = null, id = null) {
        event = event == null ? '' : '/' + event;
        id    = id == null ? '' : '/' + id;
        return {
            presets: PathMapper.PRESETS + id,
            users_event_presets: PathMapper.EVENTS + event + '/presets' + id,
            users_event_preset: PathMapper.EVENTS + event + '/preset_active',
        };
    }
};
