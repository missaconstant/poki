<?php namespace Poki; ?>

<!DOCTYPE html>
<html>
    <head>
        <?php include INCLUDES . 'default-head.inc.php' ?>
        <link rel="stylesheet" href="<?= THEME . 'assets/plugins/summernote/summernote-bs4.css' ?>">
        <link rel="stylesheet" href="<?= THEME . 'assets/plugins/datatables/dataTables.bootstrap4.min.css' ?>">
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

                            <!-- <div class="row"> -->
                                
                                <?php include $view ?>

                                <br>
                                <br>
                                
                            <!-- </div> -->

                        </div><!-- container -->

                    </div> <!-- Page content Wrapper -->

                </div> <!-- content -->

                <?php include INCLUDES . 'footer.inc.php' ?>

            </div>
            <!-- End Right content here -->

        </div>
        <!-- END wrapper -->

        <?php include INCLUDES . 'default-script.inc.php' ?>
        <script src="<?= THEME . 'assets/plugins/summernote/summernote-bs4.min.js' ?>"></script>
        <script src="<?= THEME . 'assets/plugins/datatables/jquery.dataTables.min.js' ?>"></script>
        <script src="<?= THEME . 'assets/plugins/datatables/dataTables.bootstrap4.min.js' ?>"></script>
        <script>
            $(function () {
                $('.summernote').summernote({
                    height: 200,
                    minHeight: null,
                    minWidth: null
                });

                $('.pk-datatable').DataTable();
            });
        </script>
    </body>
</html>