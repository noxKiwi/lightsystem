<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Api\LightSystem\AlarmClient;

use DateTime;
use Exception;
use noxkiwi\core\ErrorHandler;
use noxkiwi\core\Helper\DateTimeHelper;
use noxkiwi\lightsystem\Api\LightSystem\BaseClient\OpcItem;
use noxkiwi\lightsystem\Api\LightSystem\BaseClient\OpcNode;
use const E_USER_NOTICE;

/**
 * I am an alarm setup.
 *
 * @package      noxkiwi\lightsystem\Api\LightSystem\AlarmClient
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2022 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
class HysteresisData
{
    public int $hysteresisTime;
    public float $hysteresisValue;
}

