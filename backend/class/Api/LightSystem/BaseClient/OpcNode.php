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
class OpcNode
{
    public int $opcNodeId;
    public string $opcNodePrefix;
    public string $opcNodeName;


    public static function fromEntry(Entry $entry): static
    {
        $a = new self;
        $a->opcNodeId = $entry->opc_node_id;
        $a->opcNodePrefix = $entry->opc_node_prefix;
        $a->opcNodeName = $entry->opc_node_name;
        return $a;
    }
}

