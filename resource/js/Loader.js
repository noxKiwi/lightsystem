"use strict";

let loaders = {};

class Loader
{
    static show(pointer) {
        pointer = pointer || "body";
        if (typeof (loaders[pointer]) === "boolean" && loaders[pointer] === true) {
            return;
        }
        loaders[pointer] = true;
        $(pointer).append("<div class=\"Loader\"><div><h3>" + Translate.get("PLEASE_WAIT") + "</h3><span></span><span></span><span></span><span></span><span></span></div></div>");
        $(pointer).find(".Loader").fadeIn(100);
    }

    static hide(pointer) {
        pointer = pointer || "body";
        if (typeof (loaders[pointer]) !== "boolean" || loaders[pointer] !== true) {
            return;
        }
        loaders[pointer] = false;
        $(pointer).find(".Loader").fadeOut(100);
        window.setTimeout($(pointer).find(".Loader").remove, 100);
    }
}
