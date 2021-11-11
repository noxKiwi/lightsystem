"use strict";

class DateHelper
{
    static stringToTimestamp(text) {
        return Math.round(new Date(text).getTime() / 1000);
    }

    static getFormattedDate(timestamp) {
        let date = new Date(timestamp * 1000);

        let month = date.getMonth() + 1;
        let day   = date.getDate();
        let hour  = date.getHours();
        let min   = date.getMinutes();
        let sec   = date.getSeconds();

        month = (month < 10 ? "0" : "") + month;
        day   = (day < 10 ? "0" : "") + day;
        hour  = (hour < 10 ? "0" : "") + hour;
        min   = (min < 10 ? "0" : "") + min;
        sec   = (sec < 10 ? "0" : "") + sec;

        return date.getFullYear() + "-" + month + "-" + day + " " + hour + ":" + min + ":" + sec;
    }
}
