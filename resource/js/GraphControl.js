"use strict";

class GraphControl extends Control
{
    /**
     * I will construct a new curve display on the given pointer.
     * Constructing the curve display will also create the control form for setting the time-range and other stuff.
     */
    constructor(pointer) {
        super();
        $("#" + pointer).append(`
<div id="` + pointer + `rsLChartHeader" class="rsLChartHeader">
    <table>
        <tbody>
            <tr>
                <td>
                    <button class="jqxButton" data-action="fastback">|&lt;</button>
                    <button class="jqxButton" data-action="back">&lt;&lt;</button>
                    <button class="jqxButton" data-action="moveback">&lt;</button>
                    <button class="jqxButton" data-action="moveforward">&gt;</button>
                    <button class="jqxButton" data-action="forward">&gt;&gt;</button>
                    <button class="jqxButton" data-action="fastforward">&gt;|</button>
                </td>
                <td>
                    <div class="jqxDateTimeInput inpStart"></div>
                </td>
                <td>
                    <div class="jqxDateTimeInput inpEnd"></div>
                </td>
                <td>
                    <select class="rsLChartRange">
                        <option value="3600">1h</option>
                        <option value="43200">12h</option>
                        <option value="86400">1d</option>
                        <option value="604800">1w</option>
                        <option value="1209600">2w</option>
                        <option value="2419200">4w</option>
                    </select>
                </td>
                <td>
                    <button class="jqxButton" data-action="reload">R</button>
                </td>
            </tr>
        </tbody>
    </table>
    <div style="height:100%;width:100%;flex:1 1 0%" id="` + pointer + `rsLChartCanvas">LOAD</div>
    `);
        this.chart = Highcharts.chart(pointer + "rsLChartCanvas", {
            chart       : {
                zoomType : "xy"
            },
            title       : {
                text : null
            },
            xAxis       : {
                crosshair : true,
                type      : "datetime"
            },
            yAxis       : {
                title : {
                    text : null
                }
            },
            plotOptions : {
                area   : {
                    fillColor : {
                        linearGradient : {
                            x1 : 0,
                            y1 : 0,
                            x2 : 0,
                            y2 : 1
                        },
                        stops          : [[0, Highcharts.getOptions().colors[0]], [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get("rgba")]]
                    },
                    marker    : {
                        radius : 2
                    },
                    lineWidth : 1,
                    states    : {
                        hover : {
                            lineWidth : 1
                        }
                    },
                    threshold : null
                },
                series : {
                    animation : false
                }
            },
            legend      : {
                layout : "horizontal",
                align  : "center"
            },
            tooltip     : {
                xDateFormat : "%d.%m.%Y %H:%M:%S",
                shared      : true
            }
        });

        this.variables = $("#" + pointer).data("tags").split(",");
        this.pointer   = pointer;
        this.reading   = false;
        this.timeEnd   = Date.now() / 1000;
        this.timeRange = 3600;
        this.timeStart = this.timeEnd - this.timeRange;

        $("#" + pointer + " button").click(function () {
            let myAction = $(this).data("action");
            switch (myAction) {
                case "fastback":
                    highchartsExtender.getInstance(pointer).moveTimeRange(-1);
                    break;
                case "back":
                    highchartsExtender.getInstance(pointer).moveTimeRange(-.5);
                    break;
                case "forward":
                    highchartsExtender.getInstance(pointer).moveTimeRange(.5);
                    break;
                case "fastforward":
                    highchartsExtender.getInstance(pointer).moveTimeRange(1);
                    break;
                case "reload":
                    highchartsExtender.getInstance(pointer).reload();
            }
        });

        $("#" + pointer + " .jqxButton").jqxButton({
            width  : 30,
            height : 30
        });
        $("#" + pointer + " .inpStart").jqxDateTimeInput({
            formatString   : "dd.MM.yyyy HH:mm",
            showTimeButton : true,
            width          : "200px",
            height         : "25px",
            culture        : "de-DE",
            max            : Date(),
            value          : new Date(this.timeStart * 1000)
        });
        $("#" + pointer + " .inpEnd").jqxDateTimeInput({
            formatString   : "dd.MM.yyyy HH:mm",
            showTimeButton : true,
            width          : "200px",
            height         : "25px",
            culture        : "de-DE",
            max            : Date(),
            value          : new Date(this.timeEnd * 1000)
        });
        $("#" + pointer + " .rsLChartRange").change(function () {
            let end  = Date.now() / 1000;
            let diff = $(this).val();
            highchartsExtender.getInstance(pointer).setTimeRange(end - diff, end);
        });
    }

