<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Frontend\Screen;

use noxkiwi\lightsystem\Frontend\Box\AggregateBox;
use noxkiwi\lightsystem\Frontend\Box\PanelBox;
use noxkiwi\lightsystem\Frontend\Box\ServerBox;
use noxkiwi\lightsystem\Frontend\Box\SetvalueBox;
use noxkiwi\lightsystem\Frontend\Screen;

/**
 * I am the helper for tag normalization.
 *
 * @package      noxkiwi\lightsystem\Frontend\Screen
 * @author       Jan Nox <jan@nox.kiwi>
 * @license      https://nox.kiwi/license
 * @copyright    2019 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class OpcitemScreen extends Screen
{
    /**
     * OpcitemScreen constructor.
     *
     * @param array|null $data
     *
     * @throws \ReflectionException
     */
    public function __construct(?array $data = null)
    {
        parent::__construct($data);
        $this->init($data);
    }

    /**
     * I will initialize the object.
     *
     * @param array|null $data
     *
     * @throws \ReflectionException
     */
    private function init(?array $data = null): void
    {
        $pB = new PanelBox($data);
        $pB->setPositionX(Screen::COLUMN_MIDDLE);
        $this->addBox($pB);
        $this->addBox(new AggregateBox($data));
        $this->addBox(new ServerBox(['serverId' => 6]));
        $this->addBox(new SetvalueBox($data));
    }

    /**
     * @param array $data
     */
    public function render(array $data): void
    {
    }
}
