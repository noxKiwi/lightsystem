"use strict";

Date.prototype.addMonths = function (movement) {
    let date = new Date(this),
        years = Math.floor(movement / 12),
        months = movement - (years * 12);
    if (years) {
        date.setFullYear(date.getFullYear() + years);
    }
    if (months) {
        date.setMonth(date.getMonth() + months);
    }
    return date;
}
Date.prototype.addHours = function(h) {
  this.setTime(this.getTime() + (h*60*60*1000));
  return this;
}
/**
 * I am the DataMonitorControl.
 */
class DataMonitorControl extends TableControl
{
    /**
     * I will construct the DataMonitorControl.
     * @param pointer
     */
    constructor(pointer) {
        // call parent to prepare everything.
        super(pointer);
        // Output the feature code.
        this.output({
            list : []
        });
        this.bindEvents();
        // Set initial setup!
        this.setTime(new Date());
        this.setDisplay("MONTH", "Month");
        this.setInterval("DAY");
        this.setCompression("AVG", "Average");
        this.setGroup(3, "Temperatures");
        // Now run!
        this.runTable();
    }
    
// SETTERS


    /**
     * I will set the interval type.
     * @param intervalType
     */
    setInterval(intervalType) {
        if (this.interval === intervalType) {
            return;
        }
        let intervalText = $(this.pointer).find("[data-interval=\""+intervalType+"\"]").html();
        this.interval = intervalType;
        this.pointer.find(".intervalButton > span").html(intervalText);
        this.setChanged();
    }

    /**
     * I will set the interval type.
     * @param displayType
     * @param displayText
     */
    setDisplay(displayType, displayText) {
        if (this.display === displayType) {
            return;
        }
        // Update date format of input
        // Update date
        this.display = displayType;
        this.pointer.find(".displayButton > span").html(displayText);
        this.setChanged();
        this.setTime(this.time);
        switch(displayType) {
            case "YEAR":
                this.setInterval("MONTH");
                break;
            case "MONTH":
                this.setInterval("DAY");
                break;
            case "DAY":
                this.setInterval("HOUR");
                break;
            case "HOUR":
                this.setInterval("MINUTE");
                break;
            case "MINUTE":
                this.setInterval("SECOND");
                break;
        }
    }
    
    

    /**
     * I will set the compression type.
     * @param compressionType
     * @param compressionText
     */
    setCompression(compressionType, compressionText) {
        if (this.compression === compressionType) {
            return;
        }
        this.compression = compressionType;
        this.pointer.find(".compressionButton > span").html(compressionText);
        this.setChanged();
    }

    /**
     * I will set the archive group.
     * @param groupId
     * @param groupName
     */
    setGroup(groupId, groupName) {
        if (this.groupId === groupId) {
            return;
        }
        this.groupName = groupName;
        this.groupId   = groupId;
        this.pointer.find(".groupButton > span").html(groupName);
        this.setChanged();
    }

    /**
     * I will bind all required events for this DataTableMonitor.
     */
    bindEvents() {
        let self = this;
        DataClient.getInstance();
        this.pointer.find(".intervalSelector > li > a").click(function () {
            let element = $(this);
            self.setInterval(element.attr("data-interval"));
        });
        this.pointer.find(".compressionSelector > li > a").click(function () {
            let element = $(this);
            self.setCompression(element.attr("data-compression"), element.html());
        });
        this.pointer.find(".displaySelector > li > a").click(function () {
            let element = $(this);
            self.setDisplay(element.attr("data-display"), element.html());
        });
        this.pointer.find(".refreshButton").click(function () {
            self.runTable();
        });
        $("body").delegate(".groupSelector > li > a", "click", function () {
            let element = $(this);
            self.setGroup(parseInt(element.attr("data-groupid")), element.html());
        });
        this.pointer.find(".scroll").click(function () {
            let element = $(this);
            self.scrollDisplay(parseInt(element.attr("data-scroll")));
        });
    }
    
    /**
     * I will set the time.
     */
    setTime(time)
    {
        let format = time.getUTCFullYear() + " " + (time.getUTCMonth() + 1);
        this.time = time;
        this.pointer.find(".currentDisplay").val(this.getTimeOutput());
        this.setChanged();
    }
    
