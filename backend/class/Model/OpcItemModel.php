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
final class OpcItemModel extends Model
{
    public const TABLE           = 'opc_item';
    public const    FLAG_ENABLED = 1;
    public const    FLAG_PERSISTENT = 2;
    public const    FLAG_WRITEABLE  = 4;
    public const    FLAG_ARCHIVE    = 8;
    public const    FLAGS           = [
        self::FLAG_ENABLED,
        self::FLAG_PERSISTENT,
        self::FLAG_WRITEABLE,
        self::FLAG_ARCHIVE
    ];
}
