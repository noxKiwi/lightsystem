<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem;

use noxkiwi\core\Request;

$request = Request::getInstance();

echo <<<HTML
<div    class="control"
        data-control="TimeSwitchControl"
        id="99Content"
        class="TimeSwitchControl"
        tag="{$request->get('panel_data>data>tag', 'ASNAEB')}">
</div>
HTML;
