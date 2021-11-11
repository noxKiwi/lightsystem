"use strict";

/**
 * I am the Event Service client.
 */
class EventClient extends BaseClient
{
    constructor() {
        super("event");
        console.log("A");
    }

    /**
     * I will make sure there is only ONE active AlarmClient.
     */
    static getInstance() {
        if (typeof (runtime.instances["EventClient"]) === "undefined") {
            runtime.instances["EventClient"] = new EventClient();
        }
        return runtime.instances["EventClient"];
    }

    getGroups() {
        return this.doRequest("getGroups");
    }

    getEvents() {
        return this.doRequest("getEvents");
    }
}
