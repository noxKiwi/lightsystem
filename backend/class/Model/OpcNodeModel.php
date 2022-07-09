<?php declare(strict_types=1);

namespace noxkiwi\lightsystem\Model;

use noxkiwi\dataabstraction\Entry;
use noxkiwi\dataabstraction\Model;

/**
 * I am the storage for different failure classes
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2019 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class OpcNodeModel extends Model
{
    public const TABLE = 'opc_node';
    public const FLAG_ENABLED = 1;

    /**
     * @param \noxkiwi\dataabstraction\Entry $opcNode
     * @return \noxkiwi\dataabstraction\Entry[]
     * @throws \noxkiwi\dataabstraction\Exception\EntryMissingException
     * @throws \noxkiwi\singleton\Exception\SingletonException
     */
    public static function getOpcItems(Entry $opcNode): array
    {
        $opcItemModel = OpcItemModel::getInstance();
        $opcItemModel->addFilter('opc_node_id', $opcNode->opc_node_id);
        $items = $opcItemModel->search();
        $return = [];
        foreach ($items as $item) {
            $return[] = OpcItemModel::expect($item['opc_item_id']);
        }
        return $return;
    }

}
