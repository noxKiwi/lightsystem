<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Consumer;

use noxkiwi\core\Exception\InvalidArgumentException;
use noxkiwi\lightsystem\Api\LightSystem\BaseClient;
use noxkiwi\lightsystem\Message\CommandMessage;
use noxkiwi\queue\Consumer\RabbitmqConsumer;
use noxkiwi\queue\Message;
use function print_r;
use const E_USER_NOTICE;
use function get_class;

/**
 * I am an example Message object.
 *
 * @package      noxkiwi\lightsystem\Consumer
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2020 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class CommandConsumer extends RabbitmqConsumer
{
    protected const MESSAGE_TYPES = [
        CommandMessage::class
    ];

    /**
     * @inheritDoc
     *
     * @param \noxkiwi\lightsystem\Message\CommandMessage $message
     *
     * @throws \noxkiwi\core\Exception\InvalidArgumentException
     * @throws \noxkiwi\singleton\Exception\SingletonException
     * @return bool
     */
    public function process(Message $message): bool
    {
        if (! $message instanceof CommandMessage) {
            $messageType = $message::class;
            throw new InvalidArgumentException("The given $messageType is not compatible with this consumer.", E_USER_NOTICE);
        }
        $sender = BaseClient::getInstance();
        $this->logDebug(' [*] Sending value ' . print_r($message->value, true) . ' to address ' . $message->item);
        $ret = $sender->write($message->item, $message->value);
        $this->logNotice(' [*] Finished sending with result' . print_r($ret, true));

        return true;
    }
}

