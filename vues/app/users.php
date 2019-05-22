<?php namespace Poki; ?>

<!DOCTYPE html>
<html>
    <head>
        <?php include INCLUDES . 'default-head.inc.php' ?>
    </head>


    <body class="fixed-left">

        <?php include INCLUDES . 'preloader.inc.php' ?>

        <!-- Begin page -->
        <div id="wrapper">

            <?php include INCLUDES . 'left-menu.inc.php' ?>

            <!-- Start right Content here -->

            <div class="content-page">
                <!-- Start content -->
                <div class="content">

                    <?php include INCLUDES . 'topbar.inc.php' ?>

                    <div class="page-content-wrapper ">

                        <div class="container-fluid">

                            <?php include INCLUDES . 'page-title.inc.php' ?>

                            <div class="row">
                                <?php include INCLUDES . 'users-list.inc.php' ?>
                            </div>

                        </div><!-- container -->

                    </div> <!-- Page content Wrapper -->

                </div> <!-- content -->

                <?php include INCLUDES . 'footer.inc.php' ?>

            </div>
            <!-- End Right content here -->

        </div>
        <!-- END wrapper -->

        <?php include INCLUDES . 'default-script.inc.php' ?>
        <script>
            $(function () {
                $('.activetoggle').on('change', function (e) {
                    var id = this.id.split('-')[1];
                    var val = e.target.value;
                    postize('<?= Routes::find('users-toggle-active') ?>/' + id + '/' + val, 'get', null, function (response) {
                        alerter.success(val=='1' ? 'User is now active !' : 'User is inactive now !');
                    },
                    function (err) {
                        alerter.error(err.message);
                    });
                });

                $('.deluser').on('click', function (e) {
                    e.preventDefault();
                    var id = this.getAttribute('data-rm');
                    var self = this;
                    warningAction(function () {
                        postize('<?= Routes::find('users-delete-act') ?>/' + id, 'get', null, function (response) {
                            alerter.success("User correctly removed !");
                            $(self).parent().parent().fadeOut();
                        },
                        function (err) {
                            alerter.error(err.message);
                        });
                    });
                });
            });
        </script>
    </body>
</html>