<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Api\LightSystem\EventClient;

use noxkiwi\lightsystem\Api\LightSystem\AbstractClient\AbstractClientInterface;

/**
 * I am the AlarmClient.
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2019 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
interface EventClientInterface extends AbstractClientInterface
{
    /**
     * I will return the names of the groups.
     * @return string[]
     */
    public function getGroups(): array;

    /**
     * I will query for events.
     * @return array
     */
    public function getEvents(): array;
}

