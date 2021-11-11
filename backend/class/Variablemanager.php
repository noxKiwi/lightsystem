<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem;

use noxkiwi\lightsystem\Variablemanager\VariablemanagerInterface;
use noxkiwi\log\Traits\LogTrait;
use noxkiwi\singleton\Singleton;

/**
 * I am the update manager. I am used to send only this update data to a client, that he would visualize if it was
 * changed. Basically: CLIENT:  "Hey, give me all the things that changed since my last request!" SERVER:  You are
 * visualizing [Tag1, Tag2, Tag3, Tag4, TagSausage].
 *  -  TagSausage was set to 1.
 *  -  nothing happened
 *  -  Tag2 is "Hello darkness my old friend"
 *  -  Tag3 is "No more sorrow"
 *  -  nothing happened
 *  -  nothing happened
 *  -  nothing happened
 *  -  nothing happened
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan@nox.kiwi>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
abstract class Variablemanager extends Singleton implements VariablemanagerInterface
{
    use LogTrait;

    protected const USE_DRIVER = true;
    /** @var \noxkiwi\lightsystem\Updatemanager I am the Updatemanager instance that is utilized to notify any changes in the process to the clients. */
    protected Updatemanager $updateManager;

    /**
     * Variablemanager constructor.
     * @throws \noxkiwi\singleton\Exception\SingletonException
     */
    protected function __construct()
    {
        parent::__construct();
        $this->updateManager = Updatemanager::getInstance();
    }

    /**
     * I will add the given variable with a null value to the set of variables.
     *
     * @param string $variableName [The name of the new variable that you create.]
     */
    public function addVariable(string $variableName): void
    {
        $this->setVariable($variableName, null, '');
    }

    /**
     * @inheritDoc
     */
    public function setVariable(string $variableName, mixed $variableValue, string $displayValue): void
    {
        // First store the new value.
        $variables                = $this->getVariables();
        $variables[$variableName] = $variableValue;
        $this->setVariables($variables);
        // Now trigger an update.
        $this->updateManager->addUpdate($variableName, $variableValue, $displayValue);
    }

    /**
     * I will return the value that was stored for the given $variableName.
     *
     * @param string $variableName [The variable name that you want to read.]
     *
     * @return mixed [The value of the variable that was stored previously.]
     */
    public function getVariable(string $variableName)
    {
        $variables = $this->getVariables();
        if (! isset($variables[$variableName])) {
            return null;
        }

        return $variables[$variableName];
    }

    /**
     * I will remove the given variable from the set of variables.
     *
     * @param string $variableName [The variable you want to remove.]
     */
    public function removeVariable(string $variableName): void
    {
        $variables = $this->getVariables();
        unset($variables[$variableName]);
        $this->setVariables($variables);
    }
}
