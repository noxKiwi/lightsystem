<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Model;

use noxkiwi\dataabstraction\Entry;
use noxkiwi\dataabstraction\Model;
use noxkiwi\lightsystem\Exception\AddressNotFoundException;

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

    /**
     * @param string $address
     * @return Entry
     * @throws AddressNotFoundException
     * @throws \noxkiwi\dataabstraction\Exception\EntryMissingException
     * @throws \noxkiwi\singleton\Exception\SingletonException
     */
    public static function getFromAddress(string $address) : Entry
    {
        $a = new static();
        $a->addFilter('opc_item_address', $address);
        $r = $a->search();
        if(empty($r)) {
            throw new AddressNotFoundException("Address $address not found", 42);
        }
        $r = $r[0];

        return self::expect($r['opc_item_id']);
    }
}
