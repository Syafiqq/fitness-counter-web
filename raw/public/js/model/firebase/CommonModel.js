/**
 * This <fitness-counter.com> project created by :
 * Name         : syafiq
 * Date / Time  : 12 February 2018, 8:38 PM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */
function createNewEvent(firebase, data)
{
    var eventKey = firebase.database().ref().child(DataMapper.Event()['events']).push().key;
    var event    = PojsoMapper.Event(data.event, data.slug, firebase.auth().currentUser.uid);
    var query    = {};
    var mapping  = DataMapper.Event(
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
