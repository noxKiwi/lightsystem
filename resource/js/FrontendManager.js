"use strict";

/**
 * I will manage all front-end updates.
 * @author Jan Nox <jan@nox.kiwi>
 **/
class FrontendManager
{
    constructor() {
        this.resetUpdater();
        this.resetBlink();
        return true;
    }

    static DomUpdated() {
        TagManager.findTags();
        if (! runtime.threads.update.initialized) {
            runtime.threads.update.initialized = true;
        }
    }

    static getInstance() {
        if (typeof (runtime.instances.FrontendManager) !== "object") {
            runtime.instances.FrontendManager = new FrontendManager();
        }
        return runtime.instances.FrontendManager;
    }

    clearBlink() {
        if (typeof (runtime.Intervals.AnimateBlink) !== "undefined") {
            clearInterval(runtime.Intervals.AnimateBlink);
        }
    }

    resetBlink() {
        runtime.Intervals.AnimateBlink = window.setTimeout(Animate.blink, runtime.settings.updateInterval || 333);
    }

    clearUpdater() {
        if (typeof (runtime.Intervals.FrontendManagerGetUpdates) !== "undefined") {
            clearInterval(runtime.Intervals.FrontendManagerGetUpdates);
        }
    }

    resetUpdater() {
        runtime.Intervals.FrontendManagerGetUpdates = window.setTimeout(this.getUpdates, runtime.settings.updateInterval || 1000, this);
    }

    parseAndReset(callbacks) {
        if (typeof (callbacks.jslines) !== "object") {
            return false;
        }

        let cbCount = callbacks.jslines.length;
        let cbIndex = 0;
        for (cbIndex = 0; cbIndex <= cbCount - 1; cbIndex++) {
            let myCallback = callbacks.jslines[cbIndex];
            try {
                eval("" + myCallback);
            } catch (ex) {
                Log.Error(ex);
            }
        }
        return true;
    }

    getUpdates() {
        FrontendManager.getInstance().clearUpdater();
        Core.ajaxRequest({
            url : "/?context=frontend"
        }).then(function (response) {
            FrontendManager.getInstance().parseAndReset(response);
            FrontendManager.getInstance().resetUpdater();
        });
    }

}
