<?php
declare(strict_types = 1);

use noxkiwi\core\Helper\DateTimeHelper;
use noxkiwi\translator\Translator;

?>
<table class="table table-sm table-striped">
    <tbody>
    <tr>
        <td><?= Translator::get('SERVER_BOX.ADDRESS') ?></td>
        <td><?= $data->server_address ?>:<?= $data->server_port ?></td>
    </tr>
    <tr>
        <td><?= Translator::get('SERVER_BOX.ENDPOINT') ?></td>
        <td><?= $data->server_endpoint ?></td>
    </tr>
    <tr>
        <td><?= Translator::get('SERVER_BOX.CREATED') ?></td>
        <td><?= DateTimeHelper::user($data->server_created) ?></td>
    </tr>
    <tr>
        <td><?= Translator::get('SERVER_BOX.MODIFIED') ?></td>
        <td><?= DateTimeHelper::user($data->server_modified) ?></td>
    </tr>
    </tbody>
</table>
