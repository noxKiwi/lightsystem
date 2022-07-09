<?php declare(strict_types=1);

namespace noxkiwi\lightsystem\Api\LightSystem\BaseClient;

use DateTime;
use Exception;
use noxkiwi\core\ErrorHandler;
use noxkiwi\core\Helper\DateTimeHelper;
use const E_USER_NOTICE;

/**
 * I am an arbitrary Comparison.
 *
 * @package      noxkiwi\lightsystem\Api\Lightsystem\BaseClient
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2022 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
class Comparison
{
    public string $comparisonType;
    public mixed $comparisonValue;
}

