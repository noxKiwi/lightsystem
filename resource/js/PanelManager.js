"use strict";

/**
 * I am the Panel manager
 * @author Jan Nox <jan@nox.kiwi>
 **/
class PanelManager
{

    /**
     *  I am the constructor. I will at least show the default Panel.
     **/
    constructor() {
        if (typeof (runtime.defaults.firstpanel) !== "undefined") {
            PanelManager.showPanel(runtime.defaults.firstpanel, runtime.defaults.firstpaneldata || {});
        }
    }

    /**
     * I will either draw the given render_panel_id new or focus the previously drawn Panel.
     * @param render_panel_id
     * @param panel_data
     * @returns {*}
     */
    static async showPanel(render_panel_id, panel_data) {
        let panelInstance = this.getPanel(render_panel_id);
        if (typeof (panelInstance) === "object") {
            if (typeof (panel_data) !== "undefined" && typeof (panel_data.tag) === "string") {
                panelInstance.updateTags(panel_data.tag);
            }
            return panelInstance.show();
        }
        Loader.show("body");
        if (typeof (panel_data) !== "undefined" && typeof (panel_data.tag) === "string") {
            Hook.add("PANELDRAWN", function (panelInstance) {
                panelInstance.updateTags(panel_data.tag);
            });
        }
        let pData = null;
        await Core.ajaxRequest({
            url  : "/?context=panel&view=show",
            data : {
                render_panel_id : render_panel_id,
                panel_data
            }
        }).then((data) => {
            pData = data;
        });
        PanelManager.drawPanel(pData);
    }

    /**
     * I will return TRUE if the given render_panel_id was already drawn to the DOM.
     * @param render_panel_id
     * @returns {boolean}
     */
    static getPanel(render_panel_id) {
        return runtime.visualization.panels[render_panel_id];
    }

    /**
     * I will return a good standard size(-min/-max) for the given width and height
     */
    static getWindowData(render_panel_width, render_panel_height) {
        let maxDiff      = 2;
        let windowHeight = $(window).height();
        let windowWidth  = $(window).width();

        if ((render_panel_width > windowWidth) || (render_panel_height > windowHeight)) {
            let resolutionOffset = 50;
            let aspectRatio      = render_panel_height / render_panel_width;
            render_panel_width          = windowWidth - resolutionOffset;
            render_panel_height         = render_panel_width * aspectRatio;
        }

        return {
            maxHeight : Math.max(render_panel_height * maxDiff, windowHeight),
            minHeight : render_panel_height / maxDiff,
            maxWidth  : Math.max(render_panel_width * maxDiff, windowWidth),
            minWidth  : render_panel_width / maxDiff,
            width     : render_panel_width,
            height    : render_panel_height
        };
    }

    /**
     * I will draw the given render_panel_id, because it was not drawn yet.
     * @param render_panel_id
     */
    static drawPanel(data) {
        Core.doCallbacks(data);
        Loader.hide("body");
        if (data.result === null) {
            return false;
        }
        if (typeof (data.render_panel_name) !== "string") {
            Log.Error("render_panel_name ist kein string");
            return false;
        }
        if (typeof (data.render_panel_id) !== "number") {
            Log.Error("render_panel_id ist keine Zahl");
            return false;
        }
        if (typeof (data.svg) !== "string") {
            Log.Error("render_panel_svg ist kein string");
            return false;
        }
        if (typeof (data.render_panel_width) !== "number") {
            Log.Error("render_panel_width ist kein string");
            return false;
        }
        if (typeof (data.render_panel_height) !== "number") {
            Log.Error("render_panel_height ist kein string");
            return false;
        }

        $("#rsLMain").append("<div id=\"" + data.render_panel_id + "\"><div id=\"" + data.render_panel_id + "Header\"><span>" + data.render_panel_name + "</span></div><div style=\"overflow: hidden;\" id=\"" + data.render_panel_id + "Content\" class=\"rslightsystemCanvas\">" + data.svg + "</div></div>");
        $("#" + data.render_panel_id).jqxWindow(PanelManager.getWindowData(data.render_panel_width, data.render_panel_height)).on("resized", triggerResized).on("open", triggerResized).on("moved", triggerResized).on("close", triggerResized).on("created", triggerResized).on("collapse", triggerResized);
        let panelInstance = new Panel(data.render_panel_id, data.render_panel_name, data.render_panel_content, data.render_panel_width, data.render_panel_height, $("#" + data.render_panel_id));
        Hook.fireOnce("PANELDRAWN", panelInstance);
        FrontendManager.DomUpdated();

        let controlElements = $("#" + data.render_panel_id).find(".control");
        if (controlElements.length === 0) {
            return;
        }

        let controlName = $(controlElements).data("control"),
            pointer     = $("#" + data.render_panel_id).find(".jqx-window-content");
        console.log(controlName);
        switch (controlName) {
            case "AlarmControl":
                if (typeof (runtime.controls["AlarmControl"]) === "undefined") {
                    runtime.controls["AlarmControl"] = [];
                }
                runtime.controls["AlarmControl"].push(new AlarmControl(pointer));
                break;
            case "TimeSwitchControl":
                if (typeof (runtime.controls["TimeSwitchControl"]) === "undefined") {
                    runtime.controls["TimeSwitchControl"] = [];
                }
                runtime.controls["TimeSwitchControl"].push(new TimeSwitchControl(pointer));
                break;
            case "DataMonitorControl":
                if (typeof (runtime.controls["DataMonitorControl"]) === "undefined") {
                    runtime.controls["DataMonitorControl"] = [];
                }
                runtime.controls["DataMonitorControl"].push(new DataMonitorControl(pointer));
                break;
            case "ChartControl":
                if (typeof (runtime.controls["ChartControl"]) === "undefined") {
                    runtime.controls["ChartControl"] = [];
                }
                runtime.controls["ChartControl"].push(new ChartControl(pointer));
                break;
            case "EventMonitorControl":
                if (typeof (runtime.controls["EventMonitorControl"]) === "undefined") {
                    runtime.controls["EventMonitorControl"] = [];
                }
                runtime.controls["EventMonitorControl"].push(new EventMonitorControl(pointer));
                break;
        }
    }

    /**
     * I will focus this Panel.
     * @param render_panel_id
     */
    static show(render_panel_id) {
        if (typeof (runtime.visualization.panels[render_panel_id]) !== "object") {
            Log.Error("Panel " + render_panel_id + " wurde noch nicht gezeichnet");
        }
        runtime.dvisualization.panels[render_panel_id].show();
        return;
    }
}
