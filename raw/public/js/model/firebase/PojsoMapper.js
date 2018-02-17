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
            events: {event: event, slug: slug, admin: uid, createdAt: firebase.database.ServerValue.TIMESTAMP},
            users: true,
        }
    },
    Preset: function (event, preset) {
        return {
            presets: {event: event, createAt: firebase.database.ServerValue.TIMESTAMP},
            users: preset
        }
    },
    JsonResponse: function (code = 200, status = 'Empty Status', data = null) {
        return {code: code, status: status, data: data}
    },
    PresetQueue: function (participant = '-') {
        return {
            presets: {participant: participant}
        }
    },
    CompactPresetQueue: function (queue = '-', preset = {}) {
        preset['queue'] = queue;
        return {
            presets: preset,
        }
    },
    UserManagement: function (uid, event, role, firebaseRef) {
        var participate;
        try
        {
            participate = firebaseRef[role]['events'][event] == null ? false : firebaseRef[role]['events'][event];
        }
        catch (TypeError)
        {
            participate = false;
        }

        return {
            registrar: {name: firebaseRef.name, uid: uid, participate: participate}
        }
    }
};
