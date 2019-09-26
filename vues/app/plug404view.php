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

                            <div class="row">
                                <div class="col-12 text-center" style="margin-top: 120px;">
                                    <h1 style="font-size:7em; color:#999;"><i class="mdi mdi-alert-outline"></i></h1>
                                    <h2 style="color:#999; margin-top: 30px;">[ Page not found ]<br/>Sorry ! We can't find what you're asking for.</h2>
                                </div>
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
    </body>
</html>