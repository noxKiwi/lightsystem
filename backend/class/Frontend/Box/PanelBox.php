<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Frontend\Box;

use noxkiwi\core\Helper\FrontendHelper;
use noxkiwi\core\Path;
use noxkiwi\dataabstraction\Entry;
use noxkiwi\lightsystem\Frontend\Box;
use noxkiwi\lightsystem\Model\RenderPanelModel;
use noxkiwi\lightsystem\Render\PanelRender;

/**
 * I am the blank control class.
 *
 * @package      noxkiwi\lightsystem\Frontend
 * @author       Jan Nox <jan@nox.kiwi>
 * @license      https://nox.kiwi/license
 * @copyright    2019 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class PanelBox extends Box
{
    /** @var \noxkiwi\dataabstraction\Entry */
    private Entry $panel;

    /**
     * @param array $data
     *
     * @throws \noxkiwi\core\Exception\InvalidArgumentException
     * @throws \noxkiwi\dataabstraction\Exception\ModelException
     * @throws \noxkiwi\lightsystem\Exception\RenderException
     */
    public function render(array $data): void
    {
        $file    = Path::FRONTEND_DIR . '/box/panel.php';
        $path    = Path::getInheritedPath($file);
        $content = FrontendHelper::parseFile($path, (new PanelRender((int)$this->panel->render_panel_id, $data))->render($data));
        $this->setContent($content);
    }

    /**
     * @param array|null $options
     *
     * @throws \ReflectionException
     * @throws \noxkiwi\dataabstraction\Exception\EntryInconsistentException
     * @throws \noxkiwi\dataabstraction\Exception\EntryMissingException
     * @throws \noxkiwi\dataabstraction\Exception\ModelException
     */
    protected function setOptions(?array $options): void
    {
        parent::setOptions($options);
        if (! empty($options['panelId'])) {
            $panel = RenderPanelModel::getInstance()->loadEntry($options['panelId']);
            if ($panel !== null) {
                $this->panel = $panel;
            }
        }
    }
}
