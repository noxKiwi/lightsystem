<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Frontend\Control;

use noxkiwi\dataabstraction\Entry;
use noxkiwi\lightsystem\Frontend\ContextMenuEntry;
use noxkiwi\lightsystem\Frontend\Control;
use noxkiwi\lightsystem\Model\AlarmItemModel;

/**
 * I am the AlarmValueControl class.
 *
 * @package      noxkiwi\lightsystem\Frontend\Control
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2019 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class AlarmValueControl extends Control
{
    public const IDENTIFIER = 'AlarmValueControl';

    private AlarmItemModel $alarmItemModel;

    public function __construct(array $options = null)
    {
        parent::__construct($options);
        $this->alarmItemModel = AlarmItemModel::getInstance();
    }

    public function getContextMenu(Entry $opcItem): array
    {
        $elements = parent::getContextMenu($opcItem);

        $this->alarmItemModel->addFilter('opc_item_id_alarm', $opcItem->opc_item_id);
        $entries = $this->alarmItemModel->search();


        if (! empty($entries)) {
            $a = new ContextMenuEntry();
            $a->name = 'AlarmValueControl';

            $elements['Control.AlarmValueControl'] = $a;
        }
        return $elements;
    }
}
