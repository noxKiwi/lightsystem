<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Frontend\Box;

use noxkiwi\core\Helper\FrontendHelper;
use noxkiwi\core\Path;
use noxkiwi\dataabstraction\Entry;
use noxkiwi\lightsystem\Frontend\Box;
use noxkiwi\lightsystem\Frontend\Screen;
use noxkiwi\lightsystem\Model\OpcItemModel;

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
final class SetvalueBox extends Box
{
    /** @var \noxkiwi\dataabstraction\Entry */
    private Entry $opcitem;

    /**
     * @param array $data
     */
    public function render(array $data): void
    {
        $file    = Path::FRONTEND_DIR . '/box/setvalue.php';
        $path    = Path::getInheritedPath($file);
        $content = FrontendHelper::parseFile($path, $data);
        $this->setContent($content);
    }

    /**
     * @param array|null $options
     *
     * @throws \ReflectionException
     * @throws \noxkiwi\dataabstraction\Exception\EntryInconsistentException
     * @throws \noxkiwi\dataabstraction\Exception\EntryMissingException
     * @throws \noxkiwi\dataabstraction\Exception\ModelException
     */
    protected function setOptions(?array $options): void
    {
        parent::setOptions($options);
        if (! empty($options['itemId'])) {
            $opcItem = OpcItemModel::getInstance()->loadEntry($options['itemId']);
            if ($opcItem !== null) {
                $this->opcitem = $opcItem;
            }
        }
        $this->setPositionX(Screen::COLUMN_RIGHT);
    }
}
