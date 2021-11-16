<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Api\LightSystem\AbstractClient;

/**
 * I am the BaseClient interface.
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2019 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
interface AbstractClientInterface
{
    /**
     * I will return the change counter of this service.
     * @return int
     */
    public function getCounter(): int;

    /**
     * I will return whether the $serverName is connected or not.
     * $serverName defaults to the currently set up master Server.
     *
     * @param string|null $serverName
     *
     * @return bool
     */
    public function isConnected(?string $serverName = null): bool;

    /**
     * I will set the connection to the given $serverName.
     *
     * @param string $serverName
     */
    public function setServer(string $serverName): void;

    /**
     * I will return the list of host names for this service.
     * @return string[]
     */
    public function getServers(): array;

    /**
     * I will return the host name of the connected master server.
     * @return string
     */
    public function getServer(): string;
}
