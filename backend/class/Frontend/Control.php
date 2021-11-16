<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Frontend;

use noxkiwi\core\Helper\FrontendHelper;
use noxkiwi\core\Traits\TranslationTrait;
use noxkiwi\lightsystem\Frontend\Control\ControlInterface;
use noxkiwi\lightsystem\Path;

/**
 * I am the blank control class.
 *
 * @package      noxkiwi\lightsystem\Frontend
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2019 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
abstract class Control implements ControlInterface
{
    use TranslationTrait;

    public const IDENTIFIER = '';
    /** @var array */
    private static array $controls;
    /** @var int */
    private int $width;
    /** @var int */
    private int $height;

    /**
     * Control constructor.
     *
     * @param array|null $options
     */
    public function __construct(array $options = null)
    {
        $this->setWidth($options[ControlInterface::OPTION_WIDTH] ?? 800);
        $this->setHeight($options[ControlInterface::OPTION_HEIGHT] ?? 350);
    }

    /**
     * @param int $width
     *
     * @return \noxkiwi\lightsystem\Frontend\Control
     */
    final public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @param int $height
     *
     * @return \noxkiwi\lightsystem\Frontend\Control
     */
    final public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @param \noxkiwi\lightsystem\Frontend\Control $control
     */
    final public static function addControl(Control $control): void
    {
        static::$controls[$control::IDENTIFIER] = $control;
    }

    /**
     * @return array
     */
    final public static function getControls(): array
    {
        return static::$controls;
    }

    /**
     * @param string $identifier
     *
     * @return \noxkiwi\lightsystem\Frontend\Control
     */
    public static function getControl(string $identifier): Control
    {
        return static::$controls[$identifier];
    }

    /**
     * @return array
     */
    public function run(): array
    {
        return $this->output();
    }

    /**
     * @return array
     */
    final protected function output(): array
    {
        return [
            static::RESPONSE_DATA   => FrontendHelper::parseFile(Path::getInheritedPath(Path::CONTROL_DIR . static::IDENTIFIER . '.php')),
            static::RESPONSE_HEIGHT => $this->height ?? 200,
            static::RESPONSE_WIDTH  => $this->width ?? 200,
            static::RESPONSE_TITLE  => $this->translate(static::IDENTIFIER)
        ];
    }
}
