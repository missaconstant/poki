<!DOCTYPE html>
<html>
    <head>
        <?php include INCLUDES . 'default-head.inc.php' ?>
        <link rel="stylesheet" href="<?= THEME ?>assets/plugins/summernote/summernote-bs4.css">
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
                                            <h4 class="mt-0 header-title"><?= ucfirst($category_name) ?> form</h4>
                                            <p class="text-muted m-b-30 font-14">Fill this form and then add a content.</p>

                                            <form action="<?= Routes::find('content-add') ?>" id="newcontent" name="newcontent" method="post" enctype="multipart/form-data">
                                                <?php foreach ($category_fields as $k => $field): ?>
                                                    <div class="form-group">
                                                        <label><?= $field['name'] ?></label><?= $field['type'] == 'text' ? ' - <a href="#" class="badge badge-pill badge-danger switcheditor">Switch editor</a>':'' ?>
                                                        <?= Helpers::displayHtmlField($field['type'], $field['name'], ($content ? $content[$field['name']]:false), $category_name) ?>
                                                    </div>
                                                <?php endforeach ?>

                                                <div class="form-group">
                                                    <div>
                                                        <?= Posts::getCSRF() ?>
                                                        <input type="hidden" name="editing" value="<?= Posts::get([1]) ? Posts::get(1) : '0' ?>">
                                                        <input type="hidden" name="category" value="<?= $category_name ?>">
                                                        <a href="<?= Routes::find('category-list') .'/'. $category_name ?>" class="btn btn-danger waves-effect m-l-5">Go back</a>
                                                        <button type="submit" class="btn btn-success waves-effect waves-light">Save content</button>
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
        <script src="<?= THEME ?>assets/plugins/summernote/summernote-bs4.min.js"></script>
        <script>
            $(function () {
                $('#newcontent').on('submit', function (e) {
                    e.preventDefault();
                    loader.show();
                    $form = $(this);
                    $.ajax({
                        url: $form[0].action,
                        type: 'post',
                        data: $form.serialize(),
                        dataType: 'json',
                        success: function (response) {
                            if (!response.error) {
                                alerter.success(response.message);
                                window.location.href = '<?= Routes::find("category-list") .'/'. $category_name ?>';
                            }
                            else {
                                alerter.error(response.message);
                            }
                        },
                        error: function (err) {
                            console.log(err);
                            loader.hide();
                            alerter.error('An error occured ! Please check your connexion and try again later.');
                        }
                    });
                });

                $('.summered').summernote({tabsize: 2, height: 200, focus: 1});

                $('.switcheditor').on('click', function (e) {
                    e.preventDefault();
                    $el = $(this).parent().find('.summerable');
                    if (!$el.hasClass('summered')) {
                        $el.summernote({tabsize: 2, height: 200, focus: 1});
                        $el.addClass('summered');
                    }
                    else {
                        $el.summernote('destroy');
                        $el.val('');
                        $el.removeClass('summered');
                    }
                });

                var loadingfiles = false;
                $('.adminizer-file-field').on('change', function (e) {
                    if (loadingfiles) { alerter.error("Wait until current upload finish ..."); return; }

                    var self = this;
                    var fd = new FormData();
                    /* adding file list */
                    [].forEach.call(this.files, function (file) {
                        fd.append('adm_file_upload[]', file);
                    });
                    setLoading(this);
                    /* performing ajax request */
                    loadingfiles = true; 
                    $.ajax({
                        xhr: function() {
                            var xhr = new window.XMLHttpRequest();
                            xhr.upload.addEventListener("progress", function(evt) {
                                if (evt.lengthComputable) {
                                    var percentComplete = evt.loaded / evt.total;
                                    
                                    percentComplete = parseInt(percentComplete * 100);
                                    $('.item.loading').text('Loading ' + (percentComplete)*100 + '%');

                                    if (percentComplete === 100) {
                                        console.log('completed !');
                                    }
                                }
                            }, false);
                            return xhr;
                        },
                        url: '<?= Routes::find("upload-file") ?>',
                        type: "POST",
                        data: fd,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            if (!response.error) {
                                alerter.success("Upload terminé !");
                                setUploaded(self, response.saved);
                            }
                            else {
                                alerter.error(response.message);
                            }
                            $('input[name="_token"]').val(response.newtoken);
                            removeLoading(self);
                            loadingfiles = false;
                        },
                        error: function (err) {
                            console.log(err);
                            removeLoading(self);
                            loadingfiles = false;
                        }
                    });
                });

                $('.linkchoose').select2();
            });

            function setLoading(where) {
                $(where).parent().find('.file-uploaded').append('<div class="item loader">loading ... <span class="closer">&times;</span></div>');
            }

            function removeLoading(where) {
                $(where).parent().find('.item.loader').remove();
            }

            function setUploaded(where, files) {
                var groupid = where.getAttribute('data-field');
                var $field = $(where).parent().find('#' + groupid);
                var names = [];
                [].forEach.call(files, function (file) {
                    $(where).parent().find('.file-uploaded').append('<div class="item itfile mb-1" data-fname="'+ file.savename +'"><a class="fname" href="<?= Config::$fields_files_webpath ?>'+ file.savename +'" target="_blank" id="'+ file.idfile +'">'+ file.origin +'</a> <span class="closer" onclick="removeUploaded(this, \''+ groupid +'\')">&times;</span></div>');
                    names.push(file.savename);
                });
                $field.val([$field.val(), names.join('|')].join('|'));
            }

            function removeUploaded(btn, fieldid) {
                var rest = [];
                $(btn).parent().remove();
                $('#' + fieldid).parent().find('.file-uploaded .item.itfile').each(function (index) {
                    rest.push(this.getAttribute('data-fname'));
                });
                $('#' + fieldid).val(rest.join('|'));
            }

            function bindSelectChange(field, value) {
                $('#' + field).val(value);
            }
        </script>
    </body>
</html>