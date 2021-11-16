<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Render;

/**
 * I am the interface for all Render classes.
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
interface RenderInterface
{
    /**
     * I will render the current element to the output string and return it.
     *
     * @param array|null $data
     *
     * @return string
     */
    public function render(array $data): string;
}
