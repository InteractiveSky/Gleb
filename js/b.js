/**
 * В этом файле собраны разные полезные js функции и небольшие jQuery плагины
 */

/**
 * "Правильный" диапазон дат для jQuery.datePicker()
 * Пример на http://sponsorburo.ru/
 * @param obj
 * @constructor
 */
function TrueDatesRange(obj){
    obj.datepicker("option", "minDate", 0);
    obj.change(function (e) {
        var t = $(this);
        var min = $('[name="' + t.attr('data-min') + '"]');
        var max = $('[name="' + t.attr('data-max') + '"]');
        var d = t.datepicker('getDate');
        if (d === null) {
            d = 0;
        }
        if (min.size() == 1) {
            min.datepicker("option", "maxDate", d);
        }
        if (max.size() == 1) {
            max.datepicker("option", "minDate", d);
        }
    })
}

function isNumber(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}

/**
 * Фораматирование числа (1523256.13 => 1 523 256,13)
 * @param n Число, которое нужно форматировать
 * @param str Разделитель
 * @returns {string}
 */
function formatNum(n, str) {
    var n = ('' + n).split('.');
    var num = n[0];
    var dec = n[1];
    var s, t;
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
/**
 * RGB код цвета переводит в хеш код
 * @param rgb
 * @returns {string}
 */
function rgb2hex(rgb) {
    function hex(x) {
        return isNaN(x) ? "00" : hexDigits[(x - x % 16) / 16] + hexDigits[x % 16];
    }
    var hexDigits = new Array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "a", "b", "c", "d", "e", "f");
    rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
    return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
}

/**
 * Тру плейсхолдер
 */
(function ($) {
    $.fn.onBlur = function () {
        var obj = $(this);
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