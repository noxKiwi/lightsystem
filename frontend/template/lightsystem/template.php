<?php declare(strict_types=1);

namespace noxkiwi\lightsystem;

use noxkiwi\core\Constants\Mvc;
use noxkiwi\core\Exception\InvalidArgumentException;
use noxkiwi\frontend\Element\Icon;
use noxkiwi\core\Helper\FrontendHelper;
use noxkiwi\core\Helper\JsonHelper;
use noxkiwi\core\Helper\LinkHelper;
use noxkiwi\core\Response;
use noxkiwi\lightsystem\Model\RenderPanelModel;
use noxkiwi\lightsystem\Model\RenderPanelPrefixModel;
use noxkiwi\translator\Translator;
use noxkiwi\core\Environment;use const E_ERROR;

$crudLinks = '';
$models = [
        'Slave',
        'Master',
        'Flow',
        'Account',
        'AlarmGroup',
        'AlarmItem',
        'Animation',
        'ArchiveGroup',
        'ArchiveItem',
        'OpcItem',
        'OpcNode',
        'OpcServer',
        'OpcTemplate',
        'Attribute',
        'RenderElement',
        'RenderPanel',
        'RenderSvgTag',
        'RenderPanelPrefix',
        'TimeSwitch',
        'User',
        'Animation',
];

$navi = [

];

foreach($models as $model) {
    $link = LinkHelper::get([Mvc::CONTEXT => 'crudfrontend', 'modelName' => $model, Mvc::VIEW => 'list']);
    $crudLinks .= <<<HTML
<a class="dropdown-item" href="$link">$model</a>
HTML;
}

