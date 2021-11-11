<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Updatemanager;

/**
 * I am the update manager. I am used to send only this update data to a client, that he would visualize if it was
 * changed. Basically: CLIENT:  "Hey, give me all the things that changed since my last request!" SERVER:  You are
 * visualizing [Tag1, Tag2, Tag3, Tag4, TagSausage].
 *  -  TagSausage was set to 1.
 *  -  nothing happened
 *  -  Tag2 is "Hello darknes my old friend"
 *  -  Tag3 is "No more sorrow"
 *  -  nothing happened
 *  -  nothing happened
 *  -  nothing happened
 *  -  nothing happened
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan@nox.kiwi>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
interface UpdatemanagerInterface
{
    /**
     * I will bind each of the given $tags to the current user session.
     *
     * @param string[] $tags
     */
    public function bindTags(array $tags): void;

    /**
     * I will unbind all tags of the current user session.
     */
    public function clearTags(): void;

    /**
     * I will bind the given $tag to the current user session.
     * @param string $tag
     */
    public function bindTag(string $tag): void;

    /**
     * I will return all updates that exist for the current user session.
     * @return \noxkiwi\lightsystem\Value\Structure\UpdateValue[]
     */
    public function getUpdates(): array;

    /**
     * I will add all the given $updates.
     *
     * @param $updates
     */
    public function addUpdates(array $updates): void;

    /**
     * I will add the given $value for the given $tag to the update lists.
     *
     *
     * @param string $tag
     *
     * @param        $value
     * @param string|null $display
     * @todo   use variables instead of tags!
     */
    public function addUpdate(string $tag, mixed $value, string $display = null): void;

    /**
     * I will return the tags of the current user session.
     * @return array
     */
    public function getTags(): array;
}
