<?php
declare(strict_types = 1);
use noxkiwi\core\Response;
?><!DOCTYPE>
<html lang="en">
<head><title>Have a ☕</title>
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <style>body {background : #2e2e2e;padding : 0;margin : 0;}

        main {position : relative;height : 100%;width : 100%;margin : 0;padding : 0;resize : both;overflow : auto;}

        main div {position : absolute;top : 50%;left : 20px;right : 20px;transform : translateY(-50%);overflow : auto;}

        * {font-family : SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;color : #AAFF00;}</style>
</head>
<body>
<main>
    <div><?= Response::getInstance()->getData('content') ?></div>
</main>
</body>
</html>
