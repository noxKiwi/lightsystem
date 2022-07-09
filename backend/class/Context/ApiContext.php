<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Context;

use Exception;
use noxkiwi\lightsystem\Api\lightsystem\AbstractClient;
use noxkiwi\lightsystem\Api\LightSystem\AbstractClient\AbstractClientInterface;
use function method_exists;

/**
 * I am the Base API Context.
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2019 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
abstract class ApiContext extends CallbackContext
{
    /**
     * I am the XMLRpc Client that will used with this API context.
     * @var \noxkiwi\lightsystem\Api\LightSystem\AbstractClient\AbstractClientInterface $client
     */
    protected AbstractClientInterface $client;

    /**
     * I will use the given $client for the instance to work with.
     *
     * @param \noxkiwi\lightsystem\Api\lightsystem\AbstractClient $client
     */
    final protected function setClient(AbstractClient $client): void
    {
        $this->client = $client;
    }

    /**
     * I will process the request.
     */
    final protected function viewRun(): void
    {
        $methodName = $this->request->get(static::REQUEST_KEY_METHOD, 'count');
        if (! method_exists($this->client, $methodName)) {
            return;
        }
        $result = null;
        try {
            $result = $this->client->{$methodName}($this->request->get(static::REQUEST_KEY_DATA, ''));
        } catch (Exception $exception) {
            $this->response->set(static::RESPONSE_KEY_ERRORS, $exception->getMessage());
            $this->feedbackError($exception->getMessage());
        }
        $this->response->set(static::RESPONSE_KEY_DATA, $result);
    }
}

