<?php namespace Poki; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Poki - Install done !</title>
    <link rel="stylesheet" href="<?= Files::style('starter.style') ?>">
</head>
<body>

    <form action="<?= Routes::find('configure') ?>" id="configform" method="post">
        <div class="top">
            <img src="<?= Files::image('03.png') ?>" alt="">
        </div>
        <h3>Install done !</h3>
        <p>
            Your Poki installation is now ended and correctly done ! You can now log into your admin dashboard and start Poki ! 
        </p>
        <div class="fields">
            <div class="field">
                <a class="btn" href="<?= Routes::find('default') ?>">Go to login</a>
            </div>
        </div>
    </form>

</body>
</html>