    getTimeOutput() {
        let format = this.time.getUTCFullYear();
        if (this.display === "YEAR") {
            return format;
        }
        format = format + "-" + (this.time.getUTCMonth() + 1);
        if (this.display === "MONTH") {
            return format;
        }
        format = format + "-" + (this.time.getUTCDay());
        if (this.display === "DAY") {
            return format;
        }
        
        format = format + "-" + (this.time.getUTCHours());
        if (this.display === "HOUR") {
            return format;
        }
        
        format = format + "-" + (this.time.getUTCMinutes());
        if (this.display === "MINUTE") {
            return format;
        }
        return format;
    }

    /**
     * I will move the chart's display range by the given factor.
     * The factor is the multiplier for the amount of seconds between BEGIN and END.
     * To scroll back, use negative values for the factor.
     */
    scrollDisplay(factor) {
        let date = this.time,
            interval = this.interval;
        if(this.display === "MONTH") {
            this.setTime(date.addMonths(factor));
        }
        if(this.display === "HOUR") {
            this.setTime(date.addHours(factor * 1));
        }
        if(this.display === "DAY") {
            this.setTime(date.addHours(factor * 24));
        }
    }
    
// Build Front-End

    /**
     * I will output the initial Control.
     */
    output() {
        let html = `
<!-- header -->
<div class="input-group input-group-sm">
    <div class="btn-group btn-group-sm" role="group" aria-label="Control">
        ` + this.buildRefreshButton() + `
        ` + this.buildGroupSelection() + `
        ` + this.buildDisplaySelection() + `
        ` + this.buildCompressorSelection() + `
        ` + this.buildIntervalSelection() + `
        <button class="scroll btn btn-sm btn-secondary" type="button" id="button-addon1" data-scroll="-1"><i class="fas fa-backward"></i></button>
        <input type="text" class="currentDisplay form-control form-control-sm" id="basic-url" aria-describedby="basic-addon3" disabled readonly>
        <button class="scroll btn btn-sm btn-secondary" type="button" id="button-addon1" data-scroll="1"><i class="fas fa-forward"></i></button>
    </div>
</div>
<!-- table -->
<div class="tbl"></div>`;
        let self = this;
        this.pointer.html(html);
        DataClient.getInstance().getGroups().then(function (groupsResponse) {
            self.groups = groupsResponse.response;
            self.updateGroupSelector();
        });
    }

    /**
     * I will add all archive groups to the group selector.
     */
    updateGroupSelector() {
        let groupsString = "",
            groupId;
        for (groupId in this.groups) {
            let groupName = this.groups[groupId];
            groupsString  = groupsString + `<li><a class="dropdown-item ccBtnGroup" data-groupid="` + groupId + `" data-groupname="` + groupName + `">` + groupName + `</a></li>`;
        }

        $(this.pointer).find(".groupSelector").html(null).html(groupsString);
    }

    /**
     * I will build the dropdown for the archive groups.
     * @returns {string}
     */
    buildGroupSelection() {
        return `
<div class="dropdown">
    <button class="groupButton btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-boxes"></i> <span></span>
    </button>
    <ul class="groupSelector dropdown-menu" aria-labelledby="dropdownMenuButton1">
        <li><a class="dropdown-item" href="#">Action</a></li>
        <li><a class="dropdown-item" href="#">Another action</a></li>
        <li><a class="dropdown-item" href="#">Something else here</a></li>
    </ul>
</div>`;
    }

    /**
     * I will build the dropdown for the display types.
     * @returns {string}
     */
    buildDisplaySelection() {
        return `
<div class="dropdown">
    <button class="displayButton btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-table"></i> <span>None</span>
    </button>
    <ul class="displaySelector dropdown-menu" aria-labelledby="dropdownMenuButton2">
        <li><a class="dropdown-item" data-display="YEAR">` + Translate.get("display_year") + `</a></li>
        <li><a class="dropdown-item" data-display="MONTH">` + Translate.get("display_month") + `</a></li>
<!--
        <li><a class="dropdown-item" data-display="WEEK">` + Translate.get("display_week") + `</a></li>
-->
        <li><a class="dropdown-item" data-display="DAY">` + Translate.get("display_day") + `</a></li>
        <li><a class="dropdown-item" data-display="HOUR">` + Translate.get("display_hour") + `</a></li>
        <li><a class="dropdown-item" data-display="MINUTE">` + Translate.get("display_minute") + `</a></li>
    </ul>
</div>`;
    }

