<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem;

use noxkiwi\core\Exception\InvalidArgumentException;
use noxkiwi\lightsystem\Updatemanager\UpdatemanagerInterface;
use noxkiwi\lightsystem\Value\Structure\UpdateValue;
use noxkiwi\log\Traits\LogTrait;
use noxkiwi\singleton\Singleton;
use function array_key_exists;
use function compact;
use function in_array;
use function session_id;
use function str_replace;

function updatelog(string $text): void
{
}

/**
 * I am the update manager. I am used to send only this update data to a client, that he would visualize if it was
 * changed. Basically: CLIENT:  "Hey, give me all the things that changed since my last request!" SERVER:  You are
 * visualizing [Tag1, Tag2, Tag3, Tag4, TagSausage].
 *  -  TagSausage was set to 1.
 *  -  nothing happened
 *  -  Tag2 is "The Sound of Silence"
 *  -  Tag3 is "No more sorrow"
 *  -  nothing happened
 *  -  nothing happened
 *  -  nothing happened
 *  -  nothing happened
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
abstract class Updatemanager extends Singleton implements UpdatemanagerInterface
{
    use LogTrait;

    protected const USE_DRIVER = true;

    /**
     * I will add all the given $updates.
     *
     * @param array $updates
     */
    public function addUpdates(array $updates): void
    {
        foreach ($updates as $tag => $value) {
            $this->addUpdate($tag, $value);
        }
    }

    /**
     * @inheritDoc
     */
    public function addUpdate(string $tag, mixed $value, string $display = null): void
    {
        updatelog($tag);
        if (empty($tag)) {
            updatelog('An empty tag was given.');
            $this->logError(__METHOD__ . ': No tag given');

            return;
        }
        if ($value === null) {
            updatelog('An empty value was given.');
            $this->logError(__METHOD__ . ': No value given');

            return;
        }
        try {
            $update = new UpdateValue(
                compact('tag', 'value')
            );
        } catch (InvalidArgumentException $exception) {
            updatelog('Could not create UpdateValue');
            $this->logError(__METHOD__ . ': Unable to create UpdateValue.');

            return;
        }
        $display  ??= (string)$value;
        $sessions = $this->getSessions();
        foreach ($sessions as $session) {
            $userTags = $this->getUserTags($session);
            if (! array_key_exists($tag, $userTags)) {
                updatelog("======== TAG $tag NICHT IN SESSION $session VORHANDEN.");
                $this->logDebug("Tag {$tag} not present in session {$session}");
                continue;
            }
            updatelog('I will update an address value.');
            updatelog("  -      Tag: $tag");
            updatelog("  -    Value: $value");
            updatelog("  -  Display: $display");
            updatelog("  -  Session: $session");
            $updates       = $this->getUserUpdates($session);
            $updates[$tag] = $update;
            $this->setUserUpdates($session, $updates);
            $userTags[$tag] = compact('value', 'display');
            $this->setUserTags($session, $userTags);
        }
    }

    /**
     * I will return a list of all sessions that are active.
     *
     * @return string[]
     */
    abstract protected function getSessions(): array;

    /**
     * I will return all bound tags of the given $sessid.
     *
     * @param string $sessid
     *
     * @return array
     */
    abstract protected function getUserTags(string $sessid): array;

    /**
     * I will return all updates that are queued for the given $sessid.
     *
     * @param string $sessionId
     *
     * @return \noxkiwi\lightsystem\Value\Structure\UpdateValue[]
     */
    abstract protected function getUserUpdates(string $sessionId): array;

    /**
     * I will write down all the given $updates for the given $sessid.
     *
     * @param array  $updates
     *
     * @param string $sessionId
     */
    abstract protected function setUserUpdates(string $sessionId, array $updates): void;

    /**
     * I will override the tags for the given $sessid with the given ones.
     *
     * @param array  $data
     * @param string $sessionId
     */
    abstract protected function setUserTags(string $sessionId, array $data): void;

    /**
     * I will bind each of the given $tags to the current user session.
     *
     * @param array $tags
     */
    final public function bindTags(array $tags): void
    {
        $this->clearTags();
        $allTags = [];
        foreach ($tags as $tag) {
            $allTags[$tag] = null;
        }
        $this->setUserTags(static::getUserSession(), $allTags);
    }

    /**
     * I will unbind all tags of the current user session.
     */
    public function clearTags(): void
    {
        $this->setUserTags(static::getUserSession(), []);
    }

    /**
     * @return string
     */
    public static function getUserSession(): string
    {
        return (string)str_replace('.', '', session_id());
    }

    /**
     * I will bind the given $tag to the current user session.
     *
     * @param string $tag
     */
    public function bindTag(string $tag): void
    {
        $allTags = $this->getUserTags(static::getUserSession());
        if (in_array($tag, $allTags, true)) {
            return;
        }
        $allTags[] = $tag;
        $this->setUserTags(static::getUserSession(), $allTags);
    }

    /**
     * I will return the tags of the current user session.
     *
     * @return array
     */
    public function getTags(): array
    {
        return $this->getUserTags(static::getUserSession());
    }

    /**
     * I will add each tag of the given list $newTags into the given $sessid's access list.
     *
     * @param string $sessid
     * @param array  $newTags
     */
    protected function addUserTags(string $sessid, array $newTags): void
    {
        $tags = $this->getUserTags($sessid);
        foreach ($newTags as $key => $value) {
            $tags[$value] = null;
        }
        $this->setUserTags($sessid, $tags);
    }
}
