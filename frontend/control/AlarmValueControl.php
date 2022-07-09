<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem;

use noxkiwi\core\Request;

$request = Request::getInstance();

echo <<<HTML
<div class="card-body control"
        data-control="AlarmValueControl"
        id="95Content"
        class="AlarmValueControl"
        tag="{$request->get('panel_data>data>tag', 'ASNAEB')}"
        tag="{$request->get('panel_data>data>tag', 'ASNAEB')}">
    <div class="row">
        <h5 class="card-title" data-propertyName="name" data-propertyTarget="html"></h5>
    </div>
    <div class="row">
        <div class="input-group input-group-sm mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">Comparison</span>
            </div>
            <select data-propertyName="response.comparison.comparisonType" data-propertyTarget="value">
                <option value="EQ">==</option>
                <option value="NEQ">!=</option>
                <option value="GT">&gt;</option>
                <option value="GTE">&gt;=</option>
                <option value="LT">&lt;</option>
                <option value="LTE">&lt;=</option>
            </select>
            <input type="number" class="form-control" value="" aria-describedby="basic-addon1" data-propertyName="response.comparison.comparisonValue" data-propertyTarget="value">
        </div>
        <div class="input-group input-group-sm mb-3">
            <input type="hidden" data-propertyName="alarm_id" data-propertyTarget="value"/>
            <div class="input-group-prepend">
                <span class="input-group-text">Hysteresis Value</span> 
            </div>
            <input type="number" class="form-control" value="" aria-describedby="basic-addon1" data-propertyName="response.hysteresis.hysteresisValue" data-propertyTarget="value">
        </div>
        <div class="input-group input-group-sm mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">Hysteresis Time</span>
            </div>
            <input type="number" class="form-control" value="" aria-describedby="basic-addon1" data-propertyName="response.hysteresis.hysteresisTime" data-propertyTarget="value">
        </div>
        
        <button class="btn btn-success">Speichern</button>
    </div>
    <div class="alert alert-primary" role="alert" data-propertyName="response.description">
        If %ADDR% %COMPARATOR% %VALUE% + %HYSTERESISVALUE% for longer than %HYSTERESISTIME%, set the alarm value to %VALUEON%
    </div>
</div>
HTML;