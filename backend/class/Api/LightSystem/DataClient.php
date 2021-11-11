<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Api\LightSystem;

use Exception;
use noxkiwi\core\ErrorHandler;
use noxkiwi\database\Database;
use noxkiwi\lightsystem\Api\LightSystem\DataClient\DataClientInterface;
use noxkiwi\lightsystem\Api\LightSystem\DataClient\GetDataRequest;
use noxkiwi\lightsystem\Model\ArchiveItemModel;
use noxkiwi\lightsystem\Model\OpcItemModel;
use noxkiwi\lightsystem\Model\ArchiveGroupModel;
use \DateInterval;
use \DateTime;
use const E_USER_NOTICE;

/**
 * I am the DataClient.
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan@nox.kiwi>
 * @license      https://nox.kiwi/license
 * @copyright    2019 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class DataClient extends AbstractClient implements DataClientInterface
{
    private const PARAM_GROUP_ID = 'groupId';
    /*
     * This represents the data compression that is shown to the client.
     */
    private const COMPRESSION_MIN = 'MIN';
    private const COMPRESSION_MAX = 'MAX';
    private const COMPRESSION_AVG = 'AVG';
    private const COMPRESSIONS    = [
        self::COMPRESSION_MIN,
        self::COMPRESSION_MAX,
        self::COMPRESSION_AVG
    ];
    /*
     * This is the list of available display ranges (Show a table that contains a %DISPLAY% range).
     */
    private const DISPLAY_YEAR  = 'YEAR';
    private const DISPLAY_MONTH = 'MONTH';
    #    private const DISPLAY_WEEK   = 'WEEK';
    private const DISPLAY_DAY    = 'DAY';
    private const DISPLAY_HOUR   = 'HOUR';
    private const DISPLAY_MINUTE = 'MINUTE';
    private const DISPLAYS       = [
        self::DISPLAY_YEAR,
        self::DISPLAY_MONTH,
        #        self::DISPLAY_WEEK,
        self::DISPLAY_DAY,
        self::DISPLAY_HOUR,
        self::DISPLAY_MINUTE
    ];
    /*
     * I am the list of available intervals.
     * The selected %DISPLAY% will be shown in a set of smaller %INTERVAL%s.
     */
    private const INTERVAL_YEAR    = 'YEAR';
    private const INTERVAL_QUARTER = 'QUARTER';
    private const INTERVAL_MONTH   = 'MONTH';
    #    private const INTERVAL_WEEK    = 'WEEK';
    private const INTERVAL_DAY    = 'DAY';
    private const INTERVAL_HOUR   = 'HOUR';
    private const INTERVAL_MINUTE = 'MINUTE';
    private const INTERVAL_SECOND = 'SECOND';
    private const INTERVALS       = [
        self::INTERVAL_YEAR   => '%Y',
        self::INTERVAL_MONTH  => '%Y-%m',
        #        self::INTERVAL_WEEK   => '%Y-%V',
        self::INTERVAL_DAY    => '%Y-%m-%d',
        self::INTERVAL_HOUR   => '%Y-%m-%d %H:00',
        self::INTERVAL_MINUTE => '%Y-%m-%d %H:%i:00',
        self::INTERVAL_SECOND => '%Y-%m-%d %H:%i:%S'
    ];
    private const INTERVALS2      = [
        self::INTERVAL_YEAR   => 'Y',
        self::INTERVAL_MONTH  => 'Y-m',
        #        self::INTERVAL_WEEK   => 'Y-V',
        self::INTERVAL_DAY    => 'Y-m-d',
        self::INTERVAL_HOUR   => 'Y-m-d H:00',
        self::INTERVAL_MINUTE => 'Y-m-d H:i:00',
        self::INTERVAL_SECOND => 'Y-m-d H:i:S'
    ];
    private const INTERVALS3      = [
        self::INTERVAL_YEAR   => 'Y-01-01 00:00:00',
        self::INTERVAL_MONTH  => 'Y-m-01 00:00:00',
        self::INTERVAL_DAY    => 'Y-m-d 00:00:00',
        self::INTERVAL_HOUR   => 'Y-m-d H:00:00',
        self::INTERVAL_MINUTE => 'Y-m-d H:i:00',
        self::INTERVAL_SECOND => 'Y-m-d H:i:S'
    ];

    /**
     * @return array
     */
    public function getCompressions(): array
    {
        return static::COMPRESSIONS;
    }

    /**
     * @return array
     */
    public function getDisplays(): array
    {
        return static::DISPLAYS;
    }

    /**
     * @return array
     */
    public function getIntervals(): array
    {
        return static::INTERVALS;
    }

    /**
     * @inheritDoc
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
            if ($archiveGroup['archive_group_type'] !== 'I') {
                // No interval group
                continue;
            }
            $foundGroups[$archiveGroup['archive_group_id']] = $archiveGroup['archive_group_name'];
        }

        return $foundGroups;
    }

    /**
     * @inheritDoc
     */
    public function getData(array $params): array
    {
        $opcItem = $params['opcItem'] ?? '';                      // ADdress
        $object  = $this->getObject($params);
        $rows    = $this->query($object);
        $data    = [];
        foreach ($rows as $row) {
            $value = null;
            if ($row[$opcItem] !== null) {
                $value = (float)$row[$opcItem];
            }
            $data [] = [
                strtotime($row['date']) * 1000,
                $value
            ];
        }
        $itemModel = OpcItemModel::getInstance();
        $itemModel->addFilter('opc_item_address', $opcItem);
        $item = $itemModel->search();

        return ['name' => $item[0]['opc_item_readable'] ?? $opcItem, 'data' => $data];
    }

    private function getInterval(string $display): DateInterval
    {
        switch ($display) {
            case self::DISPLAY_YEAR:
                $string = 'P1Y';
                break;
            case self::DISPLAY_MONTH:
                $string = 'P1M';
                break;
            case self::DISPLAY_DAY:
                $string = 'P1D';
                break;
            case self::DISPLAY_HOUR:
                $string = 'PT1H';
                break;
            case self::DISPLAY_MINUTE:
                $string = 'PT1M';
                break;
            default:
                $string = 'P1m';
                break;
        }

        return new DateInterval($string);
    }

    /**
     * I will use the given $begin string and create a new DateTime Object.
     * According to the given $display, I will remove unnecessary info from the DateTime object.
     *
     * @param string $begin
     * @param string $display
     *
     * @return \DateTime
     */
    private function getBegin(string $begin, string $display): DateTime
    {
        $dateTime = new \DateTime($begin);
        if (empty($display)) {
            return $dateTime;
        }

        return new DateTime($dateTime->format(self::INTERVALS3[$display]));
    }

    private function getEnd(DateTime $begin, string $display): string
    {
        $date     = clone $begin;
        $interval = $this->getInterval($display);
        $date->add($interval);

        return $date->format('y-m-d H:i:s');
    }

    /**
     *
     * I will transform the given $request into SQL and run this query.
     *
     * @params noxkiwi\lightsystem\Api\LightSystem\DataClient\GetDataRequest $request
     * @param \noxkiwi\lightsystem\Api\LightSystem\DataClient\GetDataRequest $request
     *
     * @throws \noxkiwi\database\Exception\DatabaseException
     * @throws \noxkiwi\singleton\Exception\SingletonException
     * @return array
     */
    private function query(GetDataRequest $request): array
    {
        $group          = '_room_temperature';
        $intervalFormat = static::INTERVALS[$request->interval];
        $fields         = '';
        if(empty($request->opcItems)) {
            $request->opcItems = [$request->opcItem];
        }
        foreach ($request->opcItems as $opcItem) {
            $fields .= <<<SQL
, ROUND({$request->compression}(`$group`.`$opcItem`), 2)                          AS `$opcItem`
SQL;
        }
        $sql = <<<SQL
SELECT
    DATE_FORMAT( `$group`.`{$group}_created`, '{$request->sqlFormat}') AS `date` $fields
FROM
    `$group`
WHERE
    `$group`.`{$group}_created` BETWEEN '{$request->begin->format($request->phpFormat)}' AND '{$request->end->format($request->phpFormat)}'
GROUP BY
    DATE_FORMAT( `$group`.`{$group}_created`, '{$request->sqlFormat}')
SQL;
        $db  = Database::getInstance();
        $db->read($sql);

        return $db->getResult();
    }

    /**
     * @inheritDoc
     */
    public function getNodes(array $params): array
    {
        try {
            $archiveItemModel = ArchiveItemModel::getInstance();
            $archiveItemModel->addFilter('archive_group_id', $params['groupId'] ?? 0);
            $items     = $archiveItemModel->search();
            $itemModel = OpcItemModel::getInstance();
            $nodes     = [];
            foreach ($items as $archiveItem) {
                $opcItem = $itemModel->loadEntry($archiveItem['opc_item_id']);
                # Add OPC Item to response
                $nodes[$opcItem->getField('opc_item_readable')] = $opcItem->getField('opc_item_address');
            }

            return $nodes;
        } catch (Exception $exception) {
            ErrorHandler::handleException($exception, E_USER_NOTICE);

            return [];
        }
    }

    private function getObject(array $params): GetDataRequest
    {
        $object              = new GetDataRequest();
        $object->opcItem     = $params['opcItem'] ?? '';
        $object->groupId     = $params['groupId'] ?? 0;
        $object->display     = $params['display'] ?? '';
        $object->interval    = $params['interval'];
        $object->compression = $params['compression'];
        $object->begin       = $this->getBegin($params['begin'], $object->display);
        $object->end         = new \DateTime($params['end'] ?? $this->getEnd($object->begin, $object->display));
        $object->sqlFormat   = static::INTERVALS[$object->interval];
        $object->phpFormat   = static::INTERVALS3[$object->interval];
        $object->opcItems    = $this->getNodes($params);

        return $object;
    }

    /**
     * I will build an HTML table with the given compression $params.
     * @return array
     */
    public function getTable(array $params): array
    {
        $object             = $this->getObject($params);
        $table['columns']   = ['date'];
        $table['titles']    = ['date'];
        $params['opcItems'] = [];
        foreach ($object->opcItems as $title => $node) {
            $params['opcItems'][] = $node;
            $table['columns'][]   = $node;
            $table['titles'][]    = $title;
        }
        $table['data'] = $this->query($object);

        return [
            'table' => <<<HTML
<table class="table table-sm table-striped w-100">
	{$this->getHeaders($table)}
	{$this->getBody($table)}
</table>
HTML
        ];
    }

    public function getBody(array $table): string
    {
        $html = '<tbody>';
        foreach ($table['data'] as $datum) {
            $html .= '<tr>';
            foreach ($table['columns'] as $columnNumber => $column) {
                $class      = 'nkPhysics';
                $attributes = '';
                if ($columnNumber > 0) {
                    $class .= ' nkCelsius';
                } else {
                    $attributes = 'A';
                }
                $html .= "<td $attributes class=\"$class\">{$datum[$column]}</td>";
            }
            $html .= '</tr>';
        }

        return $html . '</tbody>';
    }

    public function getHeaders(array $table): string
    {
        $html = '<thead><tr>';
        foreach ($table['titles'] as $columnName => $column) {
            $html .= "<th>$column</th>";
        }
        $html .= '</tr></thead>';

        return $html;
    }
}
