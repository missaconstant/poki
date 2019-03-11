$(function () {
    
    $('.toggle-plugin').on('click', function (e) {
        e.preventDefault();
        loader.show();

        postize('/plugins/toggle/' + this.parentNode.id, 'get', false, function (datas) {
            alerter.success(datas.message);
            setTimeout(function () { window.location.href = '/listener/list' }, 1500);
        },
        function (err) {
            alerter.error(err.message);
            loader.hide();
        }, true);
    });

    $('.delete-plugin').on('click', function (e) {
        e.preventDefault();

        var self = this;

        warningAction(function () {
            postize('/plugins/delete/' + self.parentNode.id, 'get', false, function (datas) {
                alerter.success(datas.message);
                setTimeout(function () { window.location.href = '/listener/list' }, 1500);
            },
            function (err) {
                alerter.error(err.message);
                loader.hide();
            }, true);
        });
    });

});