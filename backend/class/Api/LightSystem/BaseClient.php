<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Api\LightSystem;

use noxkiwi\core\ErrorHandler;
use noxkiwi\lightsystem\Api\LightSystem\BaseClient\BaseClientInterface;
use noxkiwi\lightsystem\Exception\XmlRpcException;
use function compact;
use const E_USER_NOTICE;

/**
 * I am the AlarmClient.
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2019 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class BaseClient extends AbstractClient implements BaseClientInterface
{
    /**
     * @inheritDoc
     */
    public function read(string $address)
    {
        try {
            return $this->makeRequest('read', compact('address'));
        } catch (XmlRpcException $exception) {
            ErrorHandler::handleException($exception, E_USER_NOTICE);
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function write(string $address, $value)
    {
        try {
            return $this->makeRequest('write', compact('address', 'value'));
        } catch (XmlRpcException $exception) {
            ErrorHandler::handleException($exception, E_USER_NOTICE);
        }

        return false;
    }
}

