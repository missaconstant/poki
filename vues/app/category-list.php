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
                                <?php include INCLUDES . 'contents-list.inc.php' ?>
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

    <script>
        $(function () {
            
        });

        function deleteContent(btn, categoryname) {
            var id = $(btn).parent().parent()[0].id.substring(8);
            $.confirm({
                title: 'Are you sure ?',
                content: 'You are deleting this element.<br>You could not come back after this action.',
                type: 'red',
                theme: 'modern',
                icon: 'fa fa-warning',
                buttons: {
                    delete: {
                        btnClass: 'btn btn-danger',
                        action: function () {
                            loader.show();
                            $.ajax({
                                url: '<?= Routes::find('content-delete') ?>/' + id + '/' + categoryname,
                                type: 'get',
                                datatype: 'json',
                                success: function (response) {
                                    if (!response.error) {
                                        loader.hide();
                                        alerter.success("Content deleted !");
                                        $('#content_' + id).parent().fadeOut();
                                    }
                                    else {
                                        loader.hide();
                                        alerter.error(response.message);
                                    }
                                },
                                error: function (err) {
                                    alerter.error("An error occured ! Check your connexion and try again later.");
                                    loader.hide();
                                }
                            });
                        }
                    },
                    Cancel: {}
                }
            });
        }

        function addContentFromCsv(fileinput) {
            var file = fileinput.files[0];

            if (!/^[\s\S]+.csv$/.test(file.name)) {
                alerter.error("Please choose a CSV file");
            }
            else {
                $.confirm({
                    title: 'Are you sure ?',
                    content: 'You are going to add this file elements to this category ...',
                    type: 'red',
                    theme: 'modern',
                    icon: 'fa fa-warning',
                    buttons: {
                        confirm: {
                            btnClass: 'btn btn-danger',
                            action: function () {
                                loader.show();
                                var fd = new FormData();
                                    fd.append('csvfile', file);
                                    fd.append('categoryname', '<?= $category_name ?>');
                                $.ajax({
                                    url: '<?= Routes::find('content-from-csv') ?>',
                                    type: 'post',
                                    contentType: false,
                                    processData: false,
                                    cache: false,
                                    data: fd,
                                    dataType: 'json',
                                    success: function (response) {
                                        if (!response.error) {
                                            alerter.success(response.message);
                                            window.location.reload();
                                        }
                                        else {
                                            alerter.error(response.message);
                                        }
                                        loader.hide();
                                    },
                                    error: function (err) {
                                        alerter.error("An error occured ! Please try again later.");
                                        loader.hide();
                                    }
                                });
                            },
                        },
                        cancel: {}
                    }
                });
            }
        }

        function getCSV(categoryname)
        {
            loader.show();
            $.ajax({
                url: '<?= Routes::find('content-get-csv') ?>/' + categoryname,
                type: 'get',
                dataType: 'json',
                success: function (response) {
                    if (!response.error) {
                        if (!$('#linkdowncsv').length) {
                            var el = document.createElement('a');
                                el.href = response.message;
                                el.id = 'linkdowncsv';
                                el.setAttribute('download', '');
                                el.style.display = 'none';
                            $(document.body).append(el);
                        }
                        $('#linkdowncsv')[0].click();
                    }
                    else {
                        alerter.error("An error occured ! Please try again later.");
                    }
                    loader.hide();
                },
                error: function (err) {
                    alerter.error("An error occured ! Please try again later.");
                    loader.hide();
                }
            });
        }
    </script>
</html>