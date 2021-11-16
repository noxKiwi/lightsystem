<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Model;

use noxkiwi\dataabstraction\Entry;

/**
 * I am the storage for different failure classes
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2018 - 2020 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
class UserEntry extends Entry
{
    public const USERNAME = 'user_username';

    /**
     * I will return the user name.
     * @return string
     */
    public function userName(): string
    {
        return $this->{self::USERNAME};
    }
}
