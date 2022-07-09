"use strict";

/**
 * I am the alarm conf control.
 */
class AlarmValueControl extends Control {
    constructor(pointer) {
        super(pointer);
        Translate.addTranslations("deDE", {});
        Translate.addTranslations("enUS", {});
        this.show(7);
    }

    show(nodeId) {
//        PanelManager.showPanel(95);
        let self = this;
        AlarmClient.getInstance().getInfo(nodeId, function (response) {
            self.render("#95Content", response);
        });
    }

    render(pointer, response) {
        this.alarmInformation = response;
        $(pointer).find('input[data-propertyname="response.hysteresis.hysteresisValue"]').val(response.hysteresis.hysteresisValue);
        $(pointer).find('input[data-propertyname="response.hysteresis.hysteresisTime"]').val(response.hysteresis.hysteresisTime);
        $(pointer).find('select[data-propertyname="response.comparison.comparisonType"]').val(response.comparison.comparisonType);
        $(pointer).find('input[data-propertyname="response.comparison.comparisonValue"]').val(response.comparison.comparisonValue);
        let self = this;
        $(pointer).find('input').change(function () {
            let text = self.buildDescription();
            $(pointer).find('div[data-propertyname="response.description"]').html(text);
        });
        $(pointer).find('button').click(function () {
            console.log(self.buildAlarmInformation());
        });
        $(pointer).find('input[data-propertyname="response.comparison.comparisonValue"]').trigger('change');
        return this;
    }

    getAlarmInformation() {
        return this.alarmInformation;
    }

    buildAlarmInformation() {
        let response = this.getAlarmInformation();
        response.hysteresis.hysteresisValue = $(this.pointer).find('input[data-propertyname="response.hysteresis.hysteresisValue"]').val();
        response.hysteresis.hysteresisTime = $(this.pointer).find('input[data-propertyname="response.hysteresis.hysteresisTime"]').val();
        response.comparison.comparisonType = $(this.pointer).find('select[data-propertyname="response.comparison.comparisonType"]').val();
        response.comparison.comparisonValue = $(this.pointer).find('input[data-propertyname="response.comparison.comparisonValue"]').val();
        return response;
    }

    buildDescription() {
        let info = this.buildAlarmInformation();
        return `If ` + info.read.opcItemAddress + `
 <b>` + info.comparison.comparisonType + `</b>
 <b>` + info.comparison.comparisonValue + `</b> 
 for longer than 
 <b>` + info.hysteresis.hysteresisTime + `s</b>
 , set the alarm value to 
 <b>` + info.flagOn + `</b>`;
    }
}
