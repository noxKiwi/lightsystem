"use strict";

class Control extends Plugin
{
    constructor(pointer) {
        super();
        this.pointer      = pointer;
        this.btnSize      = "btn btn-sm";
        this.btnGroupSize = "btn-group btn-group-sm";
    }

    static getControlsOfType(ControlName) {
        if (typeof (runtime.controls[ControlName]) === "undefined") {
            return [];
        }
        return runtime.controls[ControlName];
    }

}







