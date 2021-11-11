"use strict";

class ReadingControl extends ValueControl
{
    static getInstance() {
        if (typeof (runtime.instances.ReadingControl) === "undefined") {
            runtime.instances.ReadingControl = new ReadingControl();
        }
        return runtime.instances.ReadingControl;
    }

    static show(tag) {
        PanelManager.showPanel(97);
        ItemManager.getInfo(tag, function (response) {
            ReadingControl.getInstance().prepare("#97Content", response.variable.address_name, response.variable.variable_name, response.value, response.variable.group_unit, response.variable.group_min, response.variable.group_max, response.variable.group_precision, "01.10.2013 12:34:56");
            ReadingControl.getInstance().render();
        });
    }
}
