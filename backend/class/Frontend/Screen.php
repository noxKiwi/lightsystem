<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Frontend;

use noxkiwi\core\Frontend\Renderable\HTML\Div;
use noxkiwi\core\Traits\TranslationTrait;
use ReflectionClass;

/**
 * I am the blank control class.
 *
 * @package      noxkiwi\lightsystem\Frontend
 * @author       Jan Nox <jan@nox.kiwi>
 * @license      https://nox.kiwi/license
 * @copyright    2019 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
abstract class Screen extends Div
{
    use TranslationTrait;

    public const COLUMN_LEFT   = 1;
    public const COLUMN_MIDDLE = 2;
    public const COLUMN_RIGHT  = 3;
    /** @var \noxkiwi\lightsystem\Frontend\Box[][] */
    private array $boxes;
    /** @var string */
    private string $title;
    /** @var int */
    private int $sizeLeft = 3;
    /** @var int */
    private int $sizeMiddle = 6;
    /** @var int */
    private int $sizeRight = 3;

    /**
     * Screen constructor.
     *
     * @param array|null $data
     *
     * @throws \ReflectionException
     */
    public function __construct(?array $data = null)
    {
        $screenName = (new ReflectionClass($this))->getShortName();
        $this->setTitle($this->translate("{$screenName}_TITLE"));
    }

    /**
     * @param array $boxConfig
     *
     * @return \noxkiwi\lightsystem\Frontend\Screen
     */
    public function putBoxConfig(array $boxConfig): Screen
    {
        $className = $boxConfig['class'] . 'Box';
        $box       = $this->getBox($className);
        if (empty($box)) {
            $box = new $className();
            $pos = $this->normalizePosition($boxConfig['position']);
            $box->setPositionX($pos);
            $this->addBox($box);
        }
        # Delete if configured and allowed
        $boxConfig['deleted'] ??= false;
        if ($boxConfig['deleted'] === true && $box->isDeletable()) {
            $this->removeBox($box);
        }
        # Collapse if configured and allowed
        if (array_key_exists('collapsed', $boxConfig) && $box->isCollapsible()) {
            $box->setCollapsed($boxConfig['collapsed']);
        }

        return $this;
    }

    /**
     * @param string $boxName
     *
     * @return \noxkiwi\lightsystem\Frontend\Box|null
     */
    public function getBox(string $boxName): ?Box
    {
        if (empty($this->boxes)) {
            return null;
        }
        foreach ($this->boxes as $xBoxes) {
            foreach ($xBoxes as $box) {
                $class = get_class($box);
                if ($class === $boxName) {
                    return $box;
                }
            }
        }

        return null;
    }

    /**
     * @param string $column
     *
     * @return int
     */
    private function normalizePosition(string $column): int
    {
        if ($column === 'LEFT') {
            return static::COLUMN_LEFT;
        }
        if ($column === 'MIDDLE') {
            return static::COLUMN_MIDDLE;
        }
        if ($column === 'RIGHT') {
            return static::COLUMN_RIGHT;
        }

        return 0;
    }

    /**
     * I will add the given $box to the Screen.
     *
     * @param \noxkiwi\lightsystem\Frontend\Box $box
     *
     * @return \noxkiwi\lightsystem\Frontend\Screen
     */
    final public function addBox(Box $box): Screen
    {
        if (empty($box->getPositionX())) {
            $maxPos = $this->getMaxPositionX();
            $box->setPositionX($maxPos);
        }
        if (empty($box->getPositionY())) {
            $maxPos = $this->getMaxPositionY($box->getPositionX());
            $box->setPositionY($maxPos);
        }
        $this->boxes [$box->getPositionX()][$box->getPositionY()] = $box;

        return $this;
    }

    /**
     * I will return the maximum position to add the box to the last column.
     * @return int
     */
    private function getMaxPositionX(): int
    {
        if (empty($this->boxes)) {
            return static::COLUMN_LEFT;
        }

        return array_keys($this->boxes)[count($this->boxes) - 1];
    }

    /**
     * I will return the position of the last box to add the box after it.
     *
     * @param int $columnX
     *
     * @return int
     */
    private function getMaxPositionY(int $columnX): int
    {
        if (empty($this->boxes[$columnX])) {
            return 0;
        }

        return array_keys($this->boxes[$columnX])[count($this->boxes[$columnX]) - 1] + 1;
    }

    /**
     * @param \noxkiwi\lightsystem\Frontend\Box $deleteBox
     *
     * @return \noxkiwi\lightsystem\Frontend\Screen
     */
    public function removeBox(Box $deleteBox): Screen
    {
        if (empty($this->boxes)) {
            return $this;
        }
        foreach ($this->boxes as $x => $xBoxes) {
            foreach ($xBoxes as $y => $box) {
                if ($deleteBox === $box) {
                    unset ($this->boxes[$x][$y]);
                }
            }
        }

        return $this;
    }

    /**
     * @param int|null $sizeLeft
     *
     * @return \noxkiwi\lightsystem\Frontend\Screen
     */
    final public function setSizeLeft(?int $sizeLeft): Screen
    {
        if (empty($sizeLeft)) {
            return $this;
        }
        $this->sizeLeft = $sizeLeft;

        return $this;
    }

    /**
     * @param int|null $sizeMiddle
     *
     * @return \noxkiwi\lightsystem\Frontend\Screen
     */
    final public function setSizeMiddle(?int $sizeMiddle): Screen
    {
        if (empty($sizeMiddle)) {
            return $this;
        }
        $this->sizeMiddle = $sizeMiddle;

        return $this;
    }

    /**
     * @param int|null $sizeRight
     *
     * @return \noxkiwi\lightsystem\Frontend\Screen
     */
    final public function setSizeRight(?int $sizeRight): Screen
    {
        if (empty($sizeRight)) {
            return $this;
        }
        $this->sizeRight = $sizeRight;

        return $this;
    }

    /**
     * I will add all boxes that were given in the $boxes array.
     *
     * @param \noxkiwi\lightsystem\Frontend\Box[] $boxes
     *
     * @return \noxkiwi\lightsystem\Frontend\Screen
     */
    final public function addBoxes(array $boxes): Screen
    {
        foreach ($boxes as $box) {
            if (! $box instanceof Box) {
                continue;
            }
            $this->addBox($box);
        }

        return $this;
    }

    /**
     * I will output the screen.
     * @return string
     */
    public function output(): string
    {
        return <<<HTML
<div class="container-fluid">
    <h3>{$this->getTitle()}</h3>
    <div class="row">
        <div class="col-md-{$this->sizeLeft}">{$this->outputColumn(static::COLUMN_LEFT)}</div>
        <div class="col-md-{$this->sizeMiddle}">{$this->outputColumn(static::COLUMN_MIDDLE)}</div>
        <div class="col-md-{$this->sizeRight}">{$this->outputColumn(static::COLUMN_RIGHT)}</div>
    </div>
</div>
HTML;
    }

    /**
     * I will solely return the title.
     * @return string
     */
    final public function getTitle(): string
    {
        return $this->title;
    }

	/**
	 * I will solely set the title.
	 *
	 * @param string|null $title
	 *
	 * @return \noxkiwi\lightsystem\Frontend\Screen
	 */
    final public function setTitle(?string $title): Screen
    {
        if (empty($title)) {
            return $this;
        }
        $this->title = $title;

        return $this;
    }

    /**
     * I will output a column
     *
     * @param int $column
     *
     * @return string
     */
    protected function outputColumn(int $column): string
    {
        $return  = '';
        $columns = $this->boxes[$column] ?? [];
        foreach ($columns as $boxesX) {
            $return .= $boxesX->output();
        }

        return $return;
    }
}
