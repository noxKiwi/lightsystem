"use strict";

/**
 *
 */
class ContextMenu
{
    constructor() {
        $("body").delegate("svg [finaltag]", "contextmenu", function () {
            ContextMenu.make(this);
            return false;
        });
    }

    /**
     * I will write down the context menu
     */
    static make(pointer) {
        console.log(pointer);
        let finalTag    = $(pointer).attr("finaltag"),
            elementType = $(pointer).tagName;
        Core.ajaxRequest({
            url : "/?context=item&view=menu&tag=" + finalTag
        }).then(function (response) {
            $(pointer).contextMenu({
                selector : elementType + "[finaltag=\"" + finalTag + "\"]",
                callback : ContextMenu.open,
                build    : function (trigger, event) {
                    let options = {
                        callback : ContextMenu.open,
                        items    : {}
                    };
                    $.each(response.menu, function (index, item) {
                        options.items[index] = item;
                    });
                    console.log(elementType + "[finaltag=\"" + finalTag + "\"]");
                    return options;
                }
            });
            $(pointer).contextMenu();
        });
    }

    /**
     * I will work the clicked element from the context menu.
     */
    static open(key) {
        let myTag = $(this[0]).attr("finaltag");

        if (typeof (myTag) !== "string") {
            Log.Error("Element hat keinen finalTag!");
            return false;
        }

        if (myTag.length === 0) {
            Log.Error("Element hat einen leeren finalTag");
            return false;
        }

        myTag = TagManager.normalizeTag(myTag);

        switch (key) {
            case "graph":

                let chartInstnc = runtime.instances.highchartsExtender[getKey(runtime.instances.highchartsExtender)];

                if (typeof (chartInstnc) !== "object") {
                    Log.Error("Es wurde noch kein Chart gezeichnet.");
                    return false;
                }
                chartInstnc.addSeries(myTag + ".MW.SCALE.F_VALUE", chartInstnc.start, chartInstnc.end, "");

                return true;
            case "on":
                ItemManager.write(myTag + ".STATUS.F_VALUE", true);
                return true;
            case "off":
                ItemManager.write(myTag + ".STATUS.F_VALUE", false);
                return true;
            case "toggle":
                ItemManager.write(myTag + ".STATUS.F_VALUE", "VOID");
                return true;
            case "quit":
                return true;
            case "Control.AlarmConfigControl":
                AlarmConfigControl.show(myTag + ".SM.ALARM.F_VALUE");
                return true;
            case "Control.ReadingControl":
                ReadingControl.show(myTag + ".MW.SCALE.F_VALUE");
                return true;
            case "Control.TimeSwitchControl":
                TimeSwitchControl.show(myTag + ".STATUS.F_VALUE");
                return true;
            case "Control.CountvalueControl":
                CountvalueControl.show(myTag + ".MW.SCALE.F_VALUE");
                return true;
            case "Control.SetpointControl":
                SetpointControl.show(myTag + ".MW.SCALE.F_VALUE");
                return true;
        }
        Log.Error(key + " is unknown.");
    }
}
