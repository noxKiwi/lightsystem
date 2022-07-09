<?php declare(strict_types=1);

namespace noxkiwi\lightsystem\Api\LightSystem;

use noxkiwi\core\ErrorHandler;
use noxkiwi\lightsystem\Api\LightSystem\AlarmClient\AlarmClientInterface;
use noxkiwi\lightsystem\Api\LightSystem\AlarmClient\AlarmInformation;
use noxkiwi\lightsystem\Api\LightSystem\AlarmClient\AlarmOccurance;
use noxkiwi\lightsystem\Api\LightSystem\AlarmClient\HysteresisData;
use noxkiwi\lightsystem\Api\LightSystem\BaseClient\Comparison;
use noxkiwi\lightsystem\Api\LightSystem\BaseClient\OpcItem;
use noxkiwi\lightsystem\Api\LightSystem\BaseClient\OpcNode;
use noxkiwi\lightsystem\Exception\XmlRpcException;
use noxkiwi\lightsystem\Model\AlarmItemModel;
use noxkiwi\lightsystem\Model\OpcItemModel;
use noxkiwi\lightsystem\Model\OpcNodeModel;
use function is_array;
use function is_bool;
use function is_int;
use const E_ERROR;
use const E_USER_NOTICE;

/**
 * I am the DataClient.
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2019 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class AlarmClient extends AbstractClient implements AlarmClientInterface
{
    /**
     * @inheritDoc
     */
    public function count(): int
    {
        try {
            $response = $this->makeRequest('countAlarms');
            if (!is_int($response)) {
                throw new XmlRpcException(static::EXCEPTION_INVALID_RESPONSE, E_ERROR, $response);
            }

            return $response;
        } catch (XmlRpcException $exception) {
            ErrorHandler::handleException($exception, E_USER_NOTICE);

            return -1;
        }
    }

    /**
     * @inheritDoc
     */
    public function list(): array
    {
        $return = [];
        try {
            $alarms = $this->makeRequest('list');
        } catch (XmlRpcException $exception) {
            ErrorHandler::handleException($exception, E_USER_NOTICE);
        }
        if (empty($alarms)) {
            return $return;
        }
        foreach ($alarms as $alarm) {
            if (!is_array($alarm)) {
                continue;
            }
            $alarm = AlarmOccurance::fromApi($alarm);
            if ($alarm === null) {
                continue;
            }
            $return [] = $alarm->getTableRow();
        }

        return $return;
    }

    /**
     * @inheritDoc
     */
    public function acknowledge(string $alarm): void
    {
        try {
            $response = $this->makeRequest('acknowledge', $alarm);
            if (!is_bool($response)) {
                throw new XmlRpcException(static::EXCEPTION_INVALID_RESPONSE, E_ERROR, $response);
            }
        } catch (XmlRpcException $exception) {
            ErrorHandler::handleException($exception, E_USER_NOTICE);
        }
    }

    /**
     * @inheritDoc
     */
    public function acknowledgeAll(): void
    {
        try {
            $response = $this->makeRequest('acknowledgeAll');
            if (!is_bool($response)) {
                throw new XmlRpcException(static::EXCEPTION_INVALID_RESPONSE, E_ERROR, $response);
            }
        } catch (XmlRpcException $exception) {
            ErrorHandler::handleException($exception, E_USER_NOTICE);
        }
    }

    /**
     * @param int $opcNodeId
     * @return AlarmInformation
     */
    public function getInfo(int $opcNodeId): AlarmInformation
    {
        try {
            $opcNode = OpcNodeModel::expect($opcNodeId);

            $alarmItem = AlarmItemModel::fromNode($opcNode);


            $alarmInformation = new AlarmInformation();
            $alarmInformation->opcNode = OpcNode::fromEntry($opcNode);
            $alarmInformation->read = OpcItem::fromEntry(OpcItemModel::expect($alarmItem->opc_item_id_read));
            $alarmInformation->disengaged = OpcItem::fromEntry(OpcItemModel::expect($alarmItem->opc_item_id_disengaged));
            $alarmInformation->write = OpcItem::fromEntry(OpcItemModel::expect($alarmItem->opc_item_id_alarm));
            $alarmInformation->acknowledgement = OpcItem::fromEntry(OpcItemModel::expect($alarmItem->opc_item_id_ack));
            $alarmInformation->engaged = OpcItem::fromEntry(OpcItemModel::expect($alarmItem->opc_item_id_engaged));
            $h = new HysteresisData();
            $h->hysteresisTime = $alarmItem->alarm_item_hysteresis_seconds;
            $h->hysteresisValue = $alarmItem->alarm_item_hysteresis_value;
            $alarmInformation->hysteresis = $h;
            $c = new Comparison();
            $c->comparisonValue = $alarmItem->alarm_item_compare_value;
            $c->comparisonType = $alarmItem->alarm_item_comparison_type;
            $alarmInformation->comparison = $c;
            $alarmInformation->flagOn = $alarmItem->alarm_item_valence_on;
            $alarmInformation->flagOff = $alarmItem->alarm_item_valence_off;

            return $alarmInformation;

        } catch (\Exception $exception) {
            ErrorHandler::handleException($exception, E_USER_NOTICE);
        }
    }
}
