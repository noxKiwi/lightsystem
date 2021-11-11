"use strict";

/**
 * I am a collection of events that exist in the code.
 */
let events = {
    AJAXREQUESTERROR300 : "AJAXREQUESTERROR300",
    AJAXREQUESTERROR400 : "AJAXREQUESTERROR400",
    AJAXREQUESTERROR401 : "AJAXREQUESTERROR401",
    AJAXREQUESTERROR403 : "AJAXREQUESTERROR403",
    AJAXREQUESTERROR404 : "AJAXREQUESTERROR404",
    AJAXREQUESTERROR500 : "AJAXREQUESTERROR500",
    AJAXREQUESTERROR501 : "AJAXREQUESTERROR501",
    AJAXREQUESTERROR502 : "AJAXREQUESTERROR502",
    AJAXREQUESTERROR503 : "AJAXREQUESTERROR503",
    AJAXREQUESTERROR504 : "AJAXREQUESTERROR504",
    AJAXREQUESTERRORXXX : "AJAXREQUESTERRORXXX",
    AJAXREQUESTSUCCESS  : "AJAXREQUESTSUCCESS"
};

/**
 * @requires Log
 */
class Hook
{
    /**
     * I will fire the function attached to the event with the given name
     * I will also pass eventData as argument to that function.
     * If a function was not defined, I won't do anything.
     */
    static fire(eventName, eventData) {
        if (typeof (this.callbacks) !== "object") {
            return;
        }

        if (typeof (this.callbacks[eventName]) !== "function") {
            return;
        }

        let myCallback = this.callbacks[eventName];
        return myCallback(eventData);
    }

    /**
     * I am like fire, but I will remove the attached function of the given eventName.
     */
    static fireOnce(eventName, eventData) {
        if (typeof (this.callbacks) !== "object") {
            return;
        }

        if (typeof (this.callbacks[eventName]) !== "function") {
            return;
        }

        let myCallback            = this.callbacks[eventName];
        let returnData            = myCallback(eventData);
        this.callbacks[eventName] = null;
        return returnData;
    }

    /**
     * I will add a method that will be executed if you call Hook.fire(eventName) given the same eventName.
     */
    static add(eventName, eventFunction) {
        if (typeof (this.callbacks) !== "object") {
            this.callbacks = {};
        }

        Log.Info("Added Hook for eventName " + eventName);
        this.callbacks[eventName] = eventFunction;
    }
}
