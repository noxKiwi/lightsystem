<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Variablemanager;

/**
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
interface VariablemanagerInterface
{
    /**
     * I will return all variables and values.
     * @return array [All variables and their values as K-V Storage (Variable=>Value).]
     */
    public function getVariables(): array;

    /**
     * I will return the value that was stored for the given $variableName.
     *
     * @param string $variableName [The variable name that you want to read.]
     *
     * @return mixed [The value of the variable that was stored previously.]
     */
    public function getVariable(string $variableName);

    /**
     * I will add the given variable with a null value to the set of variables.
     *
     * @param string $variableName [The name of the new variable that you create.]
     */
    public function addVariable(string $variableName): void;

    /**
     * I will remove the given variable from the set of variables.
     *
     * @param string $variableName [The variable you want to remove.]
     */
    public function removeVariable(string $variableName): void;

    /**
     * I will store all the variables at once.
     *
     * @param array $variables [The set of variables you want to store.]
     */
    public function setVariables(array $variables): void;

    /**
     * I will save the given variable name and value in the set of variables.
     *
     * @param string $variableName  [The variable name you want to use.]
     * @param mixed  $variableValue [The variable value that will be written.]
     * @param string $displayValue  [The value that will be sent to the update Manager]
     */
    public function setVariable(string $variableName, mixed $variableValue, string $displayValue): void;
}