    /**
     * I will build the dropdown for the compression types.
     * @returns {string}
     */
    buildCompressorSelection() {
        return `
<div class="dropdown">
    <button class="compressionButton btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-calculator"></i> <span>None</span>
    </button>
    <ul class="compressionSelector dropdown-menu" aria-labelledby="dropdownMenuButton2">
        <li><a class="dropdown-item" data-compression="MAX" href="#">` + Translate.get("compression_max") + `</a></li>
        <li><a class="dropdown-item" data-compression="AVG" href="#">` + Translate.get("compression_avg") + `</a></li>
        <li><a class="dropdown-item" data-compression="MIN" href="#">` + Translate.get("compression_min") + `</a></li>
    </ul>
</div>`;
    }

    /**
     * I will build the dropdown for the interval types.
     * @returns {string}
     */
    buildIntervalSelection() {
        return `
<div class="dropdown">
    <button class="intervalButton btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-clock"></i> <span>None</span>
    </button>
    <ul class="intervalSelector dropdown-menu" aria-labelledby="dropdownMenuButton3">
        <li><a class="dropdown-item" data-interval="SECOND" href="#">` + Translate.get("interval_seconds") + `</a></li>
        <li><a class="dropdown-item" data-interval="MINUTE" href="#">` + Translate.get("interval_minutes") + `</a></li>
        <li><a class="dropdown-item" data-interval="HOUR" href="#">` + Translate.get("interval_hours") + `</a></li>
        <li><a class="dropdown-item" data-interval="DAY" href="#">` + Translate.get("interval_days") + `</a></li>
<!--
        <li><a class="dropdown-item" data-interval="WEEK" href="#">` + Translate.get("interval_weeks") + `</a></li>
-->
        <li><a class="dropdown-item" data-interval="MONTH" href="#">` + Translate.get("interval_months") + `</a></li>
        <li><a class="dropdown-item" data-interval="QUARTER" href="#">` + Translate.get("interval_quarters") + `</a></li>
        <li><a class="dropdown-item" data-interval="YEAR" href="#">` + Translate.get("interval_years") + `</a></li>
    </ul>
</div>`;
    }

    /**
     * I will build the refresh button.
     * @returns {string}
     */
    buildRefreshButton() {
        return `<button type="button" class="btn btn-sm btn-secondary btn-primary refreshButton" title="` + Translate.get("dataMonitorControl_refresh") + `"><i class="fas fa-sync refreshIcon"></i></button>`;
    }
    
    setUnchanged() {
        let refreshButton = $(this.pointer).find(".refreshButton"),
            refreshIcon   = $(this.pointer).find(".refreshIcon")
        refreshIcon.removeClass("fa-spin");
        refreshButton.removeClass("btn-warning");
        refreshButton.addClass("btn-secondary");
    }
    
    setChanged() {
        let refreshButton = $(this.pointer).find(".refreshButton");
        refreshButton.removeClass("btn-secondary");
        refreshButton.addClass("btn-primary");
    }
    
    setProcessing() {
        let refreshButton = $(this.pointer).find(".refreshButton"),
            refreshIcon   = $(this.pointer).find(".refreshIcon")
        refreshIcon.addClass("fa-spin");
        refreshButton.removeClass("btn-primary");
        refreshButton.addClass("btn-warning");
    }

    /**
     * I will load a new table according to the Control's current setup.
     */
    runTable() {
        this.setProcessing();
        let tbl  = $(this.pointer).find("div.tbl"),
            self = this;
        tbl.html("");
        DataClient.getInstance().getTable(this.groupId, this.compression, this.display, this.interval, this.time).then(function (response) {
            // Remove current table.
            // Build new table, make it a DataTable.
            tbl.html(response.response.table).find("table").DataTable({
                autoWidth      : true,
                deferRender    : true,
                ordering       : true,
                paging         : false,
                processing     : true,
                scrollX        : true,
                scrollY        : 400,
                scrollCollapse : true,
                searching      : false,
                serverSide     : false,
                renderer       : "bootstrap"
            });
            self.setUnchanged();
        });
    }
}

