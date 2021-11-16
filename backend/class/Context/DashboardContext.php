<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Context;

use noxkiwi\core\Config\JsonConfig;
use noxkiwi\core\Constants\Mvc;
use noxkiwi\core\Context;
use noxkiwi\core\Helper\DateTimeHelper;
use noxkiwi\core\Helper\FrontendHelper;
use noxkiwi\core\Helper\JsonHelper;
use noxkiwi\lightsystem\Frontend\Screen\OpcitemScreen;
use noxkiwi\database\Database;

/**
 * I am
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class DashboardContext extends Context
{
    /**
     */
    protected function viewDashboard(): void
    {
        if ($this->request->isDefined('deployment')) {
            $this->deployUpgrade();
        }
    }

    protected function viewSettlement(): void
    {
        $db = Database::getInstance();
        $result = $db->read('SELECT * FROM vSettlement;');
        var_dump($db->getResult());
        die();
    }

    /**
     * I will upgrade the version number.
     */
    private function deployUpgrade(): void
    {
        $versionInfo = new JsonConfig('/home/phpkiwi/_conf/nox.kiwi/version.json');
        [$major, $minor, $build] = explode('.', $versionInfo->get('version', '0.0.1'));
        $build++;
        if ($this->request->get('reason') === 'major') {
            $major++;
        }
        if ($this->request->get('reason') === 'patch') {
            $minor++;
        }
        $versionInfo = [
            'version'    => implode('.', [$major, $minor, $build]),
            'lastUpdate' => DateTimeHelper::iso()
        ];
        file_put_contents('/home/phpkiwi/_conf/nox.kiwi/version.json', JsonHelper::encode($versionInfo));
    }

    /**
     * I will
     * @throws \ReflectionException
     */
    protected function viewScreen(): void
    {
        $this->addScripts();
        $this->response->set('screen', (new OpcitemScreen($this->request->get()))->output());
    }

    /**
     * I will
     */
    private function addScripts(): void
    {
        $this->response->set('runtimeData', $this->getStartRuntime());
        FrontendHelper::addResource('js', 'Log');
        FrontendHelper::addResource('js', 'Loader');
        FrontendHelper::addResource('js', 'ConnectionFailure');
        FrontendHelper::addResource('js', 'Translate');
        FrontendHelper::addResource('js', 'Feedback');
        FrontendHelper::addResource('js', 'Core');
        FrontendHelper::addResource('js', 'LightSystem');
        FrontendHelper::addResource('js', 'Plugin');
        FrontendHelper::addResource('js', 'Control');
        FrontendHelper::addResource('js', 'ActivityMonitorControl');
        FrontendHelper::addResource('js', 'AlarmConfigControl');
        FrontendHelper::addResource('js', 'BaseClient');
        FrontendHelper::addResource('js', 'AlarmClient');
        FrontendHelper::addResource('js', 'EventClient');
        FrontendHelper::addResource('js', 'ContextMenu');
        FrontendHelper::addResource('js', 'ValueControl');
        FrontendHelper::addResource('js', 'CountvalueControl');
        FrontendHelper::addResource('js', 'TableControl');
        FrontendHelper::addResource('js', 'AlarmControl');
        FrontendHelper::addResource('js', 'EventMonitorControl');
        FrontendHelper::addResource('js', 'DataClient');
        FrontendHelper::addResource('js', 'DataMonitorControl');
        FrontendHelper::addResource('js', 'ChartControl');
        FrontendHelper::addResource('js', 'MaintenanceControl');
        FrontendHelper::addResource('js', 'ManualValueControl');
        FrontendHelper::addResource('js', 'ReadingControl');
        FrontendHelper::addResource('js', 'SetpointControl');
        FrontendHelper::addResource('js', 'TimeSwitchControl');
        FrontendHelper::addResource('js', 'DateHelper');
        FrontendHelper::addResource('js', 'Lib');
        FrontendHelper::addResource('js', 'Hook');
        FrontendHelper::addResource('js', 'TagManager');
        FrontendHelper::addResource('js', 'FrontendManager');
        FrontendHelper::addResource('js', 'ItemManager');
        FrontendHelper::addResource('js', 'HighchartsExtender');
        FrontendHelper::addResource('js', 'Panel');
        FrontendHelper::addResource('js', 'PanelManager');
        FrontendHelper::addResource('js', 'Animate');
        FrontendHelper::addResource('js', 'BargraphAnimation');
        // BASE COLORS AND SETUP
        FrontendHelper::addResource('css', 'Default');
        // BOOTSTRAP
        FrontendHelper::addResource('css', 'bootstrap');
        // OTHER STUFF
        // BOOTSTRAP
        FrontendHelper::addResource('css', 'Process');
        FrontendHelper::addResource('css', 'Main');
        FrontendHelper::addResource('css', 'Panels');
        FrontendHelper::addResource('css', 'AlarmControl');
        FrontendHelper::addResource('css', 'Loader');
        FrontendHelper::addResource('css', 'ConnectionFailure');
        FrontendHelper::addResource('css', 'TimeSwitch');
        FrontendHelper::addResource('css', 'Physics');
    }

    /**
     * @return array
     */
    private function getStartRuntime(): array
    {
        return [
            'settings'      => [
                'updateInterval' => (int)$this->request->get('updateInterval', 1) * 250,
                'debugmode'      => (int)$this->request->get('debugmode', 0) === 1
            ],
            'defaults'      => [
                'firstpanel'     => (int)$this->request->get('firstpanel', 1003),
                'firstpaneldata' => $this->request->get(
                    'firstpaneldata',
                    [
                        'tag' => ''
                    ]
                )
            ],
            'controls'      => ['a' => null],
            'visualization' => [
                'tagList'   => [],
                'tagTree'   => [],
                'panels'    => [],
                'panelList' => [],
                'blink'     => [
                    'objects' => []
                ],
                'objects'   => []
            ],
            'Intervals'     => [],
            'instances'     => [],
            'threads'       => [
                'update' => [
                    'initialized' => false
                ]
            ]
        ];
    }

    /**
     */
    protected function viewShow(): void
    {
        $this->response->set(Mvc::TEMPLATE, 'lightsystem');
        $this->addScripts();
    }
}
