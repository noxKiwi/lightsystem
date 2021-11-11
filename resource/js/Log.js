"use strict";

/**
 * I am the base logging class.
 */
class Log
{
    static Error(text) {
        this.writeLog("❌️" + text, 4);
    }

    static Warning(text) {
        this.writeLog("⚠️" + text, 3);
    }

    static Success(text) {
        this.writeLog("✅️" + text, 2);
    }

    static Info(text) {
        this.writeLog("ℹ️" + text, 1);
    }

    static Debug(text) {
        this.writeLog("☑️" + text, 0);
    }

    static writeLog(text, level) {
        if (level >= 3) {
            console.log(text);
        }
    }
}
