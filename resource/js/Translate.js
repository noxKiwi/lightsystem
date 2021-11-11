"use strict";

window.translations = {
    enUS : {
        JUSTNOW                 : "Just now",
        YESTERDAY               : "Yesterday",
        AGO_SECONDS             : "#data.seconds# seconds ago",
        AGO_MINUTES             : "#data.minutes# minutes ago",
        AGO_HOURS               : "#data.hours# hours ago",
        DAY0                    : "Monday",
        DAY1                    : "Tuesday",
        DAY2                    : "Wednesday",
        DAY3                    : "Thursday",
        DAY4                    : "Friday",
        DAY5                    : "Saturday",
        DAY6                    : "Sunday",
        "compression_max" : "⬆ Maximum",
        "compression_min":"⬇ Minimum",
        "compression_avg":"Ø Average",
        "interval_seconds":"Seconds",
        "interval_minutes":"Minutes",
        "interval_hours":"Hours",
        "interval_days":"Days",
        "interval_weeks":"Weeks",
        "interval_months":"Months",
        "interval_quarters":"Quarters",
        "interval_years":"Years",
        "display_year":"Year",
        "display_month":"Month",
        "display_week":"Week",
        "display_day":"Day",
        "display_hour":"Hour",
        "display_minute":"Minute",
        "SOCKET.SWITCH_ON"      : "Turn on",
        "SOCKET.SWITCH_OFF"     : "Turn off",
        "SOCKET.SWITCH_TOGGLE"  : "Switch",
        "PLEASE_WAIT"           : "Please wait",
        "GENERAL_NO_CONNECTION" : "The connection to the PLC was dropped.",
        "SERVER_PARSE_ERROR"    : "The server response was invalid."
    }
};

let language = "enUS";

/**
 * I am the client-side translation class.
 * @author Jan Nox <jan@nox.kiwi>
 */
class Translate
{

    static setTranslation(language, key, text) {
        return;
        window.translations[language][key] = text;
    }

    static addTranslations(language, translations) {
        if (typeof (window.translations) !== "object") {
            return false;
        }

        for (let myKey in window.translations) {
            if (! window.translations.hasOwnProperty(myKey)) {
                continue;
            }
            let myText = window.translations[myKey];
            Translate.setTranslation(language, myKey, myText);
        }
    }

    /**
     * I will Translate the given code and use the
     * @param string code
     * @param object data
     * @returns {*}
     */
    static translate(code, data) {
        let text = this.getTranslation(code);

        if (typeof (data) !== "object") {
            return text;
        }

        return this.context(text, data);
    }

    static get(code, data) {
        return Translate.translate(code, data);
    }

    /**
     * I will return the translation of the given code.
     * @param string code
     * @returns {*}
     */
    static getTranslation(code) {
        if (typeof (window.translations[language]) !== "object") {
            return code;
        }

        if (typeof (window.translations[language][code]) !== "string") {
            return code;
        }

        return window.translations[language][code];
    }

    /**
     * I will fill the variables of the given $text with data from the given $data.
     * @param string text
     * @param object data
     * @returns {string}
     */
    static context(text, data) {
        let textSegments      = text.split("#");
        let textSegmentsCount = textSegments.length;
        let textSegmentIndex;
        let resultText        = "";

        for (textSegmentIndex = 0; textSegmentIndex <= textSegmentsCount - 1; textSegmentIndex++) {
            let mySegment     = textSegments[textSegmentIndex];
            let mySegmentCode = mySegment.replace("data.", "");

            if (typeof (data[mySegmentCode]) === "undefined") {
                resultText += mySegment;
                continue;
            }

            resultText += data[mySegmentCode];
        }
        return resultText;
    }
}
