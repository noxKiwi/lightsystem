<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Api\Telegram\Request;

use noxkiwi\core\Session;
use noxkiwi\core\Traits\LanguageImprovementTrait;
use noxkiwi\lightsystem\Api\Telegram\Request;
use noxkiwi\core\Helper\WebHelper;
use function date;

/**
 * I am the AbstractClient for all RPCs that will be created for lightsystem project.
 *
 * @package      noxkiwi\lightsystem\Api\Telegram\Request
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2020 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class SendMessageRequest extends Request
{
    use LanguageImprovementTrait;
    public int    $chatId;
    public string $text;

    public function getData(): array
    {
        return [
            'chat_id'    => $this->chatId,
            'text'       => $this->getSenderIdentifiaction() . $this->text,
            'parse_mode' => 'html'
        ];
    }

    private function getSenderIdentifiaction() : string
    {
        $session = Session::getInstance();
        $dateTime = date('Y-m-d H:i:s:u');
        $userName = $session->get('user_username');
        return <<<HTML
This is an automatic message from your home server.<pre>
      User: {$userName}
    Client: {$this->returnIt(WebHelper::getClientIp())}
  DateTime: {$dateTime}
    Server: CORVUS
</pre>
HTML;
    }
}
