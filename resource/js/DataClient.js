"use strict";

/**
 * I am the Data Monitor client.
 */
class DataClient extends BaseClient
{
    /**
     * I will construct the DataClient.
     */
    constructor() {
        super("data");
        this.data = {
            counter    : -1,       // I am the change counter for the service.
            alarmCount : -1,    // I am the amount of not-acknowledged alarms.
            list       : [],          // I am the list of alarms.
            server     : ""         // I am the server name.
        };
    }

    /**
     * I will make sure there is only ONE active DataClient.
     */
    static getInstance() {
        if (typeof (runtime.instances["DataClient"]) === "undefined") {
            runtime.instances["DataClient"] = new DataClient();
        }
        return runtime.instances["DataClient"];
    }

    /**
     * I will return the list of groups.
     *
     * @return string[]
     */
    getGroups() {
        return this.doRequest("getGroups");
    }

    /**
     * I will return the plain data for the given setup.
     */
    getData(opcItem, compression, display, interval) {
        return this.doRequest("getData", {opcItem: opcItem, compression:compression, display:display, interval:interval});
    }
    
    getPoints(opcItem, compression, begin, end, interval) {
        return this.doRequest("getData", {opcItem: opcItem, compression:compression, begin:begin, end:end, interval:'MINUTE'});
    }

    getCompressions() {
        return this.doRequest("getCompressions");
    }

    getDisplays() {
        return this.doRequest("getDisplays");
    }

    getNodes(groupId) {
        return this.doRequest("getNodes", {groupId:groupId});
    }

    getTable(groupId, compression, display, interval, begin) {
        return this.doRequest("getTable", {groupId: groupId, compression:compression, display:display, interval:interval, begin: begin});
    }
}
