<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Frontend\Control;

use noxkiwi\lightsystem\Frontend\Control;

/**
 * I am the ChartControl class.
 *
 * @package      noxkiwi\lightsystem\Frontend\Control
 * @author       Jan Nox <jan@nox.kiwi>
 * @license      https://nox.kiwi/license
 * @copyright    2019 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class ChartControl extends Control
{
    public const IDENTIFIER = 'ChartControl';

    /**
     * Control constructor.
     *
     * @param array|null $options
     */
    public function __construct(array $options = null)
    {
        parent::__construct($options);
        $this->setWidth($options[ControlInterface::OPTION_WIDTH] ?? 1380);
        $this->setHeight($options[ControlInterface::OPTION_HEIGHT] ?? 768);
    }
}
