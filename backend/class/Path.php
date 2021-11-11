<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem;

/**
 * I am the base rendering class for process image output.
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan@nox.kiwi>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
abstract class Path extends \noxkiwi\core\Path
{
    public const CONTROL_DIR = self::FRONTEND_DIR . 'control/';
    public const MAIL_TEMPLATE = self::FRONTEND_DIR . 'mail/';
}