$panelModel = RenderPanelModel::getInstance();
$panelModel->addOrder('render_panel_name');
$panelModel->search();
$panels = $panelModel->getResult();
$panelLinks = '';
foreach($panels as $panel)
{
    $panelPrefixModel = RenderPanelPrefixModel::getInstance();
    $panelPrefixModel->addFilter('render_panel_id', $panel['render_panel_id']);
    $panelPrefixModel->addOrder('render_panel_id');
    $panelPrefixModel->addOrder('render_panel_prefix_name');
    $panelPrefixModel->search();
    $prefixes = $panelPrefixModel->getResult();
    if (empty($prefixes)) {
        continue;
    }
    foreach($prefixes as $prefix) {
        $panelLinks .= <<<HTML
<a onclick="PanelManager.showPanel({$panel['render_panel_id']}, {tag:'{$prefix['render_panel_prefix_prefix']}'});" class="dropdown-item" href="#">{$prefix['render_panel_prefix_name']}</a>
HTML;

    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <title>DCS@<?= Environment::getInstance()->get('name', 'unnamed') ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="viewport" content="width=device-width, initial-scale=1 maximum-scale=1 minimum-scale=1"/>

    <!-- JQ -->
    <script type="text/javascript" src="/asset/lib/jquery/jquery.min.js"></script>

    <!-- BOOTSTRAP -->
    <link rel="stylesheet" type="text/css" href="/asset/lib/bootstrap/css/bootstrap-night.css" >
    <script type="text/javascript" src="/asset/lib/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- DATATABLES -->
    <link rel="stylesheet" type="text/css" media="screen" href="/asset/lib/datatables/css/datatables.min.css"/>
    <script type="text/javascript" src="/asset/lib/datatables/js/datatables.min.js"></script>
    <script type="text/javascript" src="/asset/lib/datatables.buttons/js/buttons.colVis.min.js"></script>
    <script type="text/javascript" src="/asset/lib/datatables.buttons/js/buttons.bootstrap5.min.js"></script>

    <!-- FONTAWESOME -->
    <link rel="stylesheet" type="text/css" media="screen" href="/asset/lib/fontawesome/css/all.min.css"/>
    <script type="text/javascript" src="/asset/lib/fontawesome/js/all.min.js"></script>

    <!-- SELECTIZE -->
    <script type="text/javascript" src="/asset/lib/selectize/examples/js/jqueryui.js"></script>
    <script type="text/javascript" src="/asset/lib/selectize/dist/js/standalone/selectize.js"></script>
    <script type="text/javascript" src="/asset/lib/selectize/examples/js/index.js"></script>
    <!-- JQX -->
    <link rel="stylesheet" type="text/css" media="screen" href="/asset/lib/jqwidgets/jqwidgets/styles/jqx.base.css"/>
    <link rel="stylesheet" type="text/css" media="screen" href="/asset/lib/jqwidgets/jqwidgets/styles/jqx.light.css"/>
    <script type="text/javascript" src="/asset/lib/jqwidgets/jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="/asset/lib/jqwidgets/jqwidgets/jqxwindow.js"></script>
    <script type="text/javascript" src="/asset/lib/jqwidgets/jqwidgets/jqxnotification.js"></script>
    <script type="text/javascript" src="/asset/lib/snapsvg/snap.svg.min.js"></script>

    <!-- HIGHCHARTS -->
    <script src="/lib/highcharts/code/highcharts.js"></script>
    <script src="/lib/highcharts/code/modules/data.js"></script>
    <script src="/lib/highcharts/code/modules/series-label.js"></script>
    <script src="/lib/highcharts/code/modules/exporting.js"></script>
    <script src="/lib/highcharts/code/modules/export-data.js"></script>
    <script src="/lib/highcharts/code/modules/accessibility.js"></script>
    <?php echo FrontendHelper::getResourceList() ?>
</head>
<body>
<!-- TOP NAV BAR -->

<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <a class="navbar-brand" href="#"><?= Environment::getInstance()->get('name', 'unnamed') ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
            aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto">
            <li class="alarmIconEngaged nav-item d-none"><a class="nav-link btn"
                                    onclick="PanelManager.showPanel('control', {control: 'AlarmControl'})"
                                    title="<?=Translator::get('MAIN.ALARMS')?>">
                    <?= Icon::get(Icon::ICON_ALARM_ENGAGED) ?>
                    <span
                            class="alarmCountBadge badge badge-danger"></span></a></li>
            <li class="alarmIconDisengaged nav-item"><a class="nav-link btn"
                                    onclick="PanelManager.showPanel('control', {control: 'AlarmControl'})"
                                    title="<?=Translator::get('MAIN.ALARMS')?>">
                    <?= Icon::get(Icon::ICON_ALARM_DISENGAGED) ?>
                    <span
                            class="alarmCountBadge badge badge-danger"></span></a></li>
            <li class="nav-item"><a class="nav-link btn"
                                    onclick="PanelManager.showPanel('control', {control: 'DataMonitorControl'})"
                                    title="<?=Translator::get('MAIN.DATA') ?>"><?= Icon::get(Icon::ICON_TABLE) ?></a>
            </li>
            <li class="nav-item"><a class="nav-link btn"
                                    onclick="PanelManager.showPanel('control', {control: 'EventMonitorControl'})"
                                    title="<?=Translator::get('MAIN.EVENTS') ?>"><?= Icon::get(Icon::ICON_EVENTS) ?></a>
            </li>
            <li class="nav-item"><a class="nav-link btn"
                                    onclick="PanelManager.showPanel('control', {control: 'LogBookControl'})"
                                    title="<?=Translator::get('MAIN.LOGBOOK') ?>"><?= Icon::get(Icon::ICON_BOOK) ?></a>
            </li>
            <li class="nav-item"><a class="nav-link btn"
                                    onclick="PanelManager.showPanel('control', {control: 'MaintenanceControl'})"
                                    title="<?=Translator::get('MAIN.MAINTENANCE') ?>"><?= Icon::get(Icon::ICON_WRENCH) ?></a>
            </li>
            <li class="nav-item"><a class="nav-link btn"
                                    onclick="PanelManager.showPanel('control', {control: 'LogControl'})"
                                    title="<?=Translator::get('MAIN.LOGGING') ?>"><?= Icon::get(Icon::ICON_FILE_ARCHIVE) ?></a>
            </li>
            <li class="nav-item"><a class="nav-link btn"
                                    onclick="PanelManager.showPanel('control', {control: 'TimeSwitchControl'})"
                                    title="<?=Translator::get('MAIN.TIMESWITCH') ?>"><?= Icon::get(Icon::ICON_CLOCK) ?></a>
            </li>
            <li class="nav-item"><a class="nav-link btn"
                                    title="<?=Translator::get('MAIN.CHARTS') ?>"
                                    onclick="PanelManager.showPanel('control', {control: 'ChartControl'});"><?= Icon::get(Icon::ICON_CHART_LINE) ?></a>
            </li>
            <li class="nav-item"><a class="nav-link btn"
                                    title="<?=Translator::get('MAIN.SETTLEMENT') ?>"
                                    href="<?= LinkHelper::get(['context' => 'dashboard', 'view' => 'settlement'])?>"><?= Icon::get(Icon::ICON_MONEY) ?></a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <?= Icon::get(Icon::ICON_IMAGE) ?>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <?=$panelLinks?>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <?= Icon::get(Icon::ICON_COG) ?>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <?=$crudLinks?>
                </div>
            </li>
        </ul>
      <form class="d-flex">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
          <li class="nav-item">
              <a class="nav-link btn" href="<?= LinkHelper::get([Mvc::CONTEXT => 'login', Mvc::VIEW => 'logout']) ?>"
                 title="<?=Translator::get('MAIN.LOGOFF')?>">
                  <?= Icon::get(Icon::ICON_LOGOUT) ?>
              </a>
          </li>
      </form>
    </div>
</nav>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
    <section id="rsLMain">
        <div id="fbSuccess">
            <div id="fbSuccessMessage"></div>
        </div>
        <div id="fbError">
            <div id="fbErrorMessage"></div>
        </div>
        <div id="fbInfo">
            <div id="fbInfoMessage"></div>
        </div>
    </section>
</main>
<script type="text/javascript">
    var runtime = <?= JsonHelper::encode(
        Response::getInstance()->get('runtimeData', [])
    ) ?>;
    ls = new lightsystem();
</script>
</body>
</html>
