<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Api\LightSystem\AlarmClient;

use noxkiwi\lightsystem\Api\LightSystem\AbstractClient\AbstractClientInterface;

/**
 * I am the AlarmClient interface.
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan@nox.kiwi>
 * @license      https://nox.kiwi/license
 * @copyright    2019 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
interface AlarmClientInterface extends AbstractClientInterface
{
    public const VALENCE_CAME = 1;
    public const VALENCE_GONE = 0;
    public const VALENCE_NONE = -1;
    public const AREAS_ALL    = '*';

    /**
     * I will return the amount of alarms that have not gone yet.
     * @return int
     */
    public function count(): int;

    /**
     * I will return the list of alarms that are currently set off.
     * @return \noxkiwi\lightsystem\Api\lightsystem\AlarmClient\AlarmOccurance[]
     */
    public function list(): array;

    /**
     * I will acknowledge the given $alarm.
     *
     * @param string $alarm
     */
    public function acknowledge(string $alarm): void;

    /**
     * I will acknowledge all alarms.
     */
    public function acknowledgeAll(): void;
}

