<?php declare(strict_types=1);

namespace noxkiwi\lightsystem\Queue;

use noxkiwi\queue\Queue\RabbitmqQueue;

/**
 * I am the queue for sending commands through OPC-UA.
 *
 * @package      noxkiwi\lightsystem\queue
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2020 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class CommandQueue extends RabbitmqQueue
{
}
