<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Context;

use noxkiwi\core\Exception\InvalidArgumentException;
use noxkiwi\core\Request;
use noxkiwi\lightsystem\Frontend\Control;
use noxkiwi\lightsystem\Frontend\Control\ControlInterface;
use noxkiwi\lightsystem\Model\RenderPanelModel;
use noxkiwi\lightsystem\Render\PanelRender;

/**
 * I am the Context that renders Panels.
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2018 - 2021 noxkiwi
 * @version      1.0.1
 * @link         https://nox.kiwi/
 */
final class PanelContext extends CallbackContext
{
    private RenderPanelModel $renderPanelModel;

    /**
     * PanelContext constructor.
     * @throws \noxkiwi\singleton\Exception\SingletonException
     */
    public function __construct()
    {
        parent::__construct();
        $this->renderPanelModel = RenderPanelModel::getInstance();
    }

    /**
     * Showing a panel and returning it as JSON object to the client
     *
     * @throws \noxkiwi\core\Exception\InvalidArgumentException
     * @throws \noxkiwi\lightsystem\Exception\RenderException
     * @throws \noxkiwi\singleton\Exception\SingletonException
     */
    protected function viewShow(): void
    {
        $panelId = Request::getInstance()->get('render_panel_id', 0);
        if ($panelId === 'control') {
            $this->response->set('render_panel_id', rand(1, 94239));
            $this->response->add(
                Control::getControl($this->request->get('panel_data>control'))->run()
            );

            return;
        }
        $panelId = (int)$panelId;
        if ($panelId <= 0) {
            throw new InvalidArgumentException('You must provide the parameter <b>render_panel_id</b> as valid natural number.', E_WARNING);
        }
        $panel = $this->renderPanelModel->load($panelId);
        if (empty($panel)) {
            throw new InvalidArgumentException("There is no Panel {$panelId}", E_WARNING);
        }
        $this->response->add($panel);
        $panelModel    = RenderPanelModel::getInstance();
        $panelEntry    = $panelModel->loadEntry($panelId);
        $panelRenderer = new PanelRender($panelEntry, []);
        $this->response->set(ControlInterface::RESPONSE_DATA, $panelRenderer->render([]));
    }
}

