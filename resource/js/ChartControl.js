"use strict";
Date.prototype.addHours = function(h) {
  this.setTime(this.getTime() + (h*60*60*1000));
  return this;
}

/**
 * I am the ChartControl.
 */
class ChartControl extends TableControl
{
    constructor(pointer) {
        super(pointer);
        Translate.addTranslations("deDE", {});
        Translate.addTranslations("enUS", {});
        this.pointer.html(this.build());
        DataClient.getInstance();
        this.groups        = {};
        this.intervalType  = "D";
        this.intervalValue = 1;
        let beginDate      = new Date();
        beginDate.addHours(-2);
        this.setBegin(beginDate);
        this.setEnd(new Date());
        this.output({
            list : []
        });
        this.bindEvents();
    }

    bindEvents() {
        let self = this;
        // Scrolling back and forth.
        $(this.pointer).find(".ccPaging>button").click(function () {
            let factor = parseFloat($(this).data("scrollfactor"));
            self.moveChart(factor);
        });
        // Selecting an entire new range.
        $(this.pointer).find(".rangeSelector > ul > li > a").click(function() {
            let seconds = parseFloat($(this).data("seconds"));
            self.intervalToNow(seconds);
        });
//        this.getBeginPointer().change(function () {self.loadChart();});
//        this.getEndPointer().change(function () {self.loadChart();});
    }

    /**
     * I will set the interval type.
     * @param interval
     */
    setInterval(interval) {
        this.interval = interval;
        this.pointer.find(".intervalButton > span").html(interval);
    }

    /**
     * I will set the compression type.
     * @param compression
     */
    setCompression(compression) {
        this.compression = compression;
        this.pointer.find(".compressionButton > span").html(compression);
    }

    /**
     * I will update the chart and set the time range, calculated 
     * back as far as the given seconds until NOW.
     * @param seconds
     */
    intervalToNow(seconds) {
        let beginDate      = new Date(),
            endDate      = new Date();
        beginDate.addHours(-1);
        beginDate.addHours(-seconds / 60 / 60);
        this.setBegin(beginDate);
        endDate.addHours(-1);
        this.setEnd(endDate);
        this.output({
            list : []
        });
    }

    getBeginPointer() {
        return $(this.pointer).find(".ccTimeBegin");
    }

    getEndPointer() {
        return $(this.pointer).find(".ccTimeEnd");
    }

    async addSeries(opcItem, compression, interval) {
        let self = this;
        await DataClient.getInstance().getPoints(opcItem, compression, this.ccTimeBegin.toISOString(), this.ccTimeEnd.toISOString(), interval).then((data) => {
            self.chart.addSeries(data.response);
        });
    }

    setGroup(groupName, groupId) {
        let self = this;
        this.removeSeries();
        DataClient.getInstance().getNodes(groupId).then(function (response) {
            self.nodes = response.response;
            let nodeId;
            for (nodeId in self.nodes) {
                let nodeAddress = self.nodes[nodeId];
                self.addSeries(nodeAddress, "AVG", "MINUTE");
            }
        });
    }

    output() {
        let self = this;
        DataClient.getInstance().getGroups().then(function (groupsResponse) {
            self.groups = groupsResponse.response;
            self.updateGroupSelector();
            self.chart = new Highcharts.Chart({
                chart  : {
                    renderTo : "chartArea",
                    type     : "line"
                },
                title  : {
                    text : "Temperatures"
                },
                xAxis  : {
                    type : "datetime"
                },
                series : []
            });
            self.setGroup("Temperature", 7);
        });
    }

    removeSeries() {
        while (this.chart.series.length) {
            this.chart.series[0].remove(true);
        }
        this.chart.redraw();
    }

    setChart(ccTimeBegin, ccTimeEnd) {
        $("ccTimeEnd").val(ccTimeEnd);
        $("ccTimeBegin").val(ccTimeBegin);
    }

    loadChart() {
        let begin        = this.getBeginPointer().val(),
            end          = this.getEndPointer().val();
        this.ccTimeEnd   = new Date(end);
        this.ccTimeBegin = new Date(begin);
        // FAKED
        self.setGroup("Temperature", 7);
    }

    setBegin(beginDate) {
        beginDate.addHours(2);
        this.ccTimeBegin = beginDate;
        this.getBeginPointer().val(this.ccTimeBegin.toISOString().replace("Z", ""));
    }

