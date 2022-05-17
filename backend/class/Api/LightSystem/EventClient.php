<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Api\LightSystem;

use noxkiwi\database\Database;
use noxkiwi\lightsystem\Api\LightSystem\EventClient\EventClientInterface;
use noxkiwi\lightsystem\Model\ArchiveGroupModel;
use noxkiwi\lightsystem\Model\ArchiveItemModel;

/**
 * I am the EventClient.
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2019 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class EventClient extends AbstractClient implements EventClientInterface
{
    /**
     * I will return the names of the groups.
     * @return string[]
     */
    public function getGroups(): array
    {
        $archiveGroupModel = ArchiveGroupModel::getInstance();
        $archiveGroups     = $archiveGroupModel->search();
        $foundGroups       = [];
        foreach ($archiveGroups as $archiveGroup) {
            $flags = (int)$archiveGroup['archive_group_flags'];
            if (! ($flags & 1)) {
                // Group entirely disabled.
                continue;
            }
            if (! ($flags & 2)) {
                // Group not readable.
                continue;
            }
            if ($archiveGroup['archive_group_type'] !== 'E') {
                // No interval group
                continue;
            }
            $foundGroups[$archiveGroup['archive_group_id']] = $archiveGroup['archive_group_name'];
        }

        return $foundGroups;
    }

    /**
     * I will query for events.
     * @return array
     */
    public function getEvents(): array
    {
        $archiveItemModel = ArchiveItemModel::getInstance();
        $archiveItemModel->addFilter('archive_group_id', 3);
        $archiveItemModel->search();

        $sql = <<<SQL
SELECT
    *
FROM
    arc_relay_status
ORDER BY
    arc_relay_status_created DESC
SQL;

        $db = Database::getInstance();
        $db->read($sql);

        $v =  [];
        $eventRows =$db->getResult();


        foreach($eventRows as $eventRow) {
            $v[] = $this->readify($eventRow);
        }

        return $v;
    }

    private function readify(array $eventRow) : array{
        $address = '';
        foreach($eventRow as $fieldName => $fieldValue) {
            if($fieldName === 'arc_relay_status_created') {
                continue;
            }
            if($fieldValue !== null) {
                $address = $fieldName;
                $value = $fieldValue;
            }
        }
        $r = [
            'timestamp' => $eventRow['arc_relay_status_created'],
            'address'   => $address,
            'value'     => $value
        ];
        $r['text']=$this->translate('event_text', $r);
        return $r;
    }
}
