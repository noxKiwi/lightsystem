<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Render;

use noxkiwi\dataabstraction\Entry;
use noxkiwi\lightsystem\Model\AttributeModel;
use noxkiwi\lightsystem\Render;
use function explode;

/**
 * I am the renderer for an SVG Tag.
 * During the rendering process I will delegate data to other Render classes.
 *  - attributes    @package      noxkiwi\LightSystem
 * @see          \noxkiwi\lightsystem\Render\AttributeRender
 *
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class SvgtagRender extends Render
{
    /**
     * I will construct the SVG tag renderer.
     *
     * @param \noxkiwi\dataabstraction\Entry $renderElement
     * @param array|null                     $renderData
     *
     */
    public function __construct(Entry $renderElement, ?array $renderData)
    {
        parent::__construct($renderData);
        $this->setElement($renderElement);
        $this->setRenderString((string)$renderElement->render_svgtag_svg);
    }

    /**
     * I will render the current element to the output string and return it.
     *
     * @param array $data
     *
     * @throws \noxkiwi\dataabstraction\Exception\EntryInconsistentException
     * @throws \noxkiwi\dataabstraction\Exception\EntryMissingException
     * @throws \noxkiwi\lightsystem\Exception\RenderException
     * @throws \noxkiwi\singleton\Exception\SingletonException
     * @return string
     */
    public function render(array $data): string
    {
        $element = $this->getElement();
        $this->renderAttributes((array)json_decode((string)$element->render_svgtag_requiredattributes, true), true);
        $this->renderAttributes((array)json_decode((string)$element->render_svgtag_optionalattributes, true), false);

        return $this->getRenderString();
    }

    /**
     * I will render all attributes into the SVG tag code.
     *
     * @param string[]|int[] $attributeIds
     * @param bool           $required
     *
     * @throws \noxkiwi\dataabstraction\Exception\EntryInconsistentException
     * @throws \noxkiwi\dataabstraction\Exception\EntryMissingException
     * @throws \noxkiwi\lightsystem\Exception\RenderException
     * @throws \noxkiwi\singleton\Exception\SingletonException
     * @return string
     */
    private function renderAttributes(array $attributeIds, bool $required): string
    {
        $config         = $this->getConfig();
        $attributeModel = AttributeModel::getInstance();
        foreach ($attributeIds as $attributeId) {
            if(empty($attributeId)) {
                continue;
            }
            $attribute       = $attributeModel->loadEntry($attributeId);
            if(empty($attribute)) {
                continue;
            }
            $attributeRender = new AttributeRender($attribute, $config->get(), $required);
            $attributeString = $attributeRender->render($this->getRenderData());
            $this->setRenderString($attributeString);
        }

        return $this->getRenderString();
    }
}
