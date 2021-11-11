<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Context;

use noxkiwi\lightsystem\Api\LightSystem\AlarmClient;

/**
 * I am
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan@nox.kiwi>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class AlarmContext extends ApiContext
{
    /**
     * @throws \noxkiwi\singleton\Exception\SingletonException
     */
    protected function __construct()
    {
        parent::__construct();
        $this->setClient(AlarmClient::getInstance());
    }
}
