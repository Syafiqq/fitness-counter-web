/**
 * This <fitness-counter.com> project created by :
 * Name         : syafiq
 * Date / Time  : 27 January 2018, 3:16 PM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */
var DataMapper = {
    Users: function (uid = null) {
        uid = uid === null ? '' : '/' + uid;
        return {
            users: PathMapper.USERS + uid,
        };
    },
    Event: function (uid = null, role = null, id = null) {
        uid  = uid === null ? '' : '/' + uid;
        role = role === null ? '' : '/' + role;
        id   = id === null ? '' : '/' + id;
        return {
            events: PathMapper.EVENTS + id,
            users: PathMapper.USERS + uid + role + '/' + PathMapper.EVENTS + id,
        };
    }
    ,
    Preset: function (event = null, id = null) {
        event = event == null ? '' : '/' + event;
        id    = id == null ? '' : '/' + id;
        return {
            presets: PathMapper.PRESETS + id,
            users_event_presets: PathMapper.EVENTS + event + '/presets' + id,
            users_event_preset: PathMapper.EVENTS + event + '/preset_active',
        };
    },
    PresetQueue: function (preset = null, queue = null) {
        preset = preset == null ? '' : '/' + preset;
        queue  = queue == null ? '' : '/' + queue;
        return {
            presets: PathMapper.PRESETS + preset + '/queues' + queue
        }
    }
};
