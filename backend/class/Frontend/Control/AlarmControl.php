<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Frontend\Control;

use noxkiwi\lightsystem\Api\LightSystem\AlarmClient\AlarmClientInterface;
use noxkiwi\lightsystem\Frontend\Control;

/**
 * I am the AlarmControl class.
 *
 * @package      noxkiwi\lightsystem\Frontend\Control
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2019 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class AlarmControl extends Control
{
    public const IDENTIFIER    = 'AlarmControl';
    public const PARAM_AREAS   = 'areas';
    public const PARAM_VALENCE = 'valence';
    /** @var string */
    private string $areas;
    /** @var int */
    private int $valence;

    /**
     * AlarmControl constructor.
     *
     * @param array|null $options
     */
    public function __construct(array $options = null)
    {
        parent::__construct($options);
        $this->areas   = $options[self::PARAM_AREAS] ?? AlarmClientInterface::AREAS_ALL;
        $this->valence = $options[self::PARAM_VALENCE] ?? AlarmClientInterface::VALENCE_ENGAGED;
    }
}
