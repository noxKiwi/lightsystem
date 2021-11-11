<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem;

use noxkiwi\core\Response;

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>noxkiwi\crud List</title>

    <!-- JQ -->
    <script type="text/javascript" src="/asset/lib/jquery/jquery.min.js"></script>

    <!-- BOOTSTRAP -->
    <link rel="stylesheet" type="text/css" media="screen" href="/asset/lib/bootstrap/css/bootstrap.min.css"/>
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
</head>
<body>
<?= Response::getInstance()->get('content') ?>
</body>
</html>
