<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Frontend\Control;

use noxkiwi\dataabstraction\Entry;

/**
 * I am the VideoStreamControl class.
 *
 * @package      noxkiwi\lightsystem\Frontend\Control
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2019 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
interface ControlInterface
{
    public const RESPONSE_WIDTH  = 'render_panel_width';
    public const RESPONSE_HEIGHT = 'render_panel_height';
    public const RESPONSE_DATA   = 'svg';
    public const RESPONSE_TITLE  = 'render_panel_name';
    public const OPTION_WIDTH    = 'width';
    public const OPTION_HEIGHT   = 'height';

    /**
     * @return array
     */
    public function run(): array;

    /**
     * @param Entry $opcItem
     * @return \noxkiwi\lightsystem\Frontend\ContextMenuEntry[]
     */
    public function getContextMenu(Entry $opcItem):array;
}
