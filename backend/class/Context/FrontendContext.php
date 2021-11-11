<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Context;

use noxkiwi\lightsystem\Model\AnimationModel;
use noxkiwi\lightsystem\Model\OpcItemModel;
use noxkiwi\lightsystem\Updatemanager;
use noxkiwi\lightsystem\Value\Structure\AnimationValue;
use noxkiwi\lightsystem\Value\Structure\UpdateValue;
use function var_dump;

/**
 * I am the context that manages front-end updates.
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan@nox.kiwi>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class FrontendContext extends CallbackContext
{
    /** @var \noxkiwi\lightsystem\Updatemanager */
    private Updatemanager $updateManager;
    /** @var \noxkiwi\lightsystem\Model\OpcItemModel */
    private OpcItemModel $opcItemModel;

    /**
     * FrontendContext constructor.
     * @throws \noxkiwi\singleton\Exception\SingletonException
     */
    public function __construct()
    {
        parent::__construct();
        $this->updateManager = Updatemanager::getInstance();
        $this->opcItemModel  = OpcItemModel::getInstance();
    }

    /**
     * I will return all actions that have been stored for the currently logged in user.
     *
     * @throws \noxkiwi\dataabstraction\Exception\ModelException
     */
    protected function viewUpdate(): void
    {
        $updates = $this->updateManager->getUpdates();
        foreach ($updates as $update) {
            if (! $update instanceof UpdateValue) {
                continue;
            }
            $animations = AnimationModel::getAnimations($update);
            foreach ($animations as $animation) {
                $this->addAnimation($animation);
            }
        }
    }

    /**
     * @param \noxkiwi\lightsystem\Value\Structure\AnimationValue $animationValue
     */
    private function addAnimation(AnimationValue $animationValue): void
    {
        $tag       = $animationValue->get()['tag'];
        $attribute = $animationValue->get()['attribute'];
        $value     = $animationValue->get()['value'];
        $this->addCallback("Animate.tag('$tag', '$attribute', '$value');");
    }
}
