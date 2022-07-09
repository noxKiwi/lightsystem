<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Frontend;

/**
 * I represent a single entry for the AJAX ContextMenu.
 *
 * @package      noxkiwi\lightsystem\Frontend
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2022 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
 class ContextMenuEntry
{
    public string $name;
    public array $params;
}
