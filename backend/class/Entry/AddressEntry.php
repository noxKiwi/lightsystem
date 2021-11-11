<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Model;

use noxkiwi\dataabstraction\Entry;

/**
 * I am the storage for different failure classes
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan@nox.kiwi>
 * @license      https://nox.kiwi/license
 * @copyright    2018 - 2020 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
class AddressEntry extends Entry
{
    public const ADDRESS = 'address_address';

    /**
     * I will return the address.
     * @return string
     */
    public function getAddress(): string
    {
        return $this->{self::ADDRESS};
    }
}
