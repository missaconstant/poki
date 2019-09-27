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
        var checkedcontent = [];

        $(function () {

            // checkboxes for contents
            $('.content-check-all').on('change', function () {
                var top = this;
                
                // empty previousely checked
                checkedcontent = [];

                $('.content-check-one').each(function (x, elt) {
                    elt.checked = top.checked;
                    $('#dropdownMenuBtn2')[ top.checked ? 'removeClass' : 'addClass' ]('d-none');

                    // put this in checked array if check-all is checked
                    if (top.checked) checkedcontent.push(elt.id.split('_')[1]);
                });
            });

            $('.content-check-one').on('change', function () {
                var id = this.id.split('_')[1];

                // global action dropdown button toggling
                if ($('.content-check-one:checked').length == 1) {
                    $('#dropdownMenuBtn2').removeClass('d-none');
                }
                else if ($('.content-check-one:checked').length < 1) {
                    $('#dropdownMenuBtn2').addClass('d-none');
                }

                // manage actions by state
                $('.content-check-all')[0].checked = !this.checked ? false : $('.content-check-all')[0].checked;

                if (this.checked) {
                    checkedcontent.push(id);
                }
                else {
                    checkedcontent = checkedcontent.filter(function (item) {
                        return item != id
                    });
                }
            });
        });

        function toggleContent(btn, categoryname, fromselection) {
            var id = null;
            var st = null;

            if (fromselection) {
                id = checkedcontent.join('-');
                st = fromselection == 'enable' ? 0 : 1;
            }
            else {
                id = $(btn).parent().parent()[0].id.substring(8);
                st = $(btn).parent().parent()[0].getAttribute('data-state');
            }

            postize('<?= Routes::find('content-toggle') ?>/' + id + '/' + categoryname + '/' + st, 'get', false, function (response) {
                if (fromselection) {
                    checkedcontent.forEach(function (item) {
                        $('#customCheck')[0].checked            = false;
                        $('#customCheck_' + item)[0].checked    = false;

                        $('#dropdownMenuBtn2').addClass('d-none');
                        $('#content_' + item)[0].setAttribute('data-state', response.newstate);
                        $('#content_' + item).find('.toggle-btn i.mdi')[0].className = 'mdi mdi-eye' + (response.newstate ? '':'-off');
                    });

                    checkedcontent = fromselection ? [] : checkedcontent;
                }
                else {
                    $(btn).parent().parent()[0].setAttribute('data-state', response.newstate);
                    $(btn).find('i')[0].className = 'mdi mdi-eye' + (response.newstate ? '' : '-off');
                }

                alerter.success('Content(s) correctely '+ (response.newstate ? 'disabled' : 'enabled') +' !');
            },
            function (err) {
                alerter.error(err.message);
            });
        }

        function deleteContent(btn, categoryname, fromselection) {
            var id =  null;

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
                            id = fromselection ? checkedcontent.join('-') : $(btn).parent().parent()[0].id.substring(8);

                            postize('<?= Routes::find('content-delete') ?>/' + id + '/' + categoryname, 'get', false, function (response) {
                                checkedcontent = fromselection ? checkedcontent : [ id ];

                                checkedcontent.forEach(function (item) {
                                    $('#content_' + item).parent().remove();
                                });

                                checkedcontent = fromselection ? [] : checkedcontent;
                                
                                alerter.success("Content(s) correctely deleted !");
                            },
                            function (err) {
                                alerter.error(err.message);
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
            postize('<?= Routes::find('content-get-csv') ?>/' + categoryname, 'get', false, function (response) {
                if (!$('#linkdowncsv').length) {
                    var el = document.createElement('a');
                        el.href = response.message;
                        el.id = 'linkdowncsv';
                        el.setAttribute('download', '');
                        el.style.display = 'none';
                    $(document.body).append(el);
                }
                $('#linkdowncsv')[0].click();
            },
            function (err) {
                alerter.error(err.message);
            });
        }
    </script>
</html>