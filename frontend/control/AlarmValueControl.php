<div class="card-body">
    <div class="row">
        <h5 class="card-title" data-propertyName="name" data-propertyTarget="html"></h5>
    </div>
    <div class="row">
        <div class="input-group mb-3">
            <input type="hidden" data-propertyName="alarm_id" data-propertyTarget="value"/>
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">WENN: ( Messwert + </span>
            </div>
            <input type="number" class="form-control" value="" aria-describedby="basic-addon1" data-propertyName="alarm_hysteresisvalue" data-propertyTarget="value">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">)</span>
            </div>
            <select data-propertyName="alarm_comparator" data-propertyTarget="value">
                <option value="equals">==</option>
                <option value="not_equals">!=</option>
                <option value="greater">&gt;</option>
                <option value="greaterOrEqual">&gt;=</option>
                <option value="less">&lt;</option>
                <option value="lessOrEqual">&lt;=</option>
            </select>
            <input type="number" class="form-control" value="" aria-describedby="basic-addon1" data-propertyName="alarm_value" data-propertyTarget="value">
        </div>
    </div>
    <div class="row">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">Dann setze Alarm aktiv nach </span>
            </div>
            <input type="number" class="form-control" value="" aria-describedby="basic-addon1" data-propertyName="alarm_hysteresistime" data-propertyTarget="value">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">Sekunden</span>
            </div>
            <div class="input-group-append">
                <span class="btn btn-success" id="basic-addon2">Speichern</span>
            </div>
        </div>
    </div>
    <div class="row">
        <h7 class="card-title">Messbereich von: <span data-propertyName="min" data-propertyTarget="html"></span> bis <span data-propertyName="max" data-propertyTarget="html"></span><span
                    data-propertyName="unit" data-propertyTarget="html"></span></h7>
    </div>
</div>
