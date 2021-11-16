<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Context;

use noxkiwi\core\Auth;
use noxkiwi\core\Constants\Mvc;
use noxkiwi\core\Context;
use noxkiwi\core\Environment;
use noxkiwi\core\Helper\LinkHelper;
use noxkiwi\core\Helper\WebHelper;
use noxkiwi\core\Session;
use noxkiwi\lightsystem\Api\Telegram\Request\SendMessageRequest;
use noxkiwi\lightsystem\Api\Telegram\Telegram;
use noxkiwi\log\LogLevel;
use function defined;
use function explode;
use const E_ERROR;

/**
 * I am
 *
 * @package      noxkiwi\lightsystem
 * @uses         \noxkiwi\lightsystem\Context\LoginContext::viewLogin()
 * @uses         \noxkiwi\lightsystem\Context\LoginContext::viewLogout()
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2018 - 2021 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class LoginContext extends Context
{
    private const   NAME        = 'login';
    private const   VIEW_LOGIN  = 'login';
    private const   VIEW_LOGOUT = 'logout';
    private const   VIEWS       = [
        self::VIEW_LOGIN,
        self::VIEW_LOGOUT
    ];
    protected const LOG_LEVELS  = LogLevel::ALL;

    /**
     * @inheritDoc
     */
    public function isAllowed(): bool
    {
        parent::isAllowed();

        return true;
    }

    public function viewLogout(): void
    {
        $this->session->destroy();
        LinkHelper::forward([Mvc::CONTEXT => self::NAME, Mvc::VIEW => self::VIEW_LOGIN]);
    }

    public function viewLogin(): void
    {
        $ipAddress  = WebHelper::getClientIp();
        $skippedIps = Environment::getInstance()->get('lsSkipLogin>ipv4', []);
        if (in_array($ipAddress, $skippedIps, true)) {
            $botId = end(explode('.', $ipAddress));
            Session::getInstance()->start(['user_username' => "TSBot {$botId}"]);
            LinkHelper::forward([Mvc::CONTEXT => 'dashboard', Mvc::VIEW=>'show']);
        }
        $this->request->set('template', 'login');
        if (! $this->request->isPost()) {
            $versionDate = 'unknown';
            if (defined('LS_LAST_DEPLOYMENT')) {
                $content     = file_get_contents(LS_LAST_DEPLOYMENT);
                $versionDate = (new \DateTime("@{$content}"))->format('d.m.Y H:i:s');
            }
            $this->response->add(
                [
                    'MAIN.VERSION_STRING' => $this->translate(
                        'MAIN.VERSION_STRING',
                        [
                            'versionNumber' => Environment::getInstance()->get('versionNumber'),
                            'versionDate'   => $versionDate
                        ]
                    )
                ]
            );

            return;
        }
        if (! $this->request->exists('username')) {
            LinkHelper::forward([Mvc::CONTEXT => self::NAME, Mvc::VIEW => self::VIEW_LOGOUT]);
        }
        if (! $this->request->exists('password')) {
            LinkHelper::forward([Mvc::CONTEXT => self::NAME, Mvc::VIEW => self::VIEW_LOGOUT]);
        }
        $user = Auth::getInstance()->authenticate($this->request->get('username'), $this->request->get('password'));
        if (empty($user)) {
            LinkHelper::forward([Mvc::CONTEXT => self::NAME, Mvc::VIEW => self::VIEW_LOGOUT]);
        }
        $addressSegments = explode('.', $ipAddress);
        $botId           = end($addressSegments);
        $userName        = "TSBot {$botId}";
        Session::getInstance()->start(['user_username' => $userName]);
        LinkHelper::forward([Mvc::CONTEXT => 'dashboard', Mvc::VIEW=>'show']);
    }
}

