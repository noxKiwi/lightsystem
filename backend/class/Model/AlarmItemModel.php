<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Model;

use noxkiwi\dataabstraction\Entry;
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
final class AlarmItemModel extends AuditedModel
{
    public const TABLE           = 'alarm_item';
    public const    FLAG_ENABLED = 1;

    public static function fromNode(Entry $node):Entry
    {
        $a = new static();
        $a->addFilter('opc_node_id', $node->opc_node_id);
        $r = $a->search();

        if(empty($r)) {
            throw new AddressNotFoundException("There is no alarm for node $node->opc_node_id", 42);
        }

        $r = $r[0];

        return static::expect($r['alarm_item_id']);
    }
}
