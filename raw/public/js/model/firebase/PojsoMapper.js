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
    JsonResponse: function (code, status, data) {
        code   = typeof code === 'undefined' ? 200 : code;
        status = typeof status === 'undefined' ? 'Empty Status' : status;
        data   = typeof data === 'undefined' ? null : data;
        return {code: code, status: status, data: data}
    },
    PresetQueue: function (participant) {
        participant = typeof participant === 'undefined' ? '-' : participant;
        return {
            presets: {participant: participant}
        }
    },
    CompactPresetQueue: function (queue, preset) {
        queue  = typeof queue === 'undefined' ? '-' : queue;
        preset = typeof preset === 'undefined' ? {} : preset;

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
            role: {name: firebaseRef.name, uid: uid, participate: participate}
        }
    }
};
