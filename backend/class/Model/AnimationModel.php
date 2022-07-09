<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Model;

use noxkiwi\core\ErrorHandler;
use noxkiwi\core\Exception\InvalidArgumentException;
use noxkiwi\dataabstraction\Model;
use noxkiwi\lightsystem\Value\Structure\AnimationValue;
use noxkiwi\lightsystem\Value\Structure\UpdateValue;
use function strpos;

/**
 * I am the storage for different failure classes
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class AnimationModel extends Model
{
    public const    TABLE         = 'render_animation';
    public const    COMPARE_EQ    = 'EQ';
    public const    COMPARE_GT    = 'GT';
    public const    COMPARE_GTE   = 'GTE';
    public const    COMPARE_LTE   = 'LTE';
    public const    COMPARE_LT    = 'LT';
    public const    COMPARE_NEQ   = 'NEQ';
    public const    SPACE_VALUE   = '{value}';
    public const    SPACE_DISPLAY = '{display}';

    /**
     * I will return a set of animations that will be processed on the front-end for the given $address and $value.
     *
     * @param \noxkiwi\lightsystem\Value\Structure\UpdateValue $updateValue
     *
     * @throws \noxkiwi\singleton\Exception\SingletonException
     * @return array
     */
    public static function getAnimations(UpdateValue $updateValue): array
    {
        $animationData = [];
        try {
            $opcItemModel = OpcItemModel::getInstance();
            $opcItem      = $opcItemModel->loadByUnique('opc_item_address', $updateValue->get()['tag']);
            if (empty($opcItem)) {
                return self::getDefaultAnimations($updateValue);
            }
            $animationModel = self::getInstance();
            $animationModel->addFilter('opc_item_id', $opcItem['opc_item_id']);
            $animationModel->useCache();
            $animationModel->search();
            $animationEntries = $animationModel->getResult();
            foreach ($animationEntries as $animationEntry) {
                $animationDataSets = $animationEntry['render_animation_data'] ?? [];
                foreach ($animationDataSets as $animationDatum) {
                    $myAnimations = self::chooseAnimations($updateValue->get()['tag'], $animationDatum, $updateValue->get()['value']);
                    foreach($myAnimations as $myAnimation) {
                        $animationData[] = $myAnimation;
                    }
                }
            }
        } catch (InvalidArgumentException $exception) {
            ErrorHandler::handleException($exception);
        }

        return $animationData;
    }

    /**
     * @param \noxkiwi\lightsystem\Value\Structure\UpdateValue $updateValue
     *
     * @throws \noxkiwi\core\Exception\InvalidArgumentException
     * @return \noxkiwi\lightsystem\Value\Structure\AnimationValue[]
     */
    private static function getDefaultAnimations(UpdateValue $updateValue): array
    {
        $value                 = $updateValue->get()['value'];
        $address               = $updateValue->get()['tag'];
        $animations         [] = new AnimationValue(
            [
                'tag'       => $updateValue->get()['tag'],
                'attribute' => 'text',
                'value'     => $value
            ]
        );
        $alarm                 = 'ALARM.B_VALUE';
        if (strpos($address, $alarm) !== false) {
            if ($value == true) {
                $animations [] = new AnimationValue(['tag' => $updateValue->get()['tag'], 'attribute' => 'blink', 'value' => ['colors' => ['#f00', '#ff0']]]);
            } else {
                $animations [] = new AnimationValue(['tag' => $updateValue->get()['tag'], 'attribute' => 'noblink', 'value' => 'transparent']);
            }
        }
        $direction = 'DIRECTION';
        if (strpos($address, $direction) !== false) {
            $animations [] = new AnimationValue(['tag' => $updateValue->get()['tag'], 'attribute' => 'rotate', 'value' => $value]);
        }
        $operatingMode = '.OM.F_VALUE';
        if (strpos($address, $operatingMode) !== false) {
            if ($value === true) {
                $animations [] = new AnimationValue(['tag' => $updateValue->get()['tag'], 'attribute' => 'fill', 'value' => 'lime']);
            } else {
                $animations [] = new AnimationValue(['tag' => $updateValue->get()['tag'], 'attribute' => 'fill', 'value' => 'grey']);
            }
        }
        $binaryCommand = '.BC.B_VALUE';
        if (strpos($address, $binaryCommand) !== false) {
            if ((int)$value === 1) {
                $animations [] = new AnimationValue(['tag' => $updateValue->get()['tag'], 'attribute' => 'fill', 'value' => 'lime']);
            } else {
                $animations [] = new AnimationValue(['tag' => $updateValue->get()['tag'], 'attribute' => 'fill', 'value' => 'transparent']);
            }
        }

        return $animations;
    }

    /**
     * I will use the given $animationDatum and the $value to return all animations that fit their conditions.
     *
     * @param array $animationDatum
     * @param array $value
     *
     * @return \noxkiwi\lightsystem\Value\Structure\AnimationValue[]
     */
    private static function chooseAnimations(string $tag, array $animationDatum, mixed $value): array
    {
        $animations = [];
        if (! self::runComparison($value, $animationDatum['comparator'], $animationDatum['value'])) {
            return [];
        }
        foreach ($animationDatum['animations'] as $animation) {
            $animation['tag'] = $tag;
            try {
                $animations[] = new AnimationValue($animation);
            } catch (InvalidArgumentException $exception) {
                ErrorHandler::handleException($exception);
            }
        }

        return $animations;
    }

    /**
     * @param        $value
     * @param string $comparator
     * @param mixed  $compValue
     *
     * @return bool
     */
    private static function runComparison(mixed $value, string $comparator, mixed $compValue): bool
    {
        switch ($comparator) {
            case self::COMPARE_EQ:
                return $value == $compValue;
            case self::COMPARE_GT:
                return $value > $compValue;
            case self::COMPARE_GTE:
                return $value >= $compValue;
            case self::COMPARE_LTE:
                return $value <= $compValue;
            case self::COMPARE_LT:
                return $value < $compValue;
            case self::COMPARE_NEQ:
                return $value != $compValue;
            default:
                return false;
        }
    }
}
