"use strict";

/**
 * I am the alarm conf control.
 */
class AlarmConfigControl extends Control
{
    static getInstance() {
        if (typeof (runtime.instances.AlarmConfigControl) === "undefined") {
            runtime.instances.AlarmConfigControl = new AlarmConfigControl();
        }
        return runtime.instances.AlarmConfigControl;
    }

    static show(tag) {
        PanelManager.showPanel(95);
        alarmManager.getInfo(tag, function (response) {
            AlarmConfigControl.getInstance().prepare("#95Content", response.alarm);
            AlarmConfigControl.getInstance().render();
        });
    }

    prepare(pointer, alarm) {
        this.pointer               = pointer;
        this.alarm_id              = alarm.alarm_id;
        this.alarm_value           = alarm.alarm_value;
        this.alarm_comparator      = alarm.alarm_comparator;
        this.monitor_id            = alarm.monitor_id;
        this.alarm_hysteresistime  = alarm.alarm_hysteresistime;
        this.alarm_hysteresisvalue = alarm.alarm_hysteresisvalue;
    }

    renderProperty(propertyName) {
        let element = $(this.pointer).find("[data-propertyName=\"" + propertyName + "\"]");
        if (element.length === 0) {
            return;
        }
        let animType = element.attr("data-propertyTarget");
        switch (animType) {
            case "html":
                element.html(this[propertyName]);
                break;
            case "value":
                element.val(this[propertyName]);
                break;
            default:
                element.html(this[propertyName]).val(this[propertyName]);
                Log.Error("Kenne animtype " + animType + " für Eigenschaft " + propertyName + " leider nicht =(");
                break;
        }
    }

    render() {
        this.renderProperty("alarm_id");
        this.renderProperty("alarm_value");
        this.renderProperty("alarm_comparator");
        this.renderProperty("alarm_hysteresistime");
        this.renderProperty("alarm_hysteresisvalue");
        return this;
    }
}
