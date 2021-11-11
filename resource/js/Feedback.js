"use strict";

class Feedback
{
    static Info(text) {
        $("#fbInfoMessage").html(text);
        $("#fbInfo").jqxNotification("open");
        Log.Info("Server-Feedback: " + text);
    }

    static Success(text) {
        $("#fbSuccessMessage").html(text);
        $("#fbSuccess").jqxNotification("open");
        Log.Success("Server-Feedback: " + text);
    }

    static Warning(text) {
        $("#fbErrorMessage").html(text);
        $("#fbError").jqxNotification("open");
        Log.Warning("Server-Feedback: " + text);
    }

    static Danger(text) {

    }
}
