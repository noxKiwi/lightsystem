<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Helper;

use function count;
use function explode;

/**
 * I am the helper for tag normalization.
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class TagHelper
{
    public const TAG_STATUS  = 'STATUS';
    public const TAG_ALARM   = 'ALARM';
    public const TAG_MEASURE = 'MW';
    public const TAG_AUTO    = 'AUTO';
    public const TAG_HAND    = 'HAND';
    public const TAGS        = [
        self::TAG_STATUS,
        self::TAG_ALARM,
        self::TAG_MEASURE,
        self::TAG_AUTO,
        self::TAG_HAND
    ];

    /**
     * I will normalize the given $finalTag and remove all tag info that lays behind any of the standard tag extensions.
     *
     * @example TagHelper::normalizeTag('JG.WHG.OG.WZ.SOCKET01.STATUS.F_VALUE') ==> 'JG.WHG.OG.WZ.SOCKET01'
     *
     * @param string $finalTag
     *
     * @return string
     */
    public static function normalizeTag(string $finalTag): string
    {
        foreach (self::TAGS as $tag) {
            $tagData = explode($tag, $finalTag);
            if (count($tagData) === 1) {
                continue;
            }

            return (string)$tagData[0];
        }

        return $finalTag;
    }
}
