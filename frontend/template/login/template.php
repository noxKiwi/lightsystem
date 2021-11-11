<?php declare(strict_types = 1);

namespace noxkiwi\lightsystem;

use noxkiwi\core\Environment;
use noxkiwi\core\Helper\LinkHelper;
use noxkiwi\translator\Translator;

/** @var \noxkiwi\core\Response $data */
$title = Translator::get('MAIN.LOGIN', ['name' => Environment::getInstance()->get('name', 'unnamed')]);
?><!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $title ?></title>

    <!-- JQ -->
    <script type="text/javascript" src="/asset/lib/jquery/jquery.min.js"></script>

    <!-- BOOTSTRAP -->
    <link rel="stylesheet" type="text/css" href="/asset/lib/bootstrap/css/bootstrap-night.css" >
    <script type="text/javascript" src="/asset/lib/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- FONTAWESOME -->
    <link rel="stylesheet" type="text/css" media="screen" href="/asset/lib/fontawesome/css/all.min.css"/>
    <script type="text/javascript" src="/asset/lib/fontawesome/js/all.min.js"></script>
    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="/custom.css">
    <style>
        body {background : var(--MainBackground);}
    </style>
</head>
<body>

<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title" id="loginModalLabel"><?= Translator::get('LOGIN') ?></span>
            </div>
            <div class="modal-body">
                <div class="blankpage-form-field pull-right">
                    <form method="post" action="<?= LinkHelper::makeUrl() ?>">

                        <div class="input-group input-group-sm mb-3">
                            <span class="input-group-text" id="basic-addon1"><i class="fal fa-user"></i></span>
                            <input type="text" id="username" name="username" class="form-control" placeholder="<?= Translator::get('USERNAME') ?>"  aria-describedby="inputGroup-sizing-sm" />
                        </div>
                        <div class="input-group input-group-sm mb-3">
                            <span class="input-group-text" id="basic-addon1"><i class="fal fa-key-skeleton"></i></span>
                            <input type="password" id="password" name="password" class="form-control" placeholder="<?= Translator::get('PASSWORD') ?>">
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary float-end"><?= Translator::get('BTN.LOGIN') ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
    
	    var myModal = new bootstrap.Modal($("#loginModal"), {
		show     : true,
		backdrop : "static",
		keyboard : false
	    });
	    myModal.show();
            $("#username").focus();
    });
</script>
</body>
</html>
