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
                                <div class="col-12">
                                    <div class="card m-b-30">
                                        <div class="card-body">
                                            <h4 class="mt-0 header-title">Account form</h4>
                                            <p class="text-muted m-b-30 font-14">Edit your informations and connexion credentials.</p>

                                            <form action="<?= Routes::find('users-add-act') ?>" id="accountform" method="post">
                                                <div class="form-group">
                                                    <label>Name</label>
                                                    <input type="text" class="form-control" name="name" placeholder="User name" value="<?= Helpers::eie($admin,'name') ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="text" class="form-control" name="email" placeholder="User email" value="<?= Helpers::eie($admin,'email') ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label>Password</label>
                                                    <input type="password" class="form-control" name="pass" placeholder="User password" value="" autocomplete="off">
                                                </div>
                                                <div class="form-group">
                                                    <div>
                                                        <?= Posts::getCSRF() ?>
                                                        <input type="hidden" name="editing" value="<?= Session::get('admin') ?>">
                                                        <a onclick="window.history.back()" href="#" class="btn btn-danger waves-effect m-l-5">Go back</a>
                                                        <button type="submit" class="btn btn-success waves-effect waves-light">Save user</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
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
        <script>
            $(function () {
                $('#accountform').on('submit', function (e) {
                    e.preventDefault();
                    postize(this.action, 'post', $(this).serialize(), function (response) {
                        alerter.success("Updates done !");
                    },
                    function (err) {
                        alerter.error(err.message);
                    });
                });
            });
        </script>
    </body>
</html>