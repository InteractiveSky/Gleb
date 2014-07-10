$(document).ready(function () {
    $('ul li:first-child, table tr td:first-child, table tr:first-child, .tbl .td:first-child').addClass('first');
    $('ul li:last-child, table tr td:last-child, table tr:last-child, .tbl .td:last-child').addClass('last');
    $('body').on('submit', '.fancybox-inner form', function(e){
        e.preventDefault();
        submitAjaxForm(e, $(this));
    })
    $('.g-justify').append('<li style="width:100%;display:inline-block;" class="g-justify__empty-item"></li>');
});

function isNumber(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}

function formatNum(n, str) {
    var n = ('' + n).split('.');
    var num = n[0];
    var dec = n[1];
    var r, s, t;
    if (num.length > 3) {
        s = num.length % 3;
        if (s) {
            t = num.substring(0, s);
            num = t + num.substring(s).replace(/(\d{3})/g, str + "$1");
        } else {
            num = num.substring(s).replace(/(\d{3})/g, str + "$1").substring(1);
        }
    }
    if (dec && dec.length > 3) {
        dec = dec.replace(/(\d{3})/g, "$1 ");
    }
    return num + (dec ? '.' + dec : '');
}

function submitAjaxForm(e, f){
    e.preventDefault();
    var d = f.serializeArray();
    var fc = f.attr('class');
    var fa = f.attr('action');
    $.post(fa, d, function(r){


        var af = $('<div>'+r+'</div>').find('form').filter('[class="'+fc+'"][action="'+fa+'"]');

        //console.log(af);
        af.find('input[placeholder]').onBlur();
        f.parent().html(af);
        var strFun = f.attr('data-func');
        var fn = window[strFun];
        if (fn!==undefined) {
            fn(r);
        }
        //console.log(af);
    })
}

(function ($) {
    $.fn.onBlur = function () {
        //console.log('onBlur');

        var obj = $(this);
        //console.log(obj);

        $(obj).each(function (i, o) {
            $(o).addClass('placeholder');
            if ($(o).is('[type="password"]')){
                $(o).attr('data-type', 'password');
                $(o).prop('type', 'text');
            }
            if ($(o).val()=='') {
                $(o).val($(o).attr('placeholder'));
                $(this).addClass('placeholder');
            }
            $(o).attr('data-placeholder', $(o).attr('placeholder'));
            $(o).removeAttr('placeholder');

        });

        $(obj).blur(function () {
            if ($(this).val() == "") {
                $(this).val($(this).attr("data-placeholder"));
                $(this).addClass('placeholder');
                if ($(this).is('[type="password"]')){
                    $(this).attr('data-type', 'password');
                    $(this).prop('type', 'text');
                }
            }

        });

        $(obj).focus(function () {
            if ($(this).is('[data-type="password"]')) {
                $(this).prop('type', 'password');
            }
            if ($(this).val() == $(this).attr("data-placeholder")) {
                $(this).val("");
                $(this).removeClass('placeholder');
            }
        })

    };
})(jQuery);