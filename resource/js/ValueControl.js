"use strict";

class ValueControl extends Control
{
    render() {
        this.renderProperty("name");
        this.renderProperty("value");
        this.renderProperty("unit");
        this.renderProperty("min");
        this.renderProperty("max");
        this.renderProperty("precision");
        this.renderProperty("timestamp");
        this.renderProperty("tag");
        return this;
    }

    prepare(pointer, tag, name, value, unit, min, max, precision, timestamp) {
        this.pointer   = pointer;
        this.tag       = tag;
        this.name      = name;
        this.value     = value;
        this.unit      = unit;
        this.min       = min;
        this.max       = max;
        this.precision = precision;
        this.timestamp = timestamp;
    }

    renderProperty(propertyName) {
        let element = $(this.pointer).find("[data-propertyName=\"" + propertyName + "\"]");
        if (element.length === 0) {
            return;
        }
        let animType = element.attr("data-propertyTarget");
        switch (animType) {
            case "html":
                element.html(this[propertyName]);
                break;
            case "value":
                element.val(this[propertyName]);
                break;
            default:
                element.html(this[propertyName]).val(this[propertyName]);
                Log.Error("Kenne animtype " + animType + " für Eigenschaft " + propertyName + " leider nicht =(");
                break;
        }
    }
}
