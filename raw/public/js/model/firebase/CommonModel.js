/**
 * This <fitness-counter.com> project created by :
 * Name         : syafiq
 * Date / Time  : 12 February 2018, 8:38 PM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */
function createNewEvent(firebase, data, eventKey)
{
    eventKey    = typeof eventKey === 'undefined' ? firebase.database().ref().child(DataMapper.Event()['events']).push().key : eventKey;
    var event   = PojsoMapper.Event(data.event, data.slug, firebase.auth().currentUser.uid);
    var query   = {};
    var mapping = DataMapper.Event(
        firebase.auth().currentUser.uid,
        data.role,
        eventKey
    );
    _.forEach(mapping, function (value, key) {
        switch (key)
        {// @formatter:off
            case 'events' : {query[value] = event['events']} break;
            case 'users' : {query[value] = event['users']} break;
        }// @formatter:on
    });

    return firebase.database().ref().update(query);
}

function createNewPreset(firebase, data, presetKey)
{
    presetKey   = typeof presetKey === 'undefined' ? firebase.database().ref().child(DataMapper.Preset(null, null)['presets']).push().key : presetKey;
    var query   = {};
    var mapping = DataMapper.Preset(
        data.event,
        presetKey);
    var presets = PojsoMapper.Preset(data.event, presetKey);
    _.forEach(mapping, function (value, key) {
        switch (key)
        {// @formatter:off
            case 'presets' : {query[value] = presets[key]} break;
            case 'users_event_presets' : {query[value] = true} break;
            case 'users_event_preset' : {query[value] = presets['users']} break;
        }// @formatter:on
    });
    return firebase.database().ref().update(query)
}

function createNewPresetQueue(firebase, data, preset)
{
    if (typeof data.queue === 'number' && isFinite(data.queue))
    {
        var query   = {};
        var mapping = DataMapper.PresetQueue(preset, data.queue);
        var presets = PojsoMapper.PresetQueue(data.participant);
        _.forEach(mapping, function (value, key) {
            switch (key)
            {// @formatter:off
                case 'presets' : {query[value + "/participant"] = presets[key]['participant']} break;
            }// @formatter:on
        });
        return firebase.database().ref().update(query);
    }
    return null;
}

function createNewUser(firebase, data, uid)
{
    var query = {};
    _.forEach(DataMapper.Users(uid), function (value, key) {
        switch (key)
        {// @formatter:off
            case 'user_name' : {query[value] = data.name} break;
        }// @formatter:on
    });
    _.forEach(DataMapper.UserRole(uid, data.role), function (value, key) {
        switch (key)
        {// @formatter:off
            case 'users' : {query[value] = true} break;
            case 'users_groups' : {query[value] = true} break;
        }// @formatter:on
    });
    return firebase.database().ref().update(query)
}
