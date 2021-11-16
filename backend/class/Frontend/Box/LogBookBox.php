<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Frontend\Box;

use noxkiwi\core\Helper\FrontendHelper;
use noxkiwi\core\Path;
use noxkiwi\lightsystem\Frontend\Box;

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
final class LogBookBox extends Box
{
    /**
     * @param array $data
     */
    public function render(array $data): void
    {
        $file    = Path::FRONTEND_DIR . '/box/logbook.php';
        $path    = Path::getInheritedPath($file);
        $content = FrontendHelper::parseFile($path, $data);
        $this->setContent($content);
    }
}
