<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Context;

use noxkiwi\lightsystem\Api\lightsystem\EventClient;

/**
 * I am
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan@nox.kiwi>
 * @license      https://nox.kiwi/license
 * @copyright    2019 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class EventContext extends ApiContext
{
    /**
     * AlarmContext constructor.
     * @throws \noxkiwi\core\Exception\ConfigurationException
     */
    public function __construct()
    {
        parent::__construct();
        $this->setClient(EventClient::getInstance());
    }
}
