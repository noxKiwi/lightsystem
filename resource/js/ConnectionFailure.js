"use strict";
var ConnectionFailureShown = false;

/**
 * I block the entire screen when activated.
 * I can only be shown once.
 */
class ConnectionFailure
{
    /**
     * I will draw the blocker container on the screen if not done already.
     */
    static show() {
        if (ConnectionFailureShown === true) {
            return;
        }
        ConnectionFailureShown = true;
        $("body").append("<div class=\"ConnectionFailure blocked\" style=\"display:none;\"><div><h3>" + Translate.get("GENERAL_NO_CONNECTION") + "</h3></div></div>");
        $("body").find(".ConnectionFailure").fadeIn(100);
    }

    /**
     * I will fade out the blocker container and remove it from the DOM afterwards.
     */
    static hide() {
        if (ConnectionFailureShown !== true) {
            return;
        }
        ConnectionFailureShown = false;
        $("body").find(".ConnectionFailure").fadeOut(100, function () {
            $(this).remove();
            TagManager.bindTags();
        });
    }
}
