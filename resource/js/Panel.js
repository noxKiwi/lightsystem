"use strict";

/**
 * I am a Panel instance.
 * @author Jan Nox <jan@nox.kiwi>
 **/
class Panel
{
    constructor(render_panel_id, render_panel_name, render_panel_content, render_panel_width, render_panel_height, render_panel_pointer) {
        this.render_panel_id = render_panel_id;
        this.render_panel_name      = render_panel_name;
        this.render_panel_content   = render_panel_content;
        this.render_panel_width     = render_panel_width;
        this.render_panel_height    = render_panel_height;
        this.render_panel_pointer   = render_panel_pointer;

        runtime.visualization.panels[render_panel_id] = this;
    }

    show() {
        $(this.render_panel_pointer).jqxWindow("open");
        $(this.render_panel_pointer).jqxWindow("bringToFront");
    }

    getPointer() {
        return $(this.render_panel_pointer);
    }

    updateTags(tagprefix) {
        $(this.getPointer()).find("[tag]").first().attr("tag", tagprefix);
        TagManager.findTags();
    }
}
