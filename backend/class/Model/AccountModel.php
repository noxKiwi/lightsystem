<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Model;


use noxkiwi\dataabstraction\Model;

/**
 * I am the storage for different failure classes
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan@nox.kiwi>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class AccountModel extends Model
{
    public const TABLE        = 'account';
    public const    FLAG_ENABLED = 1;
}
