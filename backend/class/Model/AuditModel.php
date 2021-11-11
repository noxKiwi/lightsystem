<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Model;

use Exception;
use noxkiwi\core\Helper\WebHelper;
use noxkiwi\core\Request;
use noxkiwi\core\Session;
use noxkiwi\dataabstraction\Entry;
use noxkiwi\dataabstraction\Model;

/**
 * I am the storage for all changes made in NLFW.
 *
 * @package      noxkiwi\lightsystem\Model
 * @author       Jan Nox <jan@nox.kiwi>
 * @license      https://nox.kiwi/license
 * @copyright    2021 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class AuditModel extends Model
{
    public const TABLE = 'audit';

    /**
     * I will make sure that there's an audit entry for every change performed on the given $entry.
     *
     * @param \noxkiwi\dataabstraction\Entry $entry
     *
     * @return bool
     */
    public static function saveAudit(Entry $entry): bool
    {
        $model = $entry->getModel();
        if ($model instanceof self) {
            return true;
        }
        $time    = date('Y-m-d H:i:s');
        $request = Request::getInstance();
        $audit   = [
            'model'      => $model::class,
            'primary'    => $entry->getField($model->getPrimarykey()),
            'changes'    => $entry->getChangedFields(),
            'time'       => $time,
            'connection' => $model->getConnectionName(),
            'client'     => WebHelper::getClientIp(),
            'requestID'  => $request->getIdentifier(),
            'request'    => $request->get(),
            'user'       => Session::getInstance()->get('user_username')
        ];
        try {
            $auditModel = self::getInstance();
            $auditEntry = $auditModel->getEntry();
            $auditEntry->set([
                                 'audit_created'  => $time,
                                 'audit_modified' => date('Y-m-d H:i:s'),
                                 'audit_flags'    => 1,
                                 'audit_type'     => $model::class,
                                 'audit_data'     => json_encode($audit, JSON_PRETTY_PRINT)
                             ]);
            $auditEntry->save();
        } catch (Exception) {
            return false;
        }

        return true;
    }
}

