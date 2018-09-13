var alerter = {
    inited: false,
    box: null,

    init: function () {
        alerter.box = document.createElement('div');
        alerter.left = document.createElement('div');
        alerter.right = document.createElement('div');
        alerter.text = document.createElement('div');
        alerter.icon = document.createElement('i');

        $(alerter.box).css({'position': 'fixed', 'bottom': '30px', 'right': '-400px', 'padding': '10px', 'box-sizing': 'border-box', 'width': '300px', 'min-height': '100px', 'background':'#fff', 'z-index': '100000', 'border-radius': '10px', 'overflow': 'auto', 'box-shadow': '1px 0px 20px rgba(0, 0, 0, 0.07)', 'transition': 'all 0.2s ease'});
        $(alerter.left).css({float: 'left', width: '70px', height: '70px', borderRadius: '35px', background: '#de876a', 'margin-top': '5px', 'line-height': '60px', 'text-align': 'center'});
        $(alerter.right).css({position: 'relative', marginLeft: '90px', top: '40px', transform: 'translateY(-50%)'});
        $(alerter.text).css({fontSize: '13px', color: '#555', wordWrap: 'break-word'});
        $(alerter.icon).css({fontSize: '3em', color: '#fff', 'line-height': '70px'}).addClass('mdi');
        $(document.body).append(alerter.box);
        $(alerter.box).append(alerter.left);
        $(alerter.left).append(alerter.icon);
        $(alerter.box).append(alerter.right);
        $(alerter.right).append(alerter.text);

        alerter.inited = true;
    },

    alert: function (type, message, timeout) {
        if (!alerter.inited) alerter.init();
        $(alerter.text).html(message);
        $(alerter.left).css({background: type=='success' ? '#3ad886':'#d84b4b'});
        $(alerter.icon).removeClass(type=='success' ? 'mdi-close':'mdi-check').addClass(type=='success' ? 'mdi-check':'mdi-close');
        $(alerter.box).css({right: '30px'});
        setTimeout(function () {
            $(alerter.box).css({right: '-400px'});
        }, timeout || 3000);
    },

    success: function (message, timeout) {
        alerter.alert('success', message, timeout || false);
    },

    error: function (message, timeout) {
        alerter.alert('error', message, timeout || false);
    }
};

alerter.init();

var loader = {
    inited: false,

    init: function () {
        $('#fakeload').fakeLoader({
            bgColor:"rgba(30,30,30,0.5)",
            spinner:"spinner7",
            zIndex: 10000
        });
        loader.inited = true;
    },

    show: function () {
        if (this.inited)
            $('#fakeload').fadeIn();
        else
            loader.init();
    },

    hide: function () {
        $('#fakeload').fadeOut();
    }
};