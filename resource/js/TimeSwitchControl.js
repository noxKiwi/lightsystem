"use strict";

/**
 * I am the time switch control.
 */
class TimeSwitchControl extends Control
{
    constructor(pointer) {
        super(pointer);
        this.options = {
            minutes : 15
        };
        Translate.addTranslations("deDE", {});
        Translate.addTranslations("enUS", {});

        this.show($(this.pointer).find("[tag]").attr("tag") + '.SETPOINT');
    }

    static getInstance() {
        if (typeof (runtime.instances.TimeSwitchControl) === "undefined") {
            runtime.instances.TimeSwitchControl = new TimeSwitchControl();
        }
        return runtime.instances.TimeSwitchControl;
    }

    show(tag) {
        let self = this;
        this.getInfo(tag, function (response) {
            self.prepare("#99Content", response.timeswitch);
            self.render();
        });
    }

    getInfo(tag, callback) {
        Core.ajaxRequest({
            url : "/?context=timeswitch&view=get&address=" + tag
        }).then(function (response) {
            callback(response);
        });
    }

    save() {
        let obj;
        if (typeof (runtime.instances.TimeSwitchControl) === "undefined") {
            return;
        }
        obj = {
            monday    : this.getDayString(0),
            tuesday   : this.getDayString(1),
            wednesday : this.getDayString(2),
            thursday  : this.getDayString(3),
            friday    : this.getDayString(4),
            saturday  : this.getDayString(5),
            sunday    : this.getDayString(6)
        };
        Core.ajaxRequest({
            url  : "/?context=timeswitch&view=set&timeswitch_id=1",
            data : obj
        }).then(function (response) {
            Log.Info("Save succeeded");
        });
    }

    /**
     * I will switch the given switch depending on the current switchMode.
     * @param pointer
     */
    static switch(pointer) {
        switch (TsRuntime.drawMode) {
            case "on":
                return TimeSwitchControl.switchOn(pointer);
            case "off":
                return TimeSwitchControl.switchOff(pointer);
            default:
                if ($(pointer).hasClass("on") === true) {
                    TimeSwitchControl.switchOff(pointer);
                    return;
                }
                TimeSwitchControl.switchOn(pointer);
                break;
        }
    }

    /**
     * I will switch on the given switch.
     * @param pointer
     */
    static switchOn(pointer) {
        $(pointer).addClass("on");
        $(pointer).removeClass("off");
    }

    /**
     * I will switch off the given switch.
     * @param pointer
     */
    static switchOff(pointer) {
        $(pointer).addClass("off");
        $(pointer).removeClass("on");
    }

    /**
     * I will switch all switches of the day identified by the pointer's position.
     * @param pointer
     */
    static switchDay(pointer) {
        $(pointer).parent("tr").find(".tsField").each(function () {
            TimeSwitchControl.switch(this);
        });
    }

    /**
     * I will simply switch all the switches.
     * @param pointer
     */
    static switchAll(pointer) {
        $(pointer).parents("table").find(".tsField").each(function () {
            TimeSwitchControl.switch(this);
        });
    }

    /**
     * I will switch all switches in the hour that is set on the given pointer.
     * @param pointer
     */
    static switchHour(pointer) {
        let hour = $(pointer).data("hour");
        $(pointer).parents("table").find("tbody").find("tr").each(function () {
            $(this).find("[data-hour=" + hour + "]").each(function () {
                TimeSwitchControl.switch(this);
            });
        });
    }

    prepare(pointer, tsEntry) {
        this.pointer = pointer;
        this.tsEntry = tsEntry;
    }

    getDayString(day) {
        let returnValue = "";
        $($(this.pointer).find(".tsDayRow")[day]).find(".tsField").each(function () {
            if ($(this).hasClass("on")) {
                returnValue += "1";
                return;
            }
            returnValue += "0";
        });
        return returnValue;
    }

