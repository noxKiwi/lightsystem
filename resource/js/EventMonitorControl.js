"use strict";

/**
 * I am the event monitor control.
 */
class EventMonitorControl extends TableControl
{
    constructor(pointer) {
        super();
        this.pointer = pointer;
        this.output({
            list : []
        });
        EventClient.getInstance();
    }

    output() {
        let html = this.outputControl();
        this.pointer.html(html);
    }

    outputControl() {
        return this.outputHeader() + this.outputTable();
    }

    outputHeader() {
        let html = this.outputGroups() + `
Server:
<div class="` + this.btnGroupSize + `">
    <button type="button" class="` + this.btnSize + ` btn-secondary active" title="vulpes.nox.kiwi"><i class="fas fa-circle text-success"></i></button>
    <button type="button" class="` + this.btnSize + ` btn-secondary" title="cornix.nox.kiwi"><i class="fas fa-circle text-success"></i></button>
</div>

<div class="` + this.btnGroupSize + `" role="group" aria-label="Button group with nested dropdown">
  <button type="button" class="` + this.btnSize + ` btn-outline-success" title="Aktualisieren"><i class="fas fa-sync"></i></button>
  <button type="button" class="` + this.btnSize + ` btn-outline-primary" title="Exportieren"><i class="fas fa-file-export"></i></button>
</div>

<div class="` + this.btnGroupSize + `" role="group">
    <button id="btnGroupDrop1" type="button" class="` + this.btnSize + ` btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Ausrichtung
    </button>
    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
        <a class="dropdown-item" href="#">H</a>
        <a class="dropdown-item" href="#">V</a>
    </div>
</div>

<div class="` + this.btnGroupSize + `" role="group">
    <button id="btnGroupDrop1" type="button" class="` + this.btnSize + ` btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Gesamtintervall
    </button>
    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
        <a class="dropdown-item" href="#">Jahr</a>
        <a class="dropdown-item" href="#">Quartal</a>
        <a class="dropdown-item" href="#">Monat</a>
        <a class="dropdown-item" href="#">Woche</a>
        <a class="dropdown-item" href="#">Tag</a>
        <a class="dropdown-item" href="#">Stunde</a>
    </div>
</div>

<div class="` + this.btnGroupSize + `" role="group">
    <button id="btnGroupDrop1" type="button" class="` + this.btnSize + ` btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Unterteilung
    </button>
    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
        <a class="dropdown-item" href="#">Quartal</a>
        <a class="dropdown-item" href="#">Monat</a>
        <a class="dropdown-item" href="#">Woche</a>
        <a class="dropdown-item" href="#">Tag</a>
        <a class="dropdown-item" href="#">Stunde</a>
        <a class="dropdown-item" href="#">Minute</a>
    </div>
</div>

<div class="` + this.btnGroupSize + `" role="group">
    <button id="btnGroupDrop1" type="button" class="` + this.btnSize + ` btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Kompression
    </button>
    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
        <a class="dropdown-item" href="#">Durchschnitt</a>
        <a class="dropdown-item" href="#">Minimum</a>
        <a class="dropdown-item" href="#">Maximum</a>
        <a class="dropdown-item" href="#">Perzentil</a>
        <a class="dropdown-item" href="#">Anzahl</a>
    </div>
</div>
`;
        return html;
    }

    outputGroups() {
        let groups      = EventClient.getInstance().getGroups(),
            groupsCount = groups.length - 1,
            groupIndex  = 0,
            html        = `
<div class="` + this.btnGroupSize + `" role="group">
    <button id="btnGroupDrop1" type="button" class="` + this.btnSize + ` btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Gruppe
    </button>
    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">`;

        for (groupIndex = 0; groupIndex <= groupsCount; groupIndex++) {
            let myGroup = groups[groupIndex];
            html        = html + `
    <a class="dropdown-item" href="#">` + myGroup + `</a>
`;
        }
        html = html + `</div></div>`;
        return html;
    }

    outputTable() {
        let data = [];
        return `
<table class="table table-sm table-striped">
</table>
`;
    }
}
