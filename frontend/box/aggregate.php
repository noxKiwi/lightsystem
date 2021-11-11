<?php
declare(strict_types = 1);

use noxkiwi\core\Helper\DateTimeHelper;
use noxkiwi\translator\Translator;

?>
<table class="table table-sm table-striped">
    <tbody>
    <tr>
        <td><?= Translator::get('MAIN.SERVER_TIME') ?></td>
        <td><?= DateTimeHelper::server() ?></td>
    </tr>
    <tr>
        <td><?= Translator::get('MAIN.USER_TIME') ?></td>
        <td><?= DateTimeHelper::user() ?></td>
    </tr>
    <tr>
        <td><?= Translator::get('MAIN.OPC_ADDRESS') ?></td>
        <td><?= $data->opcitem_address ?></td>
    </tr>
    <tr>
        <td><?= Translator::get('MAIN.CREATED') ?></td>
        <td><?= DateTimeHelper::user($data->opcitem_created) ?></td>
    </tr>
    <tr>
        <td><?= Translator::get('MAIN.MODIFIED') ?></td>
        <td><?= DateTimeHelper::user($data->opcitem_modified) ?></td>
    </tr>
    <tr>
        <td><?= Translator::get('MAIN.VALUE') ?></td>
        <td></td>
    </tr>
    </tbody>
</table>
