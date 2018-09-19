$(function () {
    $('#addcategorymodal').on('hide.bs.modal', function () {
        $(this).find('#newcategorymodalform input[name="name"]').val('');
        $(this).find('#newcategorymodalform input[name="editing"]').val('0');
        $('#addfieldmodal .modal-title').text('Add new category');
        $('.deletecategorybtn').hide();
    });
});

function saveCategoryField(path) {
    loader.show();
    var $form = $('#newcategorymodalform');
    var edition = $('#newcategorymodalform #editingcategory').val();
        edition = edition != '0' ? edition : 0;
    $.ajax({
        url: $form[0].action,
        type: 'post',
        data: $form.serialize(),
        dataType: 'json',
        success: function (response) {
            if (!response.error) {
                $('#addcategorymodal').modal('hide');
                alerter.success('Category <b>'+ response.name +'</b> '+(edition!=0 ? 'modified':'added')+' !');
                window.location.href = path + '/' + response.name;
            }
            else {
                loader.hide();
                alerter.error(response.message);
                $('input[name="_token"]').val(response.newtoken);
            }
        },
        error: function (err) {
            console.log(err);
            alerter.error('An error occured ! Check your connexion and try again later.');
            loader.hide();
        }
    });
}

function postize(url, type, datas, success, error, letloader) {
    loader.show();
    $.ajax({
        url: url,
        type: type || 'get',
        data: datas,
        dataType: 'json',
        success: function (response) {
            if (!letloader) loader.hide();
            if (response.error) {
                if (error) error(response);
            }
            else {
                if (success) success(response);
            }
            if (response.newtoken) $('input[name="_token"]').val(response.newtoken);
        },
        error: function (err) {
            if (error) error({message: "An error occured ! Check your connexion and try again later.", err: err});
            if (!letloader) loader.hide();
        }
    });
}

function warningAction(doaction) {
    $.confirm({
        title: 'Are you sure ?',
        content: 'Do you really want to continue ?<br>You could not come back after this action.',
        type: 'red',
        theme: 'modern',
        icon: 'fa fa-warning',
        buttons: {
            continue: {
                btnClass: 'btn btn-danger',
                action: function () {
                    doaction();
                }
            },
            cancel: {}
        }
    });
}