<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Api\Telegram;

use noxkiwi\core\Traits\ErrorstackTrait;

/**
 * I am the base sender for all interactions with Telegram bot api.
 *
 * @package      noxkiwi\lightsystem\Api\Telegram
 * @author       Jan Nox <jan@nox.kiwi>
 * @license      https://nox.kiwi/license
 * @copyright    2020 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class Telegram
{
    use ErrorstackTrait;

    public function send(Request $message): void
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot{$message->token}/sendMessage");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $message->getData());
        curl_exec($ch);
    }
}
