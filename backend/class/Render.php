<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem;

use Exception;
use noxkiwi\core\Config;
use noxkiwi\core\Helper\JsonHelper;
use noxkiwi\core\Traits\LanguageImprovementTrait;
use noxkiwi\dataabstraction\Entry;
use noxkiwi\lightsystem\Exception\RenderException;
use noxkiwi\lightsystem\Model\RenderElementModel;
use noxkiwi\lightsystem\Model\RenderPanelModel;
use noxkiwi\lightsystem\Model\RenderSvgTagModel;
use noxkiwi\lightsystem\Render\PanelelementRender;
use noxkiwi\lightsystem\Render\RenderInterface;
use noxkiwi\lightsystem\Render\SvgtagRender;
use noxkiwi\log\Traits\LogTrait;
use function count;
use const E_ERROR;

/**
 * I am the base rendering class for process image output.
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan@nox.kiwi>
 * @license      https://nox.kiwi/license
 * @copyright    2018 - 2021 noxkiwi
 * @version      1.0.1
 * @link         https://nox.kiwi/
 */
abstract class Render implements RenderInterface
{
    use LanguageImprovementTrait;
    use LogTrait;

    /** @var \noxkiwi\core\Config */
    private Config $config;
    /** @var array */
    private array $renderData;
    /** @var array[] */
    private array $elements;
    /** @var \noxkiwi\dataabstraction\Entry */
    private Entry $element;

    /**
     * I will construct the base render class.
     *
     * @param array|null $renderData
     */
    public function __construct(?array $renderData)
    {
        $this->setRenderData($renderData ?? []);
        $this->setRenderString('');
    }

    final protected function setRenderString(string $element): void
    {
        $data                 = $this->getRenderData();
        $data['renderString'] = $element;
        $this->setRenderData($data);
    }

    /**
     * @return array|null
     */
    final protected function getRenderData(): ?array
    {
        return $this->renderData;
    }

    /**
     * @param array $renderData
     */
    final protected function setRenderData(array $renderData): void
    {
        $this->logDebug('Setting renderData:');
        $this->logDebug(JsonHelper::encode($renderData));
        $this->renderData = $renderData;
        $this->config     = new Config($renderData);
    }

    /**
     * I may render a click action.
     *
     * @param array $clickData
     *
     * @return string
     */
    public static function renderClick(array $clickData): string
    {
        if (! count($clickData)) {
            return '';
        }

        return 'data-clickdata=\'' . JsonHelper::encode($clickData) . '\'';
    }

    final protected function getRenderString(): string
    {
        return $this->getRenderData()['renderString'] ?? 'EMPTY RENDER STRING';
    }

    /**
     * I will render all children of this element and return the rendered output.
     *
     * @throws \noxkiwi\lightsystem\Exception\RenderException
     * @return string
     */
    final protected function renderElements(): string
    {
        $innerSvg = '';
        foreach ($this->getElements() as $element) {
            $innerSvg .= $this->renderElement($element);
        }

        return $innerSvg;
    }

    /**
     * @return array
     */
    final protected function getElements(): array
    {
        $this->logDebug('getting elements...');
        $this->logDebug(JsonHelper::encode($this->elements));

        return $this->elements;
    }

    /**
     * @param array $elements
     */
    final protected function setElements(array $elements): void
    {
        $this->logDebug('Setting Elements:');
        $this->logDebug(JsonHelper::encode($elements));
        $this->elements = $elements;
    }

    /**
     * I will render the given $element whether it is a tag or a panel-element.
     *
     * @param array $element
     *
     * @throws \noxkiwi\lightsystem\Exception\RenderException
     * @return string
     */
    final protected function renderElement(array $element): string
    {
        try {
            switch ($element['type'] ?? 'UNKNOWN') {
                case RenderPanelModel::OBJECTTYPE_ELEMENT:
                    $panelElementModel = RenderElementModel::getInstance();
                    $panelElementEntry = $panelElementModel->loadEntry((int)$element['id']);
                    $renderer          = new PanelelementRender($panelElementEntry, (array)$element['data']);
                    $renderedString    = $renderer->render((array)$element['data']);
                    break;
                case RenderPanelModel::OBJECTTYPE_SVGTAG:
                    $svgTagModel    = RenderSvgTagModel::getInstance();
                    $svgTagEntry    = $svgTagModel->loadEntry((int)$element['id']);
                    $renderer       = new SvgtagRender($svgTagEntry, (array)$element['data']);
                    $renderedString = $renderer->render((array)$element['data']);
                    break;
                default:
                    $renderedString = $element['type'] . 'TYPE NOT FOUND';
                    break;
            }

            return $renderedString;
        } catch (Exception $exception) {
            throw new RenderException($exception->getMessage(), E_ERROR);
        }
    }

    /**
     * @return \noxkiwi\core\Config
     */
    final protected function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * I will solely return the element.
     * @return \noxkiwi\dataabstraction\Entry
     */
    final protected function getElement(): Entry
    {
        return $this->element;
    }

    /**
     * I will solely set the renderable element of the instance.
     *
     * @param \noxkiwi\dataabstraction\Entry $renderElement
     */
    final protected function setElement(Entry $renderElement): void
    {
        $this->element = $renderElement;
    }
}
