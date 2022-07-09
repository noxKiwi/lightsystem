<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Frontend\Control;

use noxkiwi\dataabstraction\Entry;
use noxkiwi\lightsystem\Frontend\ContextMenuEntry;
use noxkiwi\lightsystem\Frontend\Control;

/**
 * I am the DataControl class.
 *
 * @package      noxkiwi\lightsystem\Frontend\Control
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2022 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class AutoManualControl extends Control
{
    public const IDENTIFIER = 'AutoManualControl';

    public function getContextMenu(Entry $opcItem): array
    {
        $elements = parent::getContextMenu($opcItem);
        if (str_contains($opcItem->opc_item_address, 'AUTO')) {
            $a = new ContextMenuEntry();
            $a->name = 'AutomaticControl';

            $elements['AutomaticControl'] = $a;
        }
        return $elements;
    }
}
