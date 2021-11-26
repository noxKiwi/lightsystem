<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Helper;

use noxkiwi\lightsystem\Frontend\Number;
use function number_format;

/**
 * I am the helper for tag normalization.
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2019 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class PhysicalHelper
{
    /**
     * @param float|null $number
     *
     * @return string
     */
    public static function celsius(?float $number = null): string
    {
        return self::numberFormat($number, Number::TEMPERATURE_CELSIUS);
    }

    /**
     * @param float       $number
     * @param string|null $unit
     * @param int|null    $decimalPlaces
     * @param string|null $decimalMark
     * @param string|null $thousandsSeparator
     *
     * @return string
     */
    public static function numberFormat(float $number, ?string $unit = null, ?int $decimalPlaces = null, ?string $decimalMark = null, ?string $thousandsSeparator = null): string
    {
        $decimalPlaces      ??= 2;
        $decimalMark        ??= ',';
        $thousandsSeparator ??= '.';
        $formattedNumber    = number_format($number, $decimalPlaces, $decimalMark, $thousandsSeparator) . $unit;

        return <<<HTML
<span class="{$unit}">{$formattedNumber}</span>
HTML;
    }
}
