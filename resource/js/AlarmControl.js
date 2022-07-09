"use strict";

/**
 * I am the alarm control.
 */
class AlarmControl extends TableControl
{
    constructor(pointer) {
        super(pointer);
        Translate.addTranslations("enUS", {
            alarm_aggregate       : "Aggregate",
            alarm_engaged         : "Engaged",
            alarm_disengaged            : "Disengaged",
            alarm_acknowledged    : "Acknowledged",
            alarm_acknowledge_all : "Acknowledge all",
            alarm_acknowledge     : "Acknowledge",
            alarm_area            : "Area"
        });
        this.output({
            list : []
        });
        AlarmClient.getInstance().reset();
    }

    output(data) {
        if (typeof (data) !== "object") {
            Log.Error("Data is null");
            return;
        }
        let html = this.outputTable(data.list);
        this.pointer.html(html);
        this.pointer.find("table").DataTable({
                autoWidth      : true,
                deferRender    : true,
                ordering       : true,
                paging         : false,
                processing     : true,
                scrollX        : true,
                scrollY        : 400,
                scrollCollapse : true,
                searching      : false,
                serverSide     : false,
                renderer       : "bootstrap"
        });
    }

    /**
     * I will build the "acknowledge all" button.
     * @returns {string}
     */
    buildAcknowledgeAllButton() {
        return `<button type="button" class="btn btn-sm btn-danger btn-primary ackAllButton" title="` + Translate.get("alarm_acknowledge_all") + `"><i class="fas fa-times"></i></button>`;
    }

    outputTable(alarms) {
        return `
        ` + this.buildAcknowledgeAllButton() + `
<table class="table table-sm table-striped w-100">
    <thead>
        <tr>
            <th>` + Translate.get("alarm_name") + `</th>
            <th>` + Translate.get("alarm_area") + `</th>
            <th>` + Translate.get("alarm_engaged") + `</th>
            <th>` + Translate.get("alarm_disengaged") + `</th>
            <th>` + Translate.get("alarm_acknowledged") + `</th>
        </tr>
    </thead>
    <tbody>
        ` + this.outputRows(alarms) + `
    </tbody>
</table>
`;
    }

    outputRows(alarms) {
        if (typeof (alarms) !== "object") {
            return;
        }
        let alarmCount = alarms.length - 1,
            alarmIndex = 0,
            html       = "";
        for (alarmIndex = 0; alarmIndex <= alarmCount; alarmIndex++) {
            let alarm = alarms[alarmIndex];
            html      = html + this.outputRow(alarm);
        }
        return html;
    }

    outputRow(alarm) {
        let style         = "warning",
            onclick       = "",
            title         = "",
            outputDisengaged    = alarm.disengaged === null ? "-" : alarm.disengaged,
            outputAckdate = alarm.ackdate === null ? "-" : alarm.ackdate;
        if (typeof (alarm.engaged) !== "string") {
            return '';
        }
        if (typeof (alarm.ackdate) !== "string") {
            style   = "danger cursor-pointer";
            onclick = `onclick="AlarmClient.getInstance().acknowledge('` + alarm.address + `')"`;
            title   = `title="` + Translate.get("alarm_acknowledge") + `"`;
        }
        if (typeof (alarm.disengaged) === "string") {
            style = "success";
        }
        return `
<tr class="alarmRow table-` + style + `" ` + onclick + title + ` >
    <td class="text-sm">` + alarm.name + `</td>
    <td class="text-sm">` + alarm.area + `</td>
    <td class="text-sm">` + alarm.engaged + `</td>
    <td class="text-sm">` + outputDisengaged + `</td>
    <td class="text-sm">` + outputAckdate + `</td>
</tr>
`;
    }
}
