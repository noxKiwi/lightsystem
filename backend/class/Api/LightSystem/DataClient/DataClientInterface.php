<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Api\LightSystem\DataClient;

use noxkiwi\lightsystem\Api\LightSystem\AbstractClient\AbstractClientInterface;

/**
 * I am the DataClient interface.
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2019 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
interface DataClientInterface extends AbstractClientInterface
{
    /**
     * I will return the names of the groups.
     * @return string[]
     */
    public function getGroups(): array;

    /**
     * I will compress the data and return it.
     * @return array
     */
    public function getData(array $params): array;

    /**
     * @return array
     */
    public function getCompressions(): array;

    /**
     * @return array
     */
    public function getDisplays(): array;

    /**
     * @return array
     */
    public function getNodes(array $params): array;

    public function getTable(array $params): array;
}
