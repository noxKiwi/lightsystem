<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Frontend;

use noxkiwi\core\Frontend\Icon;
use noxkiwi\core\Frontend\Renderable\HTML\Div;
use noxkiwi\core\Traits\TranslationTrait;
use ReflectionClass;
use function uniqid;

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
abstract class Box extends Div
{
    use TranslationTrait;

    public const POSITION_X = 'posX';
    public const POSITION_Y = 'posY';
    /** @var int */
    private int $positionX;
    /** @var int */
    private int $positionY;
    /** @var int */
    private int $width;
    /** @var int */
    private int $height;
    /** @var bool */
    private bool $collapsed;
    /** @var string */
    private string $title;
    /** @var string */
    private string $content;
    /** @var bool */
    private bool $draggable;
    /** @var bool */
    private bool $fullScreenable;
    /** @var bool */
    private bool $resizable;
    /** @var bool */
    private bool $collapsible;
    /** @var bool */
    private bool $deletable;
    /** @var array */
    private array $icon;

    /**
     * Box constructor.
     *
     * @param array $options
     *
     * @throws \ReflectionException
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
        $this->setContent(static::class);
    }

    /**
     * @param array|null $options
     *
     * @throws \ReflectionException
     */
    protected function setOptions(?array $options): void
    {
        $boxName = (new ReflectionClass($this))->getShortName();
        $this->setPositionX($options[static::POSITION_X] ?? Screen::COLUMN_LEFT);
        $this->setPositionY($options[static::POSITION_Y] ?? 0);
        $this->setWidth(3);
        $this->setHeight(4);
        $this->setCollapsed(false);
        $this->setTitle($this->translate("{$boxName}_TITLE"));
        $this->setContent($this->translate("{$boxName}_CONTENT"));
        $this->setResizable(true);
        $this->setCollapsible(true);
        $this->setDeletable(true);
        $this->setDraggable(true);
        $this->setFullScreenable(true);
        $this->setIcon(null);
    }

    /**
     * @param int $width
     *
     * @return \noxkiwi\lightsystem\Frontend\Box
     */
    final public function setWidth(int $width): Box
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @param int $height
     *
     * @return \noxkiwi\lightsystem\Frontend\Box
     */
    final public function setHeight(int $height): Box
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @param string $title
     *
     * @return \noxkiwi\lightsystem\Frontend\Box
     */
    final public function setTitle(string $title): Box
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param string $content
     *
     * @return \noxkiwi\lightsystem\Frontend\Box
     */
    final public function setContent(string $content): Box
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @param bool|null $draggable
     *
     * @return \noxkiwi\lightsystem\Frontend\Box
     */
    final protected function setDraggable(bool $draggable = null): Box
    {
        $this->draggable = $draggable ?? true;

        return $this;
    }

    /**
     * @param array|null $icon
     *
     * @return \noxkiwi\lightsystem\Frontend\Box
     */
    final protected function setIcon(?array $icon): Box
    {
        $this->icon = $icon ?? Icon::ICON_QUESTION;

        return $this;
    }

    /**
     * @return int
     */
    final public function getPositionX(): int
    {
        return $this->positionX;
    }

    /**
     * @param int $positionX
     *
     * @return \noxkiwi\lightsystem\Frontend\Box
     */
    final public function setPositionX(int $positionX): Box
    {
        $this->positionX = $positionX;

        return $this;
    }

    /**
     * @return int
     */
    final public function getPositionY(): int
    {
        return $this->positionY;
    }

    /**
     * @param int $positionY
     *
     * @return \noxkiwi\lightsystem\Frontend\Box
     */
    final public function setPositionY(int $positionY): Box
    {
        $this->positionY = $positionY;

        return $this;
    }

    /**
     * @return string
     */
    final public function output(): string
    {
        $this->prepare();
        $shownClass  = ! $this->isCollapsed() ? 'show' : '';
        $collapsible = $this->isCollapsible() ? 'collapse' : '';
        $id          = uniqid('bxo', false);

        return <<<HTML
<div id="accordion{$id}">
  <div class="card">
    <div class="card-header" id="heading{$id}" class="mb-0" data-toggle="{$collapsible}" data-target="#collapse{$id}" aria-expanded="true" aria-controls="collapse{$id}">
      {$this->title}
      {$this->buildButtons()}
    </div>
    <div id="collapse{$id}" class="collapse {$shownClass}" aria-labelledby="heading{$id}" data-parent="#accordion{$id}">
      <div>
        {$this->content}
      </div>
    </div>
  </div>
</div>
HTML;
    }

    private function prepare(): void
    {
        $this->render([]);
        if (! $this->isCollapsible()) {
            $this->setCollapsed(false);
        }
    }

    /**
     * @return bool
     */
    final public function isCollapsible(): bool
    {
        return $this->collapsible;
    }

    /**
     * @param bool|null $collapsible
     *
     * @return \noxkiwi\lightsystem\Frontend\Box
     */
    final public function setCollapsible(bool $collapsible = null): Box
    {
        $this->collapsible = $collapsible ?? true;

        return $this;
    }

    /**
     * @return bool
     */
    final public function isCollapsed(): bool
    {
        return $this->collapsed;
    }

    /**
     * @param bool|null $collapsed
     *
     * @return \noxkiwi\lightsystem\Frontend\Box
     */
    final public function setCollapsed(bool $collapsed = null): Box
    {
        $this->collapsed = $collapsed ?? true;

        return $this;
    }

    /**
     * @return string
     */
    final protected function buildButtons(): string
    {
        $r = '';
        if ($this->isCollapsible()) {
            $r .= Icon::get(Icon::ICON_MINIMIZE);
        }
        if ($this->isFullScreenable()) {
            $r .= Icon::get(Icon::ICON_MAXIMIZE);
        }
        if ($this->isDeletable()) {
            $r .= Icon::get(Icon::ICON_REMOVE);
        }

        return $r;
    }

    /**
     * @return bool
     */
    final protected function isFullScreenable(): bool
    {
        return $this->fullScreenAble;
    }

    /**
     * @param bool $fullScreenAble
     *
     * @return \noxkiwi\lightsystem\Frontend\Box
     */
    final protected function setFullScreenable(bool $fullScreenAble): Box
    {
        $this->fullScreenAble = $fullScreenAble ?? true;

        return $this;
    }

    /**
     * @return bool
     */
    final public function isDeletable(): bool
    {
        return $this->deletable;
    }

    /**
     * @param bool|null $deletable
     *
     * @return \noxkiwi\lightsystem\Frontend\Box
     */
    final public function setDeletable(bool $deletable = null): Box
    {
        $this->deletable = $deletable ?? true;

        return $this;
    }

    /**
     * @return bool
     */
    final protected function isResizable(): bool
    {
        return $this->resizable;
    }

    /**
     * @param bool|null $resizable
     *
     * @return \noxkiwi\lightsystem\Frontend\Box
     */
    final protected function setResizable(bool $resizable = null): Box
    {
        $this->resizable = $resizable ?? true;

        return $this;
    }
}
