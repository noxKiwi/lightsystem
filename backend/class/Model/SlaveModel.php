<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Model;

use noxkiwi\dataabstraction\Model;

/**
 * I am the storage for slave servers.
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2021 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class SlaveModel extends Model
{
    public const    TABLE        = 'slave';
    public const    FLAG_ENABLED = 1;
}
