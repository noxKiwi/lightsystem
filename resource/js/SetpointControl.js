"use strict";

class SetpointControl extends ValueControl
{
    static getInstance() {
        if (typeof (runtime.instances.SetpointControl) === "undefined") {
            runtime.instances.SetpointControl = new SetpointControl();
        }
        return runtime.instances.SetpointControl;
    }

    static show(tag) {
        PanelManager.showPanel(98);
        ItemManager.getInfo(tag, function (response) {
            SetpointControl.getInstance().prepare("#98Content", response.variable.address_name, response.variable.variable_name, response.value, response.variable.group_unit, response.variable.group_min, response.variable.group_max, response.variable.group_precision, "01.10.2013 12:34:56");
            SetpointControl.getInstance().render();
        });
    }
}
