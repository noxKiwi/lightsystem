"use strict";

var bellSound = new buzz.sound("/asset/lib/sound/alert.mp3");

/**
 * I am the AlarmClient.
 */
class AlarmClient extends BaseClient
{
    /**
     * I will construct the AlarmClient.
     */
    constructor() {
        super("alarm");
        this.data = {
            counter    : -1,    // I am the change counter for the service.
            alarmCount : -1,    // I am the amount of not-acknowledged alarms.
            list       : [],    // I am the list of alarms.
            server     : "",    // I am the server name.
            lastCount  : null   // I am the last count of the getCount method to determine changes.
        };
    }

    /**
     * I will make sure there is only ONE active AlarmClient.
     */
    static getInstance() {
        if (typeof (runtime.instances["AlarmClient"]) === "undefined") {
            runtime.instances["AlarmClient"] = new AlarmClient();
            runtime.instances["AlarmClient"].work();
        }
        return runtime.instances["AlarmClient"];
    }

    clearWork() {
        if (typeof (runtime.Intervals.AlarmClientWork) !== "undefined") {
            clearInterval(runtime.Intervals.AlarmClientWork);
        }
    }

    resetWork() {
        runtime.Intervals.AlarmClientWork = window.setTimeout(this.work, 10 * (runtime.settings.updateInterval || 1000), this);
    }

    /**
     * I will check the server's counter to determine if something has changed.
     */
    work() {
        let aClient = AlarmClient.getInstance();
        if (aClient.isChanged()) {
            aClient.changed();
        }
        aClient.resetWork();
    }

    /**
     * I will solely return TRUE if the counter has changed compared with the last change.
     */
    isChanged() {
        let newCounter = this.getCounter();
        if (newCounter !== this.data.alarmCount) {
            this.data.alarmCount = newCounter;
            return true;
        }
        return false;
    }

    reset() {
        this.data.alarmCount = -1;
    }

    update() {
        this.list();
        this.count();
    }

    /**
     * I am called when the server changed the counter.
     */
    changed() {
        this.update();
        let controls      = Control.getControlsOfType("AlarmControl"),
            controlsCount = controls.length - 1,
            controlsIndex = 0,
            clientData    = this.getData();
        for (controlsIndex = 0; controlsIndex <= controlsCount; controlsIndex++) {
            let myControl = controls[controlsIndex];
            myControl.output(clientData);
        }
    }

    /**
     * I will return the amount of alarms that have not gone yet.
     * @returns int
     */
    async count() {
        await this.doRequest("count").then((data) => {
            this.data.count = data.response;
        });

        $(".alarmCountBadge").html(this.data.count);

        if (this.data.count === this.data.lastCount) {
            return;
        }

        if (this.data.count === 0) {
            $(".alarmIconDisengaged").removeClass("d-none");
            $(".alarmIconEngaged").addClass("d-none");
        } else {
            $(".alarmIconDisengaged").addClass("d-none");
            $(".alarmIconEngaged").removeClass("d-none");
            bellSound.play();
        }
        this.data.lastCount = this.data.count;
        return this.data.count;
    }

    /**
     * I will return the list of alarms that are currently set off.
     * @return array
     */
    async list() {
        await this.doRequest("list").then((data) => {
            this.data.list = data.response;
        });
        return this.data.list;
    }

    /**
     * I will acknowledge the given $alarm.
     *
     * @param array $alarm
     *
     * @return bool
     */
    async acknowledge(alarm) {
        let acknowledged = false;
        await this.doRequest("acknowledge", alarm).then((data) => {
            acknowledged = data.response;
        });
        return acknowledged;
    }

    /**
     * I will acknowledge all alarms.
     *
     * @return bool
     */
    async acknowledgeAll() {
        let acknowledged = false;
        await this.doRequest("acknowledgeAll").then((data) => {
            acknowledged = data.response;
        });
        return acknowledged;
    }
}
