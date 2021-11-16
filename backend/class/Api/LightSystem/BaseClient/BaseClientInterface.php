<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Api\LightSystem\BaseClient;

use noxkiwi\lightsystem\Api\LightSystem\AbstractClient\AbstractClientInterface;

/**
 * I am the AlarmClient interface.
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2019 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
interface BaseClientInterface extends AbstractClientInterface
{
    /**
     * I will read the current value of the given $address.
     *
     * @param string $address
     *
     * @return mixed
     */
    public function read(string $address);

    /**
     * I will simply write the given $value on the given $address.
     *
     * @param string $address
     * @param mixed  $value
     */
    public function write(string $address, $value);
}

