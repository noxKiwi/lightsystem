<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Model;

/**
 * I am the storage for different failure classes
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan@nox.kiwi>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class AlarmItemModel extends AuditedModel
{
    public const TABLE           = 'alarm_item';
    public const    FLAG_ENABLED = 1;
}
