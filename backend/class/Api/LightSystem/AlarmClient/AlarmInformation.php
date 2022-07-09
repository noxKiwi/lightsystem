<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Api\LightSystem\AlarmClient;

use DateTime;
use Exception;
use noxkiwi\core\ErrorHandler;
use noxkiwi\core\Helper\DateTimeHelper;
use noxkiwi\lightsystem\Api\LightSystem\BaseClient\Comparison;
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
class AlarmInformation
{
    public string $name;
    // NODE
    public OpcNode $opcNode;
    // READ ITEM
    public OpcItem $read;
    // DISENGAGED ITEM
    public OpcItem $disengaged;
    // WRITE ITEM
    public OpcItem $write;
    // ACKNOWLEDGE ITEM;
    public OpcItem $acknowledgement;
    // ENGAGED ITEM
    public OpcItem $engaged;

    // HYSTERESIS
    public HysteresisData $hysteresis;

    // COMPARISON
    public Comparison $comparison;

    // STATUS FLAGS
    public string $flagOn;
    public string $flagOff;
}

