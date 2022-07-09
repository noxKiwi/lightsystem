<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Api\LightSystem;

use Exception;
use noxkiwi\core\ErrorHandler;
use noxkiwi\core\Helper\WebHelper;
use noxkiwi\lightsystem\Api\LightSystem\AbstractClient\AbstractClientInterface;
use noxkiwi\lightsystem\Exception\XmlRpcException;
use noxkiwi\singleton\Singleton;
use noxkiwi\translator\Traits\TranslatorTrait;
use function curl_close;
use function curl_errno;
use function curl_exec;
use function curl_init;
use function curl_setopt;
use function is_int;
use function strlen;
use function xmlrpc_decode;
use function xmlrpc_encode_request;
use const CURLOPT_HTTPHEADER;
use const CURLOPT_POSTFIELDS;
use const CURLOPT_RETURNTRANSFER;
use const CURLOPT_TIMEOUT;
use const CURLOPT_URL;
use const E_ERROR;
use const E_USER_NOTICE;

/**
 * I am the abstract Client for all RPCs that will be created for lightsystem project.
 *
 * @package      noxkiwi\lightsystem\Api\lightsystem
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2019 - 2021 noxkiwi
 * @version      1.0.1
 * @link         https://nox.kiwi/
 */
abstract class AbstractClient extends Singleton implements AbstractClientInterface
{
    use TranslatorTrait;
    protected const USE_DRIVER                 = true;
    protected const EXCEPTION_INVALID_RESPONSE = 'invalidResponse';
    /** @var string I am the transport protocol that will be used for the XMLRpc Requests. */
    private string $protocol;
    /** @var string I am the host name to the server that provides the XMLRpc service. */
    private string $host;
    /** @var int I am the network port on the host where the XMLRpc service is listening to. */
    private int $port;
    /** @var string I am the main enpoint where the XMLRpc lies. */
    private string $endpoint;

    /**
     * AbstractClient constructor.
     *
     * @param array|null $options
     */
    final protected function __construct(array $options = null)
    {
        parent::__construct();
        $this->host     = $options['host'] ?? 'vulpes.nox.kiwi';
        $this->port     = $options['port'] ?? 8001;
        $this->endpoint = $options['endpoint'] ?? 'base';
        $this->protocol = $options['protocol'] ?? WebHelper::PROTOCOL_HTTP;
    }

    /**
     * @inheritDoc
     * @return int
     */
    final public function getCounter(): int
    {
        try {
            $response = $this->makeRequest('getCounter');
            if (! is_int($response)) {
                throw new XmlRpcException(static::EXCEPTION_INVALID_RESPONSE, E_ERROR, $response);
            }

            return $response;
        } catch (Exception $exception) {
            ErrorHandler::handleException($exception, E_USER_NOTICE);

            return -1;
        }
    }

    /**
     * I will send the given $params to the given $method on the configured remote server.
     *
     * @param string $method
     * @param null   $params
     *
     * @return mixed
     */
    final protected function makeRequest(string $method, $params = null): mixed
    {
        try {
            $request = xmlrpc_encode_request($method, $params);

            return xmlrpc_decode($this->doRequest($request));
        } catch (Exception $exception) {
           # ErrorHandler::handleException($exception, E_USER_NOTICE);

            return null;
        }
    }

    /**
     * @param string $request
     *
     * @throws \noxkiwi\lightsystem\Exception\XmlRpcException
     * @return string
     */
    final protected function doRequest(string $request): string
    {
        $url        = "$this->protocol://$this->host:$this->port/$this->endpoint";
        $header     = [
            'Content-type: text/xml',
            'Content-length: ' . strlen($request)
        ];
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $url);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 1);
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $request);
        $curlResponse = curl_exec($curlHandle);
        if (curl_errno($curlHandle)) {
            throw new XmlRpcException('REQUEST_FAILED_CURL_ERROR', E_USER_NOTICE, [
                'curl_errno' => curl_errno($curlHandle),
                'url'        => $url
            ]);
        }
        if (empty($curlResponse)) {
            throw new XmlRpcException('REQUEST_FAILED_EMPTY_RESPONSE', E_USER_NOTICE, [
                'curl_errno' => curl_errno($curlHandle),
                'url'        => $url
            ]);
        }
        curl_close($curlHandle);

        return (string)$curlResponse;
    }

    /**
     * @inheritDoc
     */
    public function isConnected(?string $serverName = null): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function setServer(string $serverName): void
    {
    }

    /**
     * @inheritDoc
     */
    public function getServers(): array
    {
        return ['CERVUS', 'VULPES'];
    }

    /**
     * @inheritDoc
     */
    public function getServer(): string
    {
        return $this->host;
    }
}

