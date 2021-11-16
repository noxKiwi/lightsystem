<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Api\LightSystem;

use noxkiwi\lightsystem\Api\LightSystem\EventClient\EventClientInterface;

/**
 * I am the EventClient.
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2019 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class EventClient extends AbstractClient implements EventClientInterface
{
    /**
     * I will return the names of the groups.
     * @return string[]
     */
    public function getGroups(): array
    {
        return [
            'Klingel',
            'Netzwerk',
            'Steckdosen'
        ];
    }

    /**
     * I will query for events.
     * @return array
     */
    public function getEvents(): array
    {
        return [];
    }
}
