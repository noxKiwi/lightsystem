<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Render;

use noxkiwi\dataabstraction\Entry;
use noxkiwi\lightsystem\Render;

/**
 * I am the Render class for panel elements.
 * During the rendering process I will delegate data to other Render classes.
 *  - svgtag
 * @package      noxkiwi\lightsystem\Render
 * @see          \noxkiwi\lightsystem\Render\PanelelementRender
 * @see          \noxkiwi\lightsystem\Render\SvgtagRender
 *  - elements
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2018 - 2021 noxkiwi
 * @version      1.1.1
 * @link         https://nox.kiwi/
 */
final class PanelelementRender extends Render
{
    /**
     * TagRender constructor.
     *
     * @param \noxkiwi\dataabstraction\Entry $element
     * @param array                          $renderData
     */
    public function __construct(Entry $element, array $renderData)
    {
        parent::__construct($renderData);
        $this->setElement($element);
        $this->setElements((array)($element->render_element_data['objects'] ?? []));
    }

    /**
     * @param array $data
     *
     * @throws \noxkiwi\lightsystem\Exception\RenderException
     * @return string
     */
    public function render(array $data): string
    {
        $tag    = '';
        $config = $this->getConfig();
        if (! empty($config->get('tag', ''))) {
            $tag = 'tag="' . $config->get('tag', '') . '"';
        }
        $click = self::renderClick($config->get('data-clickinfo', []));

        return <<<SVG
<svg
    data-renderelement="{$this->getElement()->render_element_id}"
    {$tag}
    a {$click} b
    width="{$config->get('width', 0)}"
    height="{$config->get('height', 0)}"
    data-min="{$config->get('data-min', '')}"
    data-max="{$config->get('data-max', '')}"
    data-function="{$config->get('data-function', '')}"
    data-click="{$config->get('data-click', '')}"
    x="{$config->get('x', 0)}"
    y="{$config->get('y', 0)}"
    viewBox="0,0,{$config->get('width', 0)},{$config->get('height', 0)}"
>{$this->renderElements()}</svg>
SVG;
    }
}
