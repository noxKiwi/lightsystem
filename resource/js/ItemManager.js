"use strict";

/**
 *
 */
class ItemManager
{
    static write(tag, value) {
        Core.ajaxRequest({
            url     : "/?context=item&view=write",
            data    : {
                tag   : tag,
                value : value
            },
            success : Core.doCallbacks
        }).then(function (response) {
            Core.doCallbacks(response);
        });
    }

    static getInfo(tag, callback) {
        Core.ajaxRequest({
            url : "/?context=item&view=info&tag=" + tag
        }).then(function (response) {
            callback(response);
        });
    }

}
