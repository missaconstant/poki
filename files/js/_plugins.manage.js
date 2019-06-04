$(function () {
    
    $('.toggle-plugin').on('click', function (e) {
        e.preventDefault();
        loader.show();

        postize(baseroute + '/plugins/toggle/' + this.parentNode.id, 'get', false, function (datas) {
            alerter.success(datas.message);
            setTimeout(function () { window.location.href = '/listener/list' }, 1500);
        },
        function (err) {
            alerter.error(err.message);
            loader.hide();
        }, true);
    });

    $('.delete-plugin, .update-plugin').on('click', function (e) {
        e.preventDefault();

        var self = this, action = this.classList.contains('delete-plugin') ? 'delete' : 'update';

        warningAction(function () {
            postize(baseroute + '/plugins/'+ action +'/' + self.parentNode.id, 'get', false, function (datas) {
                alerter.success(datas.message);
                setTimeout(function () { window.location.href = '/listener/list' }, 1500);
            },
            function (err) {
                alerter.error(err.message);
                loader.hide();
            }, true);
        });
    });

    $('.toggle-install-form').on('click', function () { $('.install-fields').slideToggle() });

    $('.installbtn').on('click', function () {
        var files = document.getElementById('pluginfile').files;
        
        if (!files.length) {
            alerter.error("You have to choose a file !");
        }
        else if (!/zip/.test(files[0].type.toLowerCase())) {
            alerter.error("Zip file required !");
        }
        else {
            var fd = new FormData();
                fd.append("plugin", files[0]);
                fd.append('_token', $('input[name="_token"]').val());
            
            loader.show();

            $.ajax({
                url: baseroute + '/plugins/add',
                method: 'post',
                contentType: false,
                processData: false,
                cache: false,
                data: fd,
                dataType: 'json',
                success: function (datas) {
                    if (datas.error) {
                        $('input[name="_token"]').val(datas.newtoken);

                        alerter.error(datas.message);
                        loader.hide();
                    }
                    else {
                        alerter.success(datas.message);

                        setTimeout(function () { window.location.reload() }, 1500);
                    }
                },
                error: function (err) {
                    alerter.error("An error just occured !");
                    loader.hide();
                }
            });
        }
    });

    $('.generatebtn').on('click', function () {
        $('#plugin_generate_modal').modal('show');
    });

    $('#generation_form').on('submit', function() {
        loader.show();

        postize( baseroute + '/plugins/generate', 'post', $(this).serialize(), function (response) {
            loader.hide();
            alerter.success(response.message);

            $('#plugin_generate_modal').modal('hide');
            $('#generation_form')[0].reset();

            var a = document.createElement('a');
                a.href = response.link;
                a.click();
        },
        function (err) {
            alerter.error(err.message);
        } );
    });

});