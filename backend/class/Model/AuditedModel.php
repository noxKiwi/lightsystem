<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Model;

use noxkiwi\dataabstraction\Entry;
use noxkiwi\dataabstraction\Model as BaseModel;
use noxkiwi\lightsystem\ConnectionChoser;

/**
 * I am the base model implementation for the lightsystem.
 *
 * This level of inheritance is important to provide audit logging for each
 * and every heir of this Model.
 *
 * @package      noxkiwi\lightsystem\Model
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2021 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
abstract class AuditedModel extends BaseModel
{
    /**
     * @inheritDoc
     */
    public function saveEntry(Entry $entry): void
    {
        if (! AuditModel::saveAudit($entry)) {
            return;
        }
        BaseModel::saveEntry($entry);
    }
}

