<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem;

require_once dirname(__FILE__, 5) . '/bootstrap.php';

use noxkiwi\lightsystem\Consumer\CommandConsumer;
App::getInstance();
$consumer = new CommandConsumer('Command');
$consumer->run();
