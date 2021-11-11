"use strict";

String.prototype.replaceAll = function (search, replacement) {
    let target = this;
    return target.replace(new RegExp(search, "g"), replacement);
};

/**
 * I will return the value that was passed in the url with the given sParam.
 * If not defined, I will return the given defaultValue.
 * @author Jan Nox <jan@nox.kiwi>
 * @param name
 * @param defaultValue
 * @returns {*}
 */
function getUrlParameter(name, defaultValue) {
    let sPageURL      = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split("&"),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split("=");

        if (sParameterName[0] === name) {
            if (typeof (sParameterName[1]) === "undefined") {
                return defaultValue;
            }
            if (typeof (sParameterName[1]) === "string" && sParameterName[1].length === 0) {
                return defaultValue;
            }
            return sParameterName[1];
        }
    }
    return defaultValue;
};

function getValue(pointer, data) {
    if (typeof (data.value.value) !== "undefined") {
        return data.value.value;
    }

    return "VOID";
}

function getTag(pointer, data) {
    let myUrl           = data.value.tag;
    let myUrlParts      = myUrl.split("#");
    let myUrlPartsCount = myUrlParts.length;

    if (myUrlPartsCount === 1) {
        return myUrlParts[0];
    }

    let resultingUrl   = "";
    let myUrlPartIndex = 0;

    for (myUrlPartIndex = 0; myUrlPartIndex <= myUrlPartsCount - 1; myUrlPartIndex++) {
        let myUrlPart = myUrlParts[myUrlPartIndex];

        if (myUrlPart === "tag") {
            myUrlPart = $(pointer).find("[finaltag]").last().attr("finaltag");
            if (typeof (myUrlPart) !== "string") {
                myUrlPart = $(pointer).attr("finaltag");
            }
        }
        resultingUrl = resultingUrl + myUrlPart;
    }
    return resultingUrl;
}

function makeUrl(pointer, data) {
    let myUrl           = data.value.url;
    let myUrlParts      = myUrl.split("#");
    let myUrlPartsCount = myUrlParts.length;

    if (myUrlPartsCount === 1) {
        return myUrlParts[0];
    }

    let resultingUrl   = "";
    let myUrlPartIndex = 0;

    for (myUrlPartIndex = 0; myUrlPartIndex <= myUrlPartsCount - 1; myUrlPartIndex++) {
        let myUrlPart = myUrlParts[myUrlPartIndex];

        if (myUrlPart === "tag") {
            myUrlPart = $(pointer).find("[finaltag]").attr("finaltag");
        }

        resultingUrl = resultingUrl + myUrlPart;
    }

    return resultingUrl;

}

function triggerResized() {
    window.dispatchEvent(new Event("resize"));
}

function getKey(data) {
    for (let prop in data) {
        return prop;
    }
}

function bindPopover() {
}

$(document).bind("ready", function () {
    new lightsystem();
});

let TsRuntime = {
    drawMode : "s",
    days     : ["Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag", "Sonntag"],
    table    : null
};
