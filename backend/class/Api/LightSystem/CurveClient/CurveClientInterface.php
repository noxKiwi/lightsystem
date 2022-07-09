<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Api\LightSystem\CurveClient;

use noxkiwi\lightsystem\Api\LightSystem\AbstractClient\AbstractClientInterface;

/**
 * I am the interface for the Curve Client..
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2019 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
interface CurveClientInterface extends AbstractClientInterface
{
    /**
     * I will read the current value of the given $address.
     *
     * @return mixed
     */
    public function getGroups();

    /**
     * I will simply write the given $value on the given $address.
     *
     * @param int $groupId
     */
    public function getGroup(int $groupId);

    /**
     * I will create a new group.
     *
     * @param array $options
     *
     * @return mixed
     */
    public function setGroup(array $options);
}

