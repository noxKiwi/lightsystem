<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem;

use JetBrains\PhpStorm\NoReturn;
use noxkiwi\core\Environment;
use noxkiwi\core\Gate\CidrGate;
use noxkiwi\core\Gate\HostnameGate;
use noxkiwi\core\Gate\IpGate;
use noxkiwi\core\Helper\FrontendHelper;
use noxkiwi\core\Helper\LinkHelper;
use noxkiwi\core\Path;
use noxkiwi\lightsystem\Frontend\Control;
use noxkiwi\lightsystem\Frontend\Control\AlarmControl;
use noxkiwi\lightsystem\Frontend\Control\DataMonitorControl;
use noxkiwi\lightsystem\Frontend\Control\ChartControl;
use noxkiwi\lightsystem\Frontend\Control\EventMonitorControl;
use noxkiwi\lightsystem\Frontend\Control\TimeSwitchControl;
use function var_dump;

/**
 * I am the App of the lightsystem.
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class App extends \noxkiwi\core\App
{
    /**
     * Just some security stuff.
     */
    protected function __construct()
    {
        parent::__construct();
        $env                      = Environment::getInstance();
        LinkHelper::$encryptLinks = (bool)$env->get('url>encrypt', false);
        LinkHelper::$secret       = (string)$env->get('url>secret', '');
    }

    /**
     * @inheritDoc
     */
    #[NoReturn] public function run(): void
    {
        if (! $this->checkGates()) {
            echo FrontendHelper::parseFile(Path::getInheritedPath(Path::PAGE_403));

            return;
        }
        $this->addControls();
        parent::run();
    }

    /**
     * I will perform basic security checks.
     * @throws \noxkiwi\singleton\Exception\SingletonException
     * @return bool
     */
    private function checkGates(): bool
    {
        parent::checkMaintenance();
        $env = Environment::getInstance();
        // Prepare CIDR
        $cidr = CidrGate::getInstance();
        $cidr->setRanges($env->get('gate>CidrGate', []));
        // Prepare Hostname
        $hostname = HostnameGate::getInstance();
        $hostname->setHostNames($env->get('gate>HostnameGate', []));
        // IP Gate
        $ipgate = IpGate::getInstance();
        $ipgate->setAllowedHosts($env->get('gate>IpGate', []));

        // Check
        return $cidr->isOpen() && $hostname->isOpen() && $ipgate->isOpen();
    }

    /**
     * I will solely add the Controls to the environment.
     */
    private function addControls(): void
    {
        // @formatter:on
        Control::addControl(new AlarmControl());
        Control::addControl(new ChartControl());
        Control::addControl(new TimeSwitchControl());
        Control::addControl(new EventMonitorControl());
        Control::addControl(new DataMonitorControl());
    }
}
