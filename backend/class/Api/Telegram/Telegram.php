<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Api\Telegram;

use function curl_exec;
use function curl_init;
use function curl_setopt;
use const CURLOPT_POSTFIELDS;
use const CURLOPT_RETURNTRANSFER;
use const CURLOPT_URL;

/**
 * I am the base sender for all interactions with Telegram bot api.
 *
 * @package      noxkiwi\lightsystem\Api\Telegram
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2020 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class Telegram
{

    public function send(Request $message): void
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot{$message->token}/sendMessage");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $message->getData());
        curl_exec($ch);
    }
}
