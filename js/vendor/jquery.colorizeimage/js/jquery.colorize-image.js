(function ($) {
    $.fn.ColorizeImage = function () {
        var obj = $(this);
        var selector = obj.selector;
        var n_class = selector.substring(1);
        var wrapper = $('<div class="' + n_class + '"></div>').css({position: 'relative'});
        obj.each(function (i, o) {
            var img = $(o);
            var colored = img.attr('data-colored');
            if (colored !== undefined) {
                var col_img = $('<img alt="" src="' + colored + '" />').css({
                    display: 'block',
                    position: 'absolute',
                    zIndex: 2,
                    top:0,
                    left:0
                });
                col_img.addClass(n_class + '__colored').hide();
                img.addClass(n_class + '__uncolored').removeClass(n_class).wrap(wrapper).after(col_img);
            }
        });
        $('body').on('mouseenter', selector + '__uncolored', function () {
            var cl = $(this).closest(selector);
            var colored = cl.find(selector + '__colored');
            //console.log(colored);
            colored.stop(true, true).fadeIn(300);
        });
        $('body').on('mouseleave', selector + '__colored', function () {
            $(this).stop(true, true).fadeOut(300);
        });
    };
})(jQuery);