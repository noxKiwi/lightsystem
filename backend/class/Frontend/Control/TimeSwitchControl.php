<?php declare(strict_types=1);

namespace noxkiwi\lightsystem\Frontend\Control;

use noxkiwi\dataabstraction\Entry;
use noxkiwi\lightsystem\Frontend\ContextMenuEntry;
use noxkiwi\lightsystem\Frontend\Control;
use noxkiwi\lightsystem\Model\TimeSwitchModel;

/**
 * I am the VideoStreamControl class.
 *
 * @package      noxkiwi\lightsystem\Frontend\Control
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2020 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class TimeSwitchControl extends Control
{
    public const IDENTIFIER = 'TimeSwitchControl';

    private TimeSwitchModel $timeSwitchModel;

    public function __construct(array $options = null)
    {
        parent::__construct($options);
        $this->timeSwitchModel = TimeSwitchModel::getInstance();
    }

    public function getContextMenu(Entry $opcItem): array
    {
        $elements = parent::getContextMenu($opcItem);

        $this->timeSwitchModel->addFilter('opc_item_write', $opcItem->opc_item_id);
        $entries = $this->timeSwitchModel->search();

        if (!empty($entries)) {
            $a = new ContextMenuEntry();
            $a->name = 'TimeSwitchControl';
            $a->params = ['timeswitch_id' => $entries[0]['timeswitch_id']];

            $elements['Control.TimeSwitchControl'] = $a;
        }
        return $elements;
    }
}
