<?php namespace Poki; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Poki - error</title>
    <link rel="stylesheet" href="<?= Files::style('starter.style') ?>">
</head>
<body>

    <form action="<?= Routes::find('configure') ?>" id="configform" method="post">
        <div class="top">
            <img src="<?= Files::image('04.png') ?>" alt="">
        </div>
        <h3>An error occured !</h3>
        <p>
            <?= $errormsg ?>
        </p>
        <div class="fields">
            <div class="field">
                <a class="btn" href="<?= Routes::find('default') ?>">Go back</a>
            </div>
        </div>
    </form>

</body>
</html>