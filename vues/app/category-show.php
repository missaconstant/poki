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
                                <div class="col-12">
                                    <?php include INCLUDES . 'category-top-manage.inc.php' ?>
                                    <?php include INCLUDES . 'category-fields.inc.php' ?>
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
        <?php include INCLUDES . 'modal-add-field.inc.php' ?>
        <?php include INCLUDES . 'modal-api-manage.inc.php' ?>

        <?php include INCLUDES . 'default-script.inc.php' ?>
        <script>
            var $editline = null;

            $(function () {
                $('#addfieldmodal').on('hide.bs.modal', function () {
                    $('#newfieldmodalform #editingfield').val('0');
                    $('#newfieldmodalform input[name="fieldname"]').val('');
                    $('#addfieldmodal .modal-title').text('Add new field');
                    $('#newfieldmodalform .fieldline:not(.mainline)').remove();
                    $('#newfieldmodalform .form-group').removeClass('col-6').addClass('col-12');
                    $('.more-field-btn').fadeIn();
                });
                $('.api-level-choose').select2();
                $('.link-choose').select2();
                $('.link-choose').on('change', function (e) {
                    var linkto = this.value, field = this.name, category = $('#currentcategoryname').val();
                    
                    if (!linkto.length) return;

                    loader.show();
                    postize('<?= Routes::find("categories-link-act") ?>/' + category + '/' + field + '/' + linkto, 'get', null, function (response) {
                        alerter.success(response.message);
                    },
                    function (err) {
                        alerter.error(err.message);
                    });
                });
            });

            function saveNewField() {
                loader.show();
                $form = $('#newfieldmodalform');
                var edition = $('#newfieldmodalform #editingfield').val();
                    edition = edition != '0' ? edition : 0;
                $.ajax({
                    url: $form[0].action + (edition!=0 ? '/edit-field' : '/add-field'),
                    type: 'post',
                    data: $form.serialize(),
                    dataType: 'json',
                    success: function (response) {
                        if (!response.error) {
                            $('#addfieldmodal').modal('hide');
                            alerter.success('Field(s) correctly '+(edition!=0 ? 'modified':'added')+' !');
                            if (!edition) addNewFields(response.addedfields);
                            else editField(response.addedfields[0].name, response.addedfields[0].type);
                            $('#newfieldmodalform')[0].reset();
                        }
                        else {
                            alerter.error(response.message);
                        }
                        loader.hide();
                        $('input[name="_token"]').val(response.newtoken);
                    },
                    error: function (err) {
                        loader.hide();
                        alerter.error('An error occured ! Check your connexion and try again please.');
                        console.log(err);
                    }
                });
            }

            function addNewFields(fields) {
                var vars = {"varchar": "info", "int": "danger", "text": "success", "char": "primary", "date": "warning", "tinytext": "default"};
                var types = {"varchar": "Alphanumeric", "int": "Numeric", "text": "Text field", "char": "File field", "date": "Date field", "tinytext": "Multiple field"};

                for (var field in fields) {
                    var line = '<tr id="field_'+ ($('.category-fields-table tbody tr').length+1) +'">' +
                        '<td>'+ ($('.category-fields-table tbody tr').length+1) +'</td>' +
                        '<td class="field_name">'+ fields[field].name +'</td>' +
                        '<td class="field_type"><span class="p-2 bagde badge-pill badge-'+ vars[fields[field].type] +'">'+ types[fields[field].type] +'</span></td>' +
                        '<td>Reload page to link</td>' +
                        '<td>Unknown</td>' +
                        '<td style="white-space: nowrap; width: 15%;">' +
                            '<div class="tabledit-toolbar btn-toolbar" style="text-align: left;">' +
                                '<div class="btn-group btn-group-sm" style="float: none;">' +
                                    '<button type="button" onclick="openFieldEditor(\'<?= $category_name ?>\', this)" class="btn btn-sm btn-info" style="float: none; margin: 5px;"><span class="ti-pencil"></span></button>' +
                                    '<button type="button" class="btn btn-sm btn-warning" style="float: none; margin: 5px;"><span class="ti-eye"></span></button>' +
                                    '<button type="button" onclick="removeField(\''+ fields[field].name +'\', \'<?= $category_name ?>\', this)" class="btn btn-sm btn-danger" style="float: none; margin: 5px;"><span class="ti-trash"></span></button>' +
                                '</div>' +
                            '</div>' +
                        '</td>' +
                    '</tr>';
                    $('.category-fields-table tbody').append(line);
                }

                $('.category-fields-table').fadeIn();
                $('.category-top-manage').fadeIn();
            }

            function addModalFieldLine() {
                nblines = $('#newfieldmodalform .fieldline').length;
                $mainline = $('#newfieldmodalform .mainline');
                $newline = null;
                if (nblines >= 1) {
                    $mainline.find('.form-group').removeClass('col-12').addClass('col-6');
                }
                $newline = $($mainline[0].cloneNode(true));
                $newline.removeClass('mainline').find('.field-name').val('');
                $newline.find('.field-name').attr({name: 'fieldname_' + nblines});
                $newline.find('.field-type').attr({name: 'fieldtype_' + nblines});
                $('#newfieldmodalform').append($newline);
            }

            function editField(name, type) {
                var vars = {"varchar": "info", "int": "danger", "text": "success", "char": "primary", "date": "warning", "tinytext": "default"};
                var types = {"varchar": "Alphanumeric", "int": "Numeric", "text": "Text field", "char": "File field", "date": "Date field", "tinytext": "Multiple field"};
                $editline.find('.field_name').text(name);
                $editline.find('.field_type').html('<span class="p-2 bagde badge-pill badge-'+ vars[type] +'">'+ types[type] +'</span>');
            }

            function removeField(name, category, btn) {
                var id = $(btn).parent().parent().parent().parent()[0].id;
                $.confirm({
                    title: 'Are you sure ?',
                    content: 'You are deleting this element.<br>You could not come back after this action.',
                    type: 'red',
                    theme: 'modern',
                    icon: 'fa fa-warning',
                    buttons: {
                        delete: {
                            btnClass: 'btn-danger',
                            action: function () {
                                loader.show();
                                $.ajax({
                                    url: '<?= Routes::find('category-field-delete') ?>' + '/' + category + '/' + name,
                                    type: 'get',
                                    dataType: 'json',
                                    success: function (response) {
                                        if (!response.error) {
                                            alerter.success("Field <b>"+ name +"</b> Deleted");
                                            $('#' + id).fadeOut();
                                        }
                                        else {
                                            alerter.error(response.message);
                                        }
                                        loader.hide();
                                    },
                                    error: function (err) {
                                        alerter.error('An error occured ! Check your connexion and try again.');
                                        loader.hide();
                                    }
                                });
                            }
                        },
                        close: {}
                    }
                });
            }

            function openFieldEditor(category, btn) {
                $parent = $(btn).parent().parent().parent().parent();
                $oldname = $parent.find('.field_name').text();
                $oldtype = $parent.find('.field_type span').text();
                $editline = $parent;

                $('#newfieldmodalform input[name="fieldname"]').val($oldname);
                $('#newfieldmodalform #editingfield').val($oldname);

                var selectoption = $('#newfieldmodalform select[name="fieldtype"]')[0].childNodes;
                for (var a in selectoption) {
                    if (selectoption[a].nodeName == 'OPTION') {
                        if (selectoption[a].textContent.trim() == $oldtype) {
                            selectoption[a].selected = true;
                        }
                        else {
                            selectoption[a].selected = false;
                        }
                    }
                }
                
                $('.more-field-btn').hide();
                $('#addfieldmodal .modal-title').text('Edit field');
                $('#addfieldmodal').modal('show');
            }

            function openCategoryEditor(category) {
                var oldname = category;
                $('#newcategorymodalform input[name="name"]').val(oldname);
                $('#newcategorymodalform #oldcategoryname').val(oldname);
                $('#newcategorymodalform #editingcategory').val(oldname);
                $('#addcategorymodal .modal-title').text('Edit category');
                $('.deletecategorybtn').show();
                $('#addcategorymodal').modal('show');
            }

            function deleteCategory(name) {
                $.confirm({
                    title: 'Are you sure ?',
                    content: 'You are deleting this category.<br>You could not come back after this action.',
                    type: 'red',
                    theme: 'modern',
                    icon: 'fa fa-warning',
                    buttons: {
                        delete: {
                            btnClass: 'btn-danger',
                            action: function () {
                                loader.show();
                                $.ajax({
                                    url: '<?= Routes::find("categories-delete-act") ?>/' + name,
                                    type: 'get',
                                    dataType: 'json',
                                    success: function (response) {
                                        if (!response.error) {
                                            alerter.success("Category "+ name +" deleted !");
                                            window.location.href = '<?= Routes::find("dashboard") ?>';
                                        }
                                        else {
                                            alerter.error(response.message);
                                            loader.hide();
                                        }
                                    },
                                    error: function () {
                                        alerter.error();
                                        loader.hide();
                                    }
                                });
                            }
                        },
                        close: {}
                    }
                });
            }

            function openApiManager() {
                $('#apimodal').modal('show');
            }

            function toggleApi(categoryname, btn) {
                loader.show();
                var value = $(btn).hasClass('text-success') ? '0':'1';
                $(btn).blur();
                $.ajax({
                    url: '<?= Routes::find('api-toggle') ?>/' + categoryname + '/' + value,
                    type: 'get',
                    dataType: 'json',
                    success: function (response) {
                        if (!response.error) {
                            $(btn).removeClass(value=='0' ? 'text-success':'text-danger');
                            $(btn).addClass(value=='1' ? 'text-success':'text-danger');
                            alerter.success(value=='0' ? 'Api disabled !' : 'Api enabled !');
                        }
                        else {
                            alerter.error(response.message);
                        }
                        loader.hide();
                    },
                    error: function (err) {
                        loader.hide();
                        alerter.error('An error occured ! Check your connexion and try again.');
                    }
                });
            }

            function copyApiKey(btn) {
                var apikey = $(btn).parent().parent().find('#apikeyvalue')[0];
                apikey.removeAttribute('disabled');
                apikey.select();
                document.execCommand('copy');
                apikey.setAttribute('disabled','');
                alerter.success('<b>Api key</b> copied to clipboard !');
            }

            function changeApiKey(btn, unset) {
                var apikey = $(btn).parent().parent().find('#apikeyvalue');
                var categoryname = $('#categoryname').val();
                var changemessage = 'You are changing api key for this category.<br><strong>If you continue, apps that use actual api key can no longer access to undefined level.</strong>';
                var unsetmessage = 'You are deleting api key for this category.<br>This mean <strong>no more access to undefined level.</strong>';
                $(btn).blur();
                $.confirm({
                    title: 'Are you sure ?',
                    content: unset ? unsetmessage : changemessage,
                    type: 'red',
                    theme: 'modern',
                    icon: 'fa fa-warning',
                    buttons: {
                        delete: {
                            btnClass: 'btn-danger',
                            action: function () {
                                loader.show();
                                $.ajax({
                                    url: '<?= Routes::find("api-change-key") ?>/' + categoryname + (unset ? '/unset':''),
                                    type: 'get',
                                    dataType: 'json',
                                    success: function (response) {
                                        if (!response.error) {
                                            $('#apikeyvalue').val(response.newapikey);
                                            alerter.success('Api key changed !');
                                        }
                                        else {
                                            alerter.error(response.message);
                                        }
                                        loader.hide();
                                    },
                                    error: function (err) {
                                        loader.hide();
                                        alerter.error('An error occured ! Check your connexion and try again.');
                                    }
                                });
                            }
                        },
                        cancel: {}
                    }
                });
            }

            function saveApi(categoryname, btn) {
                loader.show();
                $form = $('#apimodalform');
                $.ajax({
                    url: $form[0].action,
                    method: 'post',            
                    data: $form.serialize(),
                    dataType: 'json',
                    success: function (response) {
                        if (!response.error) {
                            alerter.success("Api succefully update !");
                            $('#apimodal').modal('hide');
                        }
                        else {
                            alerter.error(response.error);
                        }
                        loader.hide();
                        $('input[name="_token"]').val(response.newtoken);
                    },
                    error: function (err) {
                        loader.hide();
                        alerter.error('An error occured ! Check your connexion and try again.');
                    }
                });
            }
        </script>
    </body>
</html>