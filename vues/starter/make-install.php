<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Adminizer - Install</title>
    <link rel="stylesheet" href="<?= Files::style('starter.style') ?>">
</head>
<body>

    <form action="<?= Routes::find('configure') ?>" id="configform" method="post">
        <div class="top">
            <img src="<?= Files::image('02.png') ?>" alt="">
        </div>
        <h3>Last step and then install</h3>
        <p>
            Confirm your login and password for Adminizer root user and then proceed to installation
        </p>
        <div class="fields">
            <div class="field">
                <input type="text" placeholder="Username : default is adminizer" name="username">
            </div>
            <div class="field">
                <input type="" placeholder="Your Password : default is adminizer" name="password">
            </div>
            <div class="field">
                <button type="submit">Install</button>
            </div>
        </div>
    </form>

</body>
</html>