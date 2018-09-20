<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Poki - Install</title>
    <link rel="stylesheet" href="<?= Files::style('starter.style') ?>">
</head>
<body>

    <form action="<?= Routes::find('configure') ?>" id="configform" method="post">
        <div class="top">
            <img src="<?= Files::image('02.png') ?>" alt="">
        </div>
        <h3>WELCOME | POKI</h3>
        <p>
            Fill the following form to configure.<br>Once done click on "next" button to continue.
        </p>
        <div class="fields">
            <div class="field">
                <input type="text" placeholder="Host" name="dbhost">
            </div>
            <div class="field">
                <input type="text" placeholder="Database" name="dbname">
            </div>
            <div class="field">
                <input type="text" placeholder="Database user" name="dbuser">
            </div>
            <div class="field">
                <input type="password" placeholder="Database Password" name="dbpass">
            </div>
            <!--
            <div class="field">
                <input type="text" placeholder="Adminify folder" name="dbpass">
                <p class="text-muted">If your adminify is in subdirectory put: <b>path/to/directory</b>.If adminify is in root directory leave this empty.</p>
            </div>
            -->
            <div class="field">
                <button type="submit">Next</button>
            </div>
        </div>
    </form>

</body>
</html>