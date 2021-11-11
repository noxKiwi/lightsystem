"use strict";

class BargraphAnimation
{
    static animate(pointer, value) {
        $(pointer).parents("svg").first().each(function () {
            let minValue     = $(this).data("min"),
                maxValue     = $(this).data("max"),
                abs          = maxValue - minValue,
                height       = $(this).attr("height"),
                ratio        = height / abs,
                displayValue = Math.max(minValue, Math.min(maxValue, value.value)),
                newHeight    = Math.abs(minValue - displayValue) * ratio;
            $(this).attr("title", value.value);
            $(this).find(".foreground").attr({
                y      : (height - newHeight),
                height : newHeight
            });
        });
    }
}

Animate.register("bargraph", BargraphAnimation.animate);
