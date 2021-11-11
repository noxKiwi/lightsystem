<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Validator\Text;

use noxkiwi\lightsystem\Model\TimeswitchModel;
use noxkiwi\validator\Validator\TextValidator;

/**
 * I am the validator for timeswitch control strings.
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan@nox.kiwi>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class TimeswitchValidator extends TextValidator
{
    /**
     * @inheritDoc
     */
    protected function __construct(array $options = [])
    {
        $this->setOptions(
            [
                self::OPTION_MINLENGTH     => 96,
                self::OPTION_MAXLENGTH     => 96,
                self::OPTION_CHARS_ALLOWED => TimeSwitchModel::STATUS_ENABLED . TimeSwitchModel::STATUS_DISABLED
            ]
        );
        parent::__construct($options);
    }
}