    render() {
        let output = this.drawTop() + `<table class="timeSwitch">` + this.drawHeader() + this.drawBody() + `</table>`;
        $(this.pointer).delay(1000).html(output);

        $(this.pointer).find(".tsField").bind("click", function () {
            TimeSwitchControl.switch(this);
        });
        $(this.pointer).find(".tsDay").bind("click", function () {
            TimeSwitchControl.switchDay(this);
        });
        $(this.pointer).find(".tsHour").bind("click", function () {
            TimeSwitchControl.switchHour(this);
        });
        $(this.pointer).find(".switchAll").bind("click", function () {
            TimeSwitchControl.switchAll(this);
        });
        return this;
    }

    drawTop() {
        return `<div class="row">
    <div class="col-md-3">
        <div class="` + this.btnGroupSize + `" role="group" aria-label="Basic example">
          <button class="` + this.btnSize + ` btn-secondary" onClick="TsRuntime.drawMode='on';" type="button"><i class="fa fa-check"></i></button>
          <button class="` + this.btnSize + ` btn-secondary" onClick="TsRuntime.drawMode='off';" type="button"><i class="fa fa-times"></i></button>
          <button class="` + this.btnSize + ` btn-secondary" onClick="TsRuntime.drawMode='s';" type="button"><i class="fa fa-random"></i></button>
          <button class="` + this.btnSize + ` btn-secondary" onClick="ItemManager.write('` + this.tsEntry.auto_address + `', 'VOID');" type="button" style="color:lime;"><i class="fa fa-power-off"></i></button>
          <button class="` + this.btnSize + ` btn-secondary" onClick="window.runtime.controls.TimeSwitchControl[0].save()"><i class="fa fa-save"></i></button>
        </div>
    </div>
    <div class="col-md-9">
        <h2>` + this.tsEntry.write_address + `</h2>
    </div>
</div>`;
    }

    drawHeader() {
        let output  = `<thead><th class="switchAll"></th>`;
        let colspan = 60 / this.options.minutes;
        for (let hour = 0; hour < 24; hour++) {
            output += `<th style="text-align: left" class="tsHour" data-hour="` + hour + `" colspan="` + colspan + `">` + hour + `</th>`;
        }
        output += `</head>`;
        return output;
    }

    drawBody() {
        let output = `<tbody>` + this.drawRows() + `</tbody>`;
        return output;
    }

    drawRows() {
        let output = "";
        for (let myDay = 0; myDay <= 6; myDay++) {
            output += this.drawRow(myDay);
        }
        return output;
    }

    drawRow(day) {
        let output  = `<tr class="tsDayRow"><td class="tsDay">` + TsRuntime.days[day] + `</td>`;
        let maxCols = 1440 / this.options.minutes;
        for (let offset = 0; offset < maxCols; offset++) {
            let hour = Math.floor(offset * this.options.minutes / 60);
            output += this.drawCell(day, offset, hour);
        }
        output += `</tr>`;
        return output;
    }

    getValueAt(day, hour) {
        let dayString;
        switch (day) {
            case 0:
                dayString = this.tsEntry.timeswitch_monday;
                break;
            case 1:
                dayString = this.tsEntry.timeswitch_tuesday;
                break;
            case 2:
                dayString = this.tsEntry.timeswitch_wednesday;
                break;
            case 3:
                dayString = this.tsEntry.timeswitch_thursday;
                break;
            case 4:
                dayString = this.tsEntry.timeswitch_friday;
                break;
            case 5:
                dayString = this.tsEntry.timeswitch_saturday;
                break;
            case 6:
                dayString = this.tsEntry.timeswitch_sunday;
                break;
        }
        return dayString.charAt(hour);
    }

    /**
     * I will draw a switch cell for the given data.
     */
    drawCell(day, offset, hour) {
        let tsClass = "off",
            myVal   = this.getValueAt(day, offset),
            test    = "";
        if (myVal == 1) {
            tsClass = "on";
        }
        if(offset % 4 == 0) {
            test = 'style="border-left:1px solid white;"';
        }
        return `<td class="tsField ` + tsClass + `" `+ test +` data-hour="` + hour + `" data-day="` + day + `" data-offset="` + offset + `"></td>`;
    }

}
