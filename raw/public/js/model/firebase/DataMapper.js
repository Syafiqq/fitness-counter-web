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
            global: PathMapper.EVENTS + id,
            local: PathMapper.USERS + '/' + uid + '/' + PathMapper.EVENTS + id,
        };
    }
};
