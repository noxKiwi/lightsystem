<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Message;

use noxkiwi\queue\Message;

/**
 * I am a command Message object.
 *
 * I represent a command that shall be sent by the Consumer.
 *
 * @package      noxkiwi\lightsystem\Message
 * @author       Jan Nox <jan@nox.kiwi>
 * @license      https://nox.kiwi/license
 * @copyright    2020 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class CommandMessage extends Message
{
    /** @var string I represent the OPCUA Item's address in "."-Notation. */
    public string  $item;
    /** @var mixed I represent the value that shall be sent to the given OPCUA Item address. */
    public mixed $value;
}

