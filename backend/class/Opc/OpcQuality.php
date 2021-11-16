<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Opc;

/**
 * I am the App of the rslightsystem.
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
abstract class OpcQuality
{
    public const BAD                                  = 0;
    public const UNCERTAIN                            = 1;
    public const UNAVAILABLE                          = 2;
    public const GOOD                                 = 3;
    public const QUALITIES                            = [
        self::BAD,
        self::UNCERTAIN,
        self::UNAVAILABLE,
        self::GOOD
    ];
    public const BAD_NON_SPECIFIC                     = 0;
    public const BAD_CONFIG                           = 1;
    public const BAD_NOT_CONNECTED                    = 2;
    public const BAD_DEVICE_FAILURE                   = 3;
    public const BAD_SENSOR_FAILURE                   = 4;
    public const BAD_LAST_KNOWN                       = 5;
    public const BAD_COMM_FAILURE                     = 6;
    public const BAD_OUT_OF_SERVICE                   = 7;
    public const QUALITIES_BAD                        = [
        self::BAD_NON_SPECIFIC,
        self::BAD_CONFIG,
        self::BAD_NOT_CONNECTED,
        self::BAD_DEVICE_FAILURE,
        self::BAD_SENSOR_FAILURE,
        self::BAD_LAST_KNOWN,
        self::BAD_COMM_FAILURE,
        self::BAD_OUT_OF_SERVICE
    ];
    public const UNCERTAIN_NON_SPECIFIC               = 0;
    public const UNCERTAIN_LAST_USABLE                = 1;
    public const UNCERTAIN_SENSOR_UNACCURATE          = 4;
    public const UNCERTAIN_ENGINEERING_UNITS_EXCEEDED = 5;
    public const UNCERTAIN_SUB_NORMAL                 = 6;
    public const QUALITIES_UNCERTAIN                  = [
        self::UNCERTAIN_NON_SPECIFIC,
        self::UNCERTAIN_LAST_USABLE,
        self::UNCERTAIN_SENSOR_UNACCURATE,
        self::UNCERTAIN_ENGINEERING_UNITS_EXCEEDED,
        self::UNCERTAIN_SUB_NORMAL
    ];
    public const GOOD_NON_SPECIFIC                    = 0;
    public const GOOD_LOCAL_OVERRIDE                  = 6;
    public const QUALITIES_GOOD                       = [
        self::GOOD_NON_SPECIFIC,
        self::GOOD_LOCAL_OVERRIDE
    ];
}
