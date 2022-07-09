<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Render;

use noxkiwi\core\Helper\StringHelper;
use noxkiwi\dataabstraction\Entry;
use noxkiwi\lightsystem\Exception\RenderException;
use noxkiwi\lightsystem\Render;
use function array_key_exists;
use function str_replace;
use const E_WARNING;

/**
 * I am the renderer for any SVG tag's attribute.
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class AttributeRender extends Render
{
    /** @var bool I am the trigger that will lead to an exception if the attribute data is missing. */
    private bool $required;

    /**
     * I will construct the Attribute render class.
     *
     * @param \noxkiwi\dataabstraction\Entry $attribute
     * @param array                          $renderData
     * @param bool                           $required
     */
    public function __construct(Entry $attribute, array $renderData, bool $required = null)
    {
        parent::__construct($renderData);
        $this->setElement($attribute);
        $this->required = $required ?? true;
    }

    /**
     * I will render the current element to the output string and return it.
     *
     * @param array $data
     *
     * @throws \noxkiwi\lightsystem\Exception\RenderException
     * @return string
     */
    public function render(array $data): string
    {
        $element       = $this->getElement();
        $attributeName = $element->render_attribute_name;
        if ($this->required && ! array_key_exists($attributeName, $this->getRenderData())) {
            $info = [
                'element'   => $element,
                'data'      => $data,
                'attribute' => $attributeName
            ];
            throw new RenderException("The required {$attributeName} is not defined for the element.", E_WARNING, $info);
        }

        return (string)str_replace("{{$attributeName}}", (string)($data[$attributeName] ?? ''), $data['renderString']);
    }
}
