"use strict";

/**
 * I am the main class for the rslightsystem
 * @author Jan Nox <jan.nox@pm.me>
 * @requires FrontendManager
 * @requires ItemManager
 * @requires Log
 */
class lightsystem extends Core
{
    constructor() {
        Loader.show("body");
        super();
        this.pm = new PanelManager();
        this.fm = FrontendManager.getInstance();
        // Disable rightclick
        //        document.addEventListener("contextmenu", event => event.preventDefault());

        bindPopover();

        $("body").delegate("[data-click!=''][data-click]", "click", function () {
            let action = $(this).attr('data-click'),
                clickDataObject = JSON.parse($(this).attr("data-clickdata"))[0];
            console.log(action);
            console.log(clickDataObject);
            if (typeof (clickDataObject) !== "object") {
         //       return false;
            }

            if (typeof (clickDataObject.attribute) !== "string") {
         //       return false;
            }

            switch (action) {
                case "write":
                    let tag = $(this).find('[finaltag]').last().attr('finaltag');
                    let val = "VOID";
                    ItemManager.write(tag, val);
                    return true;
            }

        });

        $("#fbSuccess").jqxNotification({
            width              : 400,
            position           : "top-right",
            opacity            : 0.9,
            autoOpen           : false,
            animationOpenDelay : 800,
            autoClose          : true,
            autoCloseDelay     : 3000,
            template           : "success"
        });
        $("#fbError").jqxNotification({
            width              : 400,
            position           : "top-right",
            opacity            : 0.9,
            autoOpen           : false,
            animationOpenDelay : 800,
            autoClose          : true,
            autoCloseDelay     : 3000,
            template           : "error"
        });
        $("#fbInfo").jqxNotification({
            width              : 400,
            position           : "top-right",
            opacity            : 0.9,
            autoOpen           : false,
            animationOpenDelay : 800,
            autoClose          : true,
            autoCloseDelay     : 3000,
            template           : "info"
        });
    }
}
