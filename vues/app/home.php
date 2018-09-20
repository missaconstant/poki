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

                        <div class="container-fluid" id="home">

                            <?php include INCLUDES . 'page-title.inc.php' ?>

                            <div class="row">
                                <div class="col-12 text-center">
                                    <img src="<?= Files::image('logo.png') ?>" alt="" id="logoimg">
                                    <h4 class="text-primary" id="logotext1">Welcome here !</h4>
                                    <p class="text-muted" id="logotext2">
                                        Welcome on Poki which is an admin app came to you to help you in apps administration and more other stuffs. Poki is very simple to use following few steps. It's an Open Source project under MIT licence.
                                    </p>
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