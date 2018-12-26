$(function () {
    $('#addcategorymodal').on('hide.bs.modal', function () {
        $(this).find('#newcategorymodalform input[name="name"]').val('');
        $(this).find('#newcategorymodalform input[name="editing"]').val('0');
        $('#addfieldmodal .modal-title').text('Add new category');
        $('.deletecategorybtn').hide();
    });

    autocomplete.init($('.app-search .form-control')[0], {parent: $('.content-page')[0], top: 70});
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

var autocomplete = {
    box: null,
    parent: null,
    activeItem: false,

    setBox: function (options) {
        autocomplete.box = document.createElement('div');
        autocomplete.box.id = 'autocomplete-box';
        autocomplete.box.style.position = 'absolute';
        autocomplete.box.style.top = (options.parent.offsetTop + options.top) + 'px';
        autocomplete.box.style.left = options.parent.offSetLeft + 'px';
        autocomplete.box.style.width = '100%';
        autocomplete.box.style.minHeight = '100px';
        autocomplete.box.style.zIndex = '1';
        autocomplete.box.style.background = '#fff';

        this.box = autocomplete.box;
        this.parent = options.parent;
    },

    init: function (elt, options) {
        if (!autocomplete.box) {
            this.setBox(options);
        }

        document.querySelector('.pk-search').addEventListener('submit', function (e) {
            e.preventDefault();
            console.log('submitted !');
        });

        elt.addEventListener('keyup', function (e) {
            if (this.value.length) {
                /* Focus on keyboard up and down */
                if (e.which == 38 || e.which == 40) {
                    var focused = autocomplete.activeItem;
                    var items = autocomplete.box.querySelectorAll('.pk-item');
                    if (e.which == 38) {
                        if (!focused) {
                            autocomplete.activeItem = items[items.length-1];
                            focused = autocomplete.activeItem;
                            focused.classList.add('focused');
                        }
                        else {
                            var prev = focused.parentNode.previousSibling;
                                prev =  prev && prev.nodeName.toLowerCase() == 'a' ? prev : prev ? prev.previousSibling : false;
                            if (prev && prev.nodeName.toLowerCase() == 'a') {
                                autocomplete.activeItem.classList.remove('focused');
                                autocomplete.activeItem = prev.querySelector('.pk-item');
                                focused = autocomplete.activeItem;
                                focused.classList.add('focused');
                            }
                            else {
                                autocomplete.activeItem.classList.remove('focused');
                                autocomplete.activeItem = false;
                            }
                        }
                    }
                    else {
                        if (!focused) {
                            autocomplete.activeItem = items[0];
                            focused = autocomplete.activeItem;
                            focused.classList.add('focused');
                        }
                        else {
                            var next = focused.parentNode.nextSibling;
                                next =  next && next.nodeName.toLowerCase() == 'a' ? next : next ? next.nextSibling : false;
                            if (next && next.nodeName.toLowerCase() == 'a') {
                                autocomplete.activeItem.classList.remove('focused');
                                autocomplete.activeItem = next.querySelector('.pk-item');
                                focused = autocomplete.activeItem;
                                focused.classList.add('focused');
                            }
                            else {
                                autocomplete.activeItem.classList.remove('focused');
                                autocomplete.activeItem = false;
                            }
                        }
                    }
                    return;
                }
                else if (e.which == 27) {
                    autocomplete.hideBox();
                }
                /* do search on keyword */
                autocomplete.doSeach(this.value);
            }
            else {
                autocomplete.hideBox();
            }
        });
        elt.addEventListener('blur', function (e) {
            if (!e.relatedTarget || e.relatedTarget.className != 'pk-item-link') {
                autocomplete.hideBox();
            }
        });
        elt.addEventListener('focus', function () {
            if (this.value.length){
                autocomplete.doSeach(this.value);
            }
        });
    },

    doSeach(keyword) {
        var datas = [];
        $.get({
            url: '/categories/search-count-key-in-all-categories/' + encodeURIComponent(keyword),
            dataType: 'json',
            success: function (datas) {
                var list = '';
                for (var i=0; i<datas.length; i++) {
                    var categoryname = datas[i].category;
                    var number = datas[i].list.length;
                    list += autocomplete.getTemplate({category: categoryname, number: number, keyword: keyword});
                }
                autocomplete.box.innerHTML = list;
                autocomplete.showBox();
            },
            error: function (err) {
                console.log(err);
            }
        });
    },

    showBox: function () {
        this.parent.appendChild(autocomplete.box);
    },

    hideBox: function () {
        if (this.box.parentNode == this.parent) {
            this.parent.removeChild(this.box);
            this.activeItem = false;
        }
    },

    getTemplate: function (item) {
        return '<a href="/categories/list-contents/'+ item.category +'/1/'+ item.keyword +'" class="pk-item-link">'+
                    '<div class="pk-item">' +
                        '<div class="pk-left">'+
                            '<img src="http://via.placeholder.com/50x50" alt="">'+
                        '</div>'+
                        '<div class="pk-right">'+
                            '<b class="numbers">'+ item.number +' results found</b>'+
                            '<span>From category <b>'+ item.category +'</b></span>'+
                        '</div>'+
                    '</div>'+
                '</a>';
    }
};