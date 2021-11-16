<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Variablemanager;

use Exception;
use noxkiwi\cache\Cache;
use noxkiwi\core\ErrorHandler;
use noxkiwi\lightsystem\Variablemanager;
use function is_array;

/**
 * I am the Variablemanager that utilizes the Cache subsystem to work properly.
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class CacheVariablemanager extends Variablemanager
{
    /** @var \noxkiwi\cache\Cache $cache*/
    private Cache $cache;
    /** @var int  */
    private static int $cacheTimeout = 5;
    public function __construct()
    {
        parent::__construct();
        $this->cache = Cache::getInstance();
    }

    /**
     * I will return all variables and values.
     * @return array [All variables and their values as K-V Storage (Variable=>Value).]
     */
    public function getVariables(): array
    {
        try {
            $returnValue = $this->cache->get('VARIABLEMANAGER', 'VARIABLES');
        } catch (Exception $exception) {
            ErrorHandler::handleException($exception);
            $returnValue = [];
        }

        return is_array($returnValue) ? $returnValue : [];
    }

    /**
     * I will store all the variables at once.
     *
     * @param array $variables [The set of variables you want to store.]
     */
    public function setVariables(array $variables): void
    {
        try {
            $this->cache->set('VARIABLEMANAGER', 'VARIABLES', $variables, self::$cacheTimeout);
        } catch (Exception $exception) {
            ErrorHandler::handleException($exception);
        }
    }
}