    /**
     * I will return the instance of the chartHelper for the given pointer.
     * @param string pointer
     * @returns highchartsExtender
     */
    static getInstance(pointer) {
        if (typeof (runtime.instances.highchartsExtender) === "undefined") {
            runtime.instances.highchartsExtender = {};
        }
        if (typeof (runtime.instances.highchartsExtender[pointer]) === "undefined") {
            runtime.instances.highchartsExtender[pointer] = new highchartsExtender(pointer);
        }
        return runtime.instances.highchartsExtender[pointer];
    }

    /**
     * I will return the highChart instance of this helper.
     */
    getChart() {
        return this.chart;
    }

    /**
     * I will start reading the auto-update.
     * @returns {boolean}
     */
    readStart() {
        if (this.reading) {
            return false;
        }

        this.reading = true;

        return true;
    }

    /**
     * I will stop reading the auto-update.
     * @returns {boolean}
     */
    readStop() {
        if (! this.reading) {
            return false;
        }

        this.reading = false;

        return true;
    }

    /**
     * I will add a point to the instance.
     * @param timestamp timestamp
     * @param number value
     */
    addPoint(timestamp, value) {
        this.getChart().addPoint([timestamp, value], true, true);
    }

    /**
     * I will add the new series with the given address to the chart instance.
     * I will output a warning if the chart already has this address.
     * I will return TRUE on success.
     * I will return FALSE on errors.
     * @returns {boolean}
     * @param address
     */
    addSeries(address) {
        if (this.seriesExists(address)) {
            feedback.Warning("Die Variable " + address + " ist bereits im Chart vorhanden!");
            return false;
        }

        this.variables.push(address);

        this.drawSeries(address);
        return true;
    }

    /**
     * I will call the backend service to get the data for the given address and have this data drawn.
     * @param address
     */
    drawSeries(address) {
        let instnc = this;
        Loader.show("#" + instnc.pointer);
        Core.ajaxRequest({
            url : "/?context=archive&view=data&dev=1&address=" + address + "&start=" + this.timeStart + "&end=" + this.timeEnd
        }).then(function (data) {
            instnc.getChart().addSeries({
                type : "line",
                name : data.series_name,
                data : data.series_data
            });
            Loader.hide("#" + instnc.pointer);
        });
    }

    /**
     * I will return TRUE if the given address is already a part of this chart.
     * @param seriesNames
     * @returns {boolean}
     */
    seriesExists(seriesNames) {
        return this.variables.indexOf(seriesNames) !== -1;
    }

    /**
     * I will return the given address from the chart.
     * I will return TRUE on success.
     * I will return FALSE on error.
     * @param seriesName
     * @returns {boolean}
     */
    removeSeries(seriesName) {
        if (! this.seriesExists(seriesName)) {

            return false;
        }

        let seriesLength = this.getChart().series.length;
        let seriesIndex;
        for (seriesIndex = seriesLength - 1; seriesIndex > -1; seriesIndex--) {
            if (this.getChart().series[seriesIndex].name === seriesName) {
                this.getChart().series[seriesIndex].remove();
                return true;
            }
        }
        return false;
    }

    /**
     * I will set the start and end of the chart and have it drawn again.
     * @param start
     * @param end
     */
    setTimeRange(start, end) {
        this.timeStart = start;
        this.timeEnd   = end;
        $("#" + this.pointer + " .inpEnd").val(new Date(this.timeEnd * 1000));
        $("#" + this.pointer + " .inpStart").val(new Date(this.timeStart * 1000));
        return this.drawAgain();
    }

    /**
     * I will clear the chart area and I will also use drawSeries to draw all series again.
     */
    drawAgain() {
        this.clearSeries();

        let variableCount = this.variables.length;
        let variableIndex;

        for (variableIndex = 0; variableIndex <= variableCount - 1; variableIndex++) {
            let currentVariable = this.variables[variableIndex];
            this.drawSeries(currentVariable);
        }
    }

    /**
     * I will remove all series from the chart.
     * @returns {boolean}
     */
    clearSeries() {

        let variableCount = this.variables.length;
        let variableIndex;

        for (variableIndex = 0; variableIndex <= variableCount - 1; variableIndex++) {
            let currentVariable = this.variables[variableIndex];
            this.removeSeries(currentVariable);
        }

        return true;
    }

    /**
     * I will use the current time range and move it by the difference multiplied with the given factor.
     * @param factor
     */
    moveTimeRange(factor) {
        let diff = factor * (this.timeEnd - this.timeStart);
        this.setTimeRange(this.timeStart + diff, this.timeEnd + diff);
        return;
    }

    reload() {
        let diff = this.timeEnd - this.timeStart;
        let end  = Date.now() / 1000;
        this.setTimeRange(end - diff, end);
    }

}
