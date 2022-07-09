<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Model;


use noxkiwi\dataabstraction\Model;

/**
 * I manage the panels and their prefixes
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2021 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class RenderPanelPrefixModel extends Model
{
    public const TABLE        = 'render_panel_prefix';
    public const    FLAG_ENABLED = 1;
}
