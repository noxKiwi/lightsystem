<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Model;

use noxkiwi\dataabstraction\Model;

/**
 * I am the storage for different failure classes
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan@nox.kiwi>
 * @license      https://nox.kiwi/license
 * @copyright    2019 - 2021 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class OpcTemplateModel extends Model
{
    public const TABLE          = 'opc_template';
    public const    FLAG_ENABLED   = 1;
    public const    FLAG_TYPE_NODE = 2;
}