    setEnd(endDate) {
        endDate.addHours(2);
        this.ccTimeEnd = endDate;
        this.getEndPointer().val(this.ccTimeEnd.toISOString().replace("Z", ""));
    }

    /**
     * I will move the chart's display range by the given factor.
     * The factor is the multiplier for the amount of seconds between BEGIN and END.
     * To scroll back, use negative values for the factor.
     */
    moveChart(factor) {
        let ccTimeBeginUnix    = this.ccTimeBegin.getTime(),       // The unix epoch time stamp of the chart begin.
            ccTimeEndUnix      = this.ccTimeEnd.getTime(),         // The unix epoch time stamp of the chart end.
            ccTimeRange        = ccTimeEndUnix - ccTimeBeginUnix,  // The difference between end and begin in seconds.
            ccTimeScroll       = factor * ccTimeRange,             // The amount of seconds that will be scrolled.
            ccTimeBeginUnixNew = ccTimeBeginUnix + ccTimeScroll,   // The new unix epoch time stamp of the chart begin.
            ccTimeEndUnixNew   = ccTimeEndUnix + ccTimeScroll;     // The new unix epoch time stamp of the chart end.
        this.setBegin(new Date(ccTimeBeginUnixNew));
        this.setEnd(new Date(ccTimeEndUnixNew));
        this.loadChart();
    }

    updateGroupSelector() {
        let groupsString = "",
            groupId;
        for (groupId in this.groups) {
            let groupName = this.groups[groupId];
            groupsString  = groupsString + `<li><a class="dropdown-item ccBtnGroup" data-groupid="` + groupId + `">` + groupName + `</a></li>`;
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
        <li><a class="dropdown-item" data-interval="WEEK" href="#">` + Translate.get("interval_weeks") + `</a></li>
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
        return `<button type="button" class="btn btn-sm btn-primary dcRefresh" title="` + Translate.get("dataMonitorControl_refresh") + `"><i class="fas fa-sync"></i></button>`;
    }
    
    buildRangeSelector() {
        return `
<div class="dropdown rangeSelector">
    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-clock"></i> <span>Ranges</span>
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
        <li><a class="dropdown-item" data-seconds="3600"  data-range="hour">1h</a></li>
        <li><a class="dropdown-item" data-seconds="7200"  data-range="hour">2h</a></li>
        <li><a class="dropdown-item" data-seconds="21600"  data-range="hour">6h</a></li>
        <li><a class="dropdown-item" data-seconds="43200"  data-range="hour">12h</a></li>
        <li><a class="dropdown-item" data-seconds="86400"   data-range="day">d</i></a></li>
        <li><a class="dropdown-item" data-seconds="604800"  data-range="week">w</a></li>
        <li><a class="dropdown-item" data-seconds="2678400" data-range="month">m</a></li>
        <li><a class="dropdown-item" data-seconds="31536000"  data-range="year">y</a></li>
    </ul>
</div>
<div class="btn-group btn-group-sm timeRange" role="group" aria-label="Control">
</div>`;
    }
    
    buildScrollSelector() {
        return `
<div class="btn-group btn-group-sm ccPaging" role="group" aria-label="Control">
    <button type="button" class="btn btn-sm btn-secondary" data-scrollfactor="-1"><i class="fas fa-fast-backward"></i></button>
    <button type="button" class="btn btn-sm btn-secondary" data-scrollfactor="-.5"><i class="fas fa-step-backward"></i></button>
    <button type="button" class="btn btn-sm btn-secondary" data-paging="PlayPause"><i class="fas fa-play"></i></button>
    <button type="button" class="btn btn-sm btn-secondary" data-scrollfactor=".5"><i class="fas fa-step-forward"></i></button>
    <button type="button" class="btn btn-sm btn-secondary" data-scrollfactor="1"><i class="fas fa-fast-forward"></i></button>
</div>`;
    }

    build() {
        return `
    <div class="input-group input-group-sm">
        ` + this.buildRangeSelector() + `
        ` + this.buildScrollSelector() + `
        ` + this.buildRefreshButton() + `
        ` + this.buildGroupSelection() + `
        ` + this.buildCompressorSelection() + `
        ` + this.buildIntervalSelection() + `
            <input type="datetime-local" class="col-md-4 form-control ccTimeBegin" name="from" />
            <input type="datetime-local" class="col-md-4 form-control ccTimeEnd" name="to" />
    </div>
    <div id="chartArea"></div>`;
    }
}
