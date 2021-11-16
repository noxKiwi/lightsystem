<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Model;

use noxkiwi\log\Traits\LogTrait;

/**
 * I am the storage for different failure classes
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class ItemLogic
{
    use LogTrait;

    /**
     * I will
     *
     * @param \noxkiwi\lightsystem\Model\UserEntry    $user
     * @param \noxkiwi\lightsystem\Model\AddressEntry $address
     * @param                                         $value
     */
    public function write(UserEntry $user, AddressEntry $address, $value): void
    {
        $this->logInfo("User {$user->user_name} writes {$value} to {$address->getAddress()}");
    }
}
