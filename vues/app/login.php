<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Adminify - Login</title>
    <link rel="stylesheet" href="<?= THEME ?>assets/css/icons.css">
    <link rel="stylesheet" href="<?= Files::style('starter.style') ?>">
</head>
<body>

    <form action="<?= Routes::find('login-act') ?>" id="configform" method="post">
        <div class="top">
            <i class="mdi mdi-lock"></i>
        </div>
        <h3><?= langexp('en','w1') ?> | ADMINIZER</h3>
        <p>
            <?= langexp('en','w2') ?><br><?= langexp('en', 'w3') ?>
        </p>
        <div class="fields">
            <div class="field">
                <input type="text" placeholder="<?= langexp('en', 'w4') ?>" name="user">
            </div>
            <div class="field">
                <input type="password" placeholder="<?= langexp('en', 'w5') ?>" name="pass">
            </div>
            <div class="field">
                <?= Posts::getCSRF() ?>
                <button type="submit"><?= langexp('en', 'w6') ?></button>
            </div>
        </div>
    </form>

    <script src="<?= THEME ?>assets/js/jquery.min.js"></script>
    <script src="<?= Files::script('alerter.plugin') ?>"></script>
    <script>
        $(function () {
            var trending = false;
            $('#configform').on('submit', function (e) {
                e.preventDefault();
                var form = this;
                if (trending) return;
                trending = true;
                $.ajax({
                    url: form.action,
                    type: 'post',
                    data: $(form).serialize(),
                    dataType: 'json',
                    success: function (response) {
                        if (!response.error) {
                            window.location.href = '<?= Routes::find('dashboard') ?>';
                        }
                        else {
                            alerter.error(response.message);
                            $('input[name="_token"]').val(response.newtoken);
                        }
                        trending = false;
                    },
                    error: function (err) {
                        alerter.error("<?= langexp('en', 'w7') ?>");
                        trending = false;
                    }
                });
            });
        });
    </script>
</body>
</html>