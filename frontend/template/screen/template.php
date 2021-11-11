<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem;

use noxkiwi\core\Helper\FrontendHelper;
use noxkiwi\core\Response;

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Hello, world!</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
          crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" media="screen" href="asset/lib/datatables/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" media="screen" href="asset/lib/jqwidgets/jqwidgets/styles/jqx.base.css"/>
    <link rel="stylesheet" type="text/css" media="screen" href="asset/lib/jqwidgets/jqwidgets/styles/jqx.light.css"/>
    <link rel="stylesheet" type="text/css" media="screen" href="asset/lib/fontawesome/css/all.min.css"/>
    <!-- JS -->
    <script type="text/javascript" src="asset/lib/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="asset/lib/bootstrap/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="asset/lib/fontawesome/js/all.min.js"></script>
    <script type="text/javascript" src="asset/lib/datatables/datatables.min.js"></script>
    <script type="text/javascript" src="asset/lib/highcharts/code/highcharts.js"></script>
    <script type="text/javascript" src="asset/lib/jqwidgets/jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="asset/lib/jqwidgets/jqwidgets/jqxwindow.js"></script>
    <script type="text/javascript" src="asset/lib/jqwidgets/jqwidgets/jqxnotification.js"></script>
    <script type="text/javascript" src="asset/lib/snapsvg/snap.svg.min.js"></script>
    <script type="text/javascript" src="asset/src/app.js"></script>
    <?php
    FrontendHelper::addResource('js', 'Log');
    FrontendHelper::addResource('js', 'Loader');
    FrontendHelper::addResource('js', 'ConnectionFailure');
    FrontendHelper::addResource('js', 'Core');
    FrontendHelper::addResource('js', 'Translate');
    FrontendHelper::addResource('js', 'Feedback');
    ?>
</head>
<body>
<?= Response::getInstance()->getData('content') ?>
<?= Response::getInstance()->getData('screen') ?>
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
</body>
</html>
