"use strict";

/**
 *
 */
class ContextMenu {
    constructor() {
        $("body").delegate("[finaltag]", "click", function (e) {
            ContextMenu.make(this);
            return false;
        });
    }

    /**
     * I will write down the context menu
     */
    static make(pointer) {
        let finalTag = $(pointer).attr("finaltag"),

            elementType = pointer.tagName,
            target = elementType + "[finaltag=\"" + finalTag + "\"]";
        Core.ajaxRequest({
            url: "/?context=item&view=menu&tag=" + finalTag
        }).then(function (response) {
            var definition = {
                selector: target,
                items: response.menu,
                build: function (element, event) {
                    let options = {
                        callback: ContextMenu.open
                    };
                    return options;
                }
            };
            $.contextMenu(definition).contextMenu();

        });
    }

    /**
     * I will work the clicked element from the context menu.
     */
    static open(key, b, c, d, e) {
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
            case "Enable":
                ItemManager.write(myTag + ".STATUS.F_VALUE", true);
                return true;
            case "Disable":
                ItemManager.write(myTag + ".STATUS.F_VALUE", false);
                return true;
            case "Toggle":
                ItemManager.write(myTag + ".STATUS.F_VALUE", "VOID");
                return true;
            case "quit":
                return true;
            case "Control.AlarmValueControl":
                PanelManager.showPanel('control', {control: 'AlarmValueControl', data : {tag:myTag}})
                return true;
            case "Control.ReadingControl":
                ReadingControl.show(myTag + ".MW.SCALE.F_VALUE");
                return true;
            case "Control.TimeSwitchControl":
                PanelManager.showPanel('control', {control: 'TimeSwitchControl', data : {tag:myTag}})
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
