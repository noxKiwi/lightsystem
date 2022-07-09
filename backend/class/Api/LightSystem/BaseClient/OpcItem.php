<?php declare(strict_types=1);

namespace noxkiwi\lightsystem\Api\LightSystem\BaseClient;

use noxkiwi\dataabstraction\Entry;

/**
 * I am an alarm entry.
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2019 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
class OpcItem
{
    public int $opcItemId;
    public string $opcItemAddress;

    public static function fromEntry(Entry $entry): static
    {
        $a = new self;
        $a->opcItemAddress = $entry->opc_item_address;
        $a->opcItemId = $entry->opc_item_id;
        return $a;
    }
}

