"use strict";

class AlarmManager
{
    static acknowledge(tag) {
        Core.ajaxRequest({
            url  : "/?context=alarm&view=acknowledge",
            data : { tag : tag }
        }).then(function (response) {
            doCallbacks(response);
        });
    }

    static getInfo(tag, callback) {
        Core.ajaxRequest({
            url : "/?context=alarm&view=info&tag=" + tag
        }).then(function (response) {
            callback(response);
        });
    }
}
