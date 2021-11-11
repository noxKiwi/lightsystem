"use strict";

/**
 * I am the activity stream.
 */
class BaseClient
{
    /**
     * I will construct the Client and set the context for the API.
     */
    constructor(defaultContext) {
        this.defaultContext = defaultContext;
    }

    /**
     * I will return the data object.
     */
    getData() {
        return this.data;
    }

    /**
     * I will send a request (either WebSocket or AJAX) to the given information.
     * @param endpoint
     * @param data
     * @returns {*}
     */
    async doRequest(endpoint, data) {
        let request  = {
                data   : data,
                method : endpoint
            },
            response = null;
        return Core.runRequest(this.defaultContext, "run", null, request);
    }

    /**
     * I will return the change counter of this service.
     * @return int
     */
    getCounter() {
        return this.doRequest("getCounter");
    }

    /**
     * I will return whether the $serverName is connected or not.
     * $serverName defaults to the currently set up master Server.
     * @return bool
     */
    isConnected() {
        return this.doRequest("isConnected");
    }

    /**
     * I will set the connection to the given $serverName.
     *
     * @param string $serverName
     *
     * @return bool
     */
    setServer(serverName) {
        return this.doRequest("setServer", serverName);
    }

    /**
     * I will return the list of host names for this service.
     * @return string[]
     */
    getServer() {
        return this.doRequest("getServer");
    }

    /**
     * I will return the host name of the connected master server.
     * @return string
     */
    getServers() {
        return this.doRequest("getServers");
    }
}
