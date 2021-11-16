<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Model;


use noxkiwi\dataabstraction\Model;

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
final class FlowModel    extends Model
{
    public const TABLE        = 'flow';
    public const    FLAG_ENABLED = 1;
}
