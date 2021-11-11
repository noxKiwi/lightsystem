<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Updatemanager;

use Exception;
use noxkiwi\cache\Cache;
use noxkiwi\core\ErrorHandler;
use noxkiwi\lightsystem\Updatemanager;
use function in_array;
use function is_array;

/**
 * I am the update manager. I am used to send only this update data to a client, that he would visualize if it was
 * changed. Basically: CLIENT:  "Hey, give me all the things that changed since my last request!" SERVER:  You are
 * visualizing [Tag1, Tag2, Tag3, Tag4, TagSausage].
 *  -  TagSausage was set to 1.
 *  -
 *  -  Tag2 is "Hello darknes my old friend"
 *  -  Tag3 is "No more sorrow"
 *  -
 *  -
 *  -
 *  -
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan@nox.kiwi>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class CacheUpdatemanager extends Updatemanager
{
    private const CACHE_GROUP_SESS = Cache::DEFAULT_PREFIX . '_SESS';
    private const CACHE_GROUP_UPDA = Cache::DEFAULT_PREFIX . '_UPDA';
    private const CACHE_GROUP_TAGS = Cache::DEFAULT_PREFIX . '_TAGS';
    /** @var int I am the default cache timeout for updates. */
    private static int $timeout = 86400;
    /** @var \noxkiwi\cache\Cache */
    private Cache $cache;

    public function __construct()
    {
        parent::__construct();
        $this->cache = Cache::getInstance();
    }

    /**
     * @inheritDoc
     */
    public function getUserTags(string $sessid): array
    {
        try {
            $data = $this->cache->get(self::CACHE_GROUP_TAGS, $sessid);
        } catch (Exception $exception) {
            ErrorHandler::handleException($exception);

            return [];
        }
        if (! is_array($data)) {
            return [];
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function getUpdates(): array
    {
        $data = [];
        try {
            $data = $this->getUserUpdates(self::getUserSession());
        } catch (Exception $exception) {
            ErrorHandler::handleException($exception);
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    protected function getUserUpdates(string $sessionId): array
    {
        try {
            $data = $this->cache->get(self::CACHE_GROUP_UPDA, $sessionId);
            if (! is_array($data)) {
                return [];
            }

            return $data;
        } catch (Exception $exception) {
            ErrorHandler::handleException($exception);
        }

        return [];
    }

    /**
     * @inheritDoc
     */
    protected function setUserTags(string $sessionId, array $data): void
    {
        $sessions = $this->getSessions();
        if (! in_array($sessionId, $sessions, true)) {
            $sessions[] = $sessionId;
        }
        $this->setSessions($sessions);
        try {
            $this->cache->set(self::CACHE_GROUP_TAGS, $sessionId, $data, self::$timeout);
        } catch (Exception $exception) {
            ErrorHandler::handleException($exception);
        }
    }

    /**
     * @inheritDoc
     */
    protected function getSessions(): array
    {
        try {
            $data = $this->cache->get(self::CACHE_GROUP_SESS, 'SESSIONS');
            if (! is_array($data)) {
                $this->setSessions([]);

                return [];
            }

            return $data;
        } catch (Exception $exception) {
            ErrorHandler::handleException($exception);

            return [];
        }
    }

    /**
     * I will update the session storage.
     *
     * @param $data
     */
    private function setSessions($data): void
    {
        try {
            $this->cache->set(self::CACHE_GROUP_SESS, 'SESSIONS', $data, self::$timeout);
        } catch (Exception $exception) {
            ErrorHandler::handleException($exception);
        }
    }

    /**
     * @inheritDoc
     */
    protected function setUserUpdates(string $sessionId, array $updates): void
    {
        try {
            $this->cache->set(self::CACHE_GROUP_UPDA, $sessionId, $updates, self::$timeout);
        } catch (Exception $exception) {
            $this->logError(__METHOD__ . ': Unable to set userUpdates');
            ErrorHandler::handleException($exception);
        }
    }
}
