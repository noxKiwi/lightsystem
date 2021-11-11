"use strict";

class CountvalueControl extends ValueControl
{
    static getInstance() {
        if (typeof (runtime.instances.CountvalueControl) === "undefined") {
            runtime.instances.CountvalueControl = new CountvalueControl();
        }
        return runtime.instances.CountvalueControl;
    }

    static show(tag) {
        PanelManager.showPanel(96);
        ItemManager.getInfo(tag, function (response) {
            CountvalueControl.getInstance().prepare("#99Content>.rsLDM", response.variable.address_name, response.variable.variable_name, response.value, response.variable.group_unit, response.variable.group_min, response.variable.group_max, response.variable.group_precision, "01.10.2013 12:34:56");
            CountvalueControl.getInstance().render();
        });
    }
}
