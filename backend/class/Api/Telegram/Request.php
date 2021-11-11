<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Api\Telegram;

/**
 * I am the AbstractClient for all RPCs that will be created for lightsystem project.
 *
 * @package      noxkiwi\lightsystem\Api\lightsystem
 * @author       Jan Nox <jan@nox.kiwi>
 * @license      https://nox.kiwi/license
 * @copyright    2019 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
abstract class Request
{
    public string $token;

    abstract public function getData() : array;
}
