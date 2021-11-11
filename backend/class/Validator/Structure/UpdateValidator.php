<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Validator\Structure;


use noxkiwi\validator\Validator\StructureValidator;

/**
 * I am
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan@nox.kiwi>
 * @license      https://nox.kiwi/license
 *
 * @copyright    2019 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class UpdateValidator extends StructureValidator
{
    /**
     * @var array
     */
    protected array $structureDesign = [
        'tag'   => 'Text_Tag',
        'value' => 'arbitrary'
    ];

    /**
     * @inheritDoc
     */
    public function validate($value, ?array $options = null): array
    {
        return [];
    }
}
