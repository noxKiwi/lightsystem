<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Render;

use noxkiwi\cache\Cache;
use noxkiwi\dataabstraction\Entry;
use noxkiwi\lightsystem\Render;
use function is_string;

/**
 * I am the renderer for a panel.
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class PanelRender extends Render
{
    /**
     * I will construct the Panel renderer.
     *
     * @param \noxkiwi\dataabstraction\Entry $entry
     * @param array                          $renderData
     */
    public function __construct(Entry $entry, array $renderData = [])
    {
        parent::__construct($renderData);
        $this->setElement($entry);
        $this->setElements($entry->render_panel_data['objects']);
    }

    /**
     * I will render the current element to the output string and return it.
     *
     * @param array $data
     *
     * @throws \noxkiwi\lightsystem\Exception\RenderException
     * @throws \noxkiwi\singleton\Exception\SingletonException
     * @return string
     */
    public function render(array $data): string
    {
        $element    = $this->getElement();
        $config     = $this->getConfig();
        $cache      = Cache::getInstance();
        $cachedInfo = $cache->get('panel', (string)$element->render_panel_id);
        if (is_string($cachedInfo)) {
            return $cachedInfo;
        }
        $svg = <<<SVG
<svg
    data-panelid="{$element->render_panel_id}"
    tag="{$config->get('data>tag', '')}"
    data-click"{$config->get('data-function', '')}"
    width="100%"
    height="100%"
    viewBox="0,0,{$element->render_panel_width},{$element->render_panel_height}"
>{$this->renderElements()}</svg>
SVG;
        $cache->set('panel', (string)$element->render_panel_id, $svg, 86400);

        return $svg;
    }
}
