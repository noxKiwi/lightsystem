<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Api\LightSystem;

use noxkiwi\core\ErrorHandler;
use noxkiwi\lightsystem\Api\LightSystem\AlarmClient\AlarmClientInterface;
use noxkiwi\lightsystem\Api\LightSystem\AlarmClient\AlarmOccurance;
use noxkiwi\lightsystem\Exception\XmlRpcException;
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
            $response = $this->makeRequest('count');
            if (! is_int($response)) {
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
            if (! is_array($alarm)) {
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
            if (! is_bool($response)) {
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
            if (! is_bool($response)) {
                throw new XmlRpcException(static::EXCEPTION_INVALID_RESPONSE, E_ERROR, $response);
            }
        } catch (XmlRpcException $exception) {
            ErrorHandler::handleException($exception, E_USER_NOTICE);
        }
    }
}
