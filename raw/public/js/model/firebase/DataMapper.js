/**
 * This <fitness-counter.com> project created by :
 * Name         : syafiq
 * Date / Time  : 27 January 2018, 3:16 PM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */
var DataMapper = {
    Users: function (uid) {
        uid = typeof uid === 'undefined' ? '' : (uid === null ? '' : '/' + uid);
        return {
            users: PathMapper.USERS + uid,
            user_name: PathMapper.USERS + uid + '/name',
        };
    },
    UserRole: function (uid, role) {
        uid  = typeof uid === 'undefined' ? '' : (uid === null ? '' : '/' + uid);
        role = typeof role === 'undefined' ? '' : (role === null ? '' : '/' + role);
        return {
            users: PathMapper.USERS + uid + '/roles' + role,
            users_groups: PathMapper.USERS_GROUPS + role + uid,
        };
    },
    Event: function (uid, role, id) {
        uid  = typeof uid === 'undefined' ? '' : (uid === null ? '' : '/' + uid);
        role = typeof role === 'undefined' ? '' : (role === null ? '' : '/' + role);
        id   = typeof id === 'undefined' ? '' : (id === null ? '' : '/' + id);
        return {
            events: PathMapper.EVENTS + id,
            users: PathMapper.USERS + uid + role + '/' + PathMapper.EVENTS + id,
        };
    }
    ,
    Preset: function (event, id) {
        event = typeof event === 'undefined' ? '' : (event == null ? '' : '/' + event);
        id    = typeof id === 'undefined' ? '' : (id == null ? '' : '/' + id);
        return {
            presets: PathMapper.PRESETS + id,
            users_event_presets: PathMapper.EVENTS + event + '/presets' + id,
            users_event_preset: PathMapper.EVENTS + event + '/preset_active',
        };
    },
    PresetQueue: function (preset, queue) {
        preset = typeof preset === 'undefined' ? '' : (preset == null ? '' : '/' + preset);
        queue  = typeof queue === 'undefined' ? '' : (queue == null ? '' : '/' + queue);
        return {
            presets: PathMapper.PRESETS + preset + '/queues' + queue
        }
    }
};
