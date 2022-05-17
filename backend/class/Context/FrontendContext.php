<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Context;

use noxkiwi\lightsystem\Model\AnimationModel;
use noxkiwi\lightsystem\Model\OpcItemModel;
use noxkiwi\lightsystem\Updatemanager;
use noxkiwi\lightsystem\Value\Structure\AnimationValue;
use noxkiwi\lightsystem\Value\Structure\UpdateValue;
use noxkiwi\lightsystem\Variablemanager;

/**
 * I am the context that manages front-end updates.
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan.nox@pm.me>
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
        $v = Variablemanager::getInstance();
        $variables =$v->getVariables();
        foreach ($variables as $tag => $value) {

            $update = new UpdateValue(
                compact('tag', 'value')
            );
            $animations = AnimationModel::getAnimations($update);
            $this->response->set("tag>$tag", $value);
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
        $value     = $animationValue->get()['value'] ?? null;
        if(is_array($value)) {
            $value     = json_encode($value);
        }
        $this->addCallback("Animate.tag('$tag', '$attribute', '$value');");
    }
}
