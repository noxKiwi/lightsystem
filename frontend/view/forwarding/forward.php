<?php declare(strict_types=1);
namespace noxkiwi\lightsystem;

use noxkiwi\core\Response;
use noxkiwi\translator\Translator;

?>
<h3><?= Translator::get('FORWARD_TITLE') ?></h3>
<p>
    <?= Translator::get('FORWARD_REF') ?>
    <br/><?= Response::getInstance()->getData('url') ?>
</p>
<p>
    <br/>⬅️ <?= Translator::get('FORWARD_UNTRUSTED', ['url' => '/']) ?>
    <br/>➡️ <?= Translator::get('FORWARD_TRUSTED', ['url' => Response::getInstance()->getData('url')]) ?>
    <?php if (strpos(Response::getInstance()->getData('url'), 'http://') !== false) { ?>
        <br/>❌ <?= Translator::get('FORWARD_NOHTTPS') ?>
        <br/>❌ <?= Translator::get('FORWARD_NOHTTPS_PERSONAL') ?>
        <br/>❌ <?= Translator::get('FORWARD_EXTERNAL') ?>
    <?php } ?>
</p>
