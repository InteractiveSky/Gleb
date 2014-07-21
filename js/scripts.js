$(document).ready(function () {

});
$(window).load(function () {

});
$(window).scroll(function () {

});
function timepicker() {
    $.timepicker.regional['ru'] = {
        timeOnlyTitle: 'Выберите время',
        timeText: 'Время',
        hourText: 'Часы',
        minuteText: 'Минуты',
        secondText: 'Секунды',
        millisecText: 'Миллисекунды',
        timezoneText: 'Часовой пояс',
        currentText: 'Сейчас',
        closeText: 'Ok',
        timeFormat: 'HH:mm',
        amNames: ['AM', 'A'],
        pmNames: ['PM', 'P'],
        isRTL: false
    };
    $.timepicker.setDefaults($.timepicker.regional['ru']);
    var s = 'input[data-type="time"]';
    var input_time = $(s);
    input_time.after('<button type="button" class="ui-datepicker-trigger ui-datepicker-trigger--time">Выбрать время</button>');
    $('body').on('click', '.ui-datepicker-trigger--time', function (e) {
        e.preventDefault();
        $(this).prev().focus();
    });
    input_time.timepicker({stepMinute: 5});
}
function bxSlider() {
    $('.bxslider').bxSlider({
        pager: false
    });
}
function ui_tabs() {
    $(".tabs").tabs();
}
function datePicker() {
    $(".input-text--date").datepicker({
        showOn: "both",
        minDate: 0, // возможность выбрать начиная с текущей даты
        buttonImage: false,
        buttonImageOnly: false,
        showButtonPanel: true,
        buttonText: "Выбрать дату"
    });
}
function google_maps() {
    var map_div = $('[data-map="google"]');
    if (map_div.size() > 0) {
        map_div.each(function (i, o) {
            var center_coords_attr = $(o).attr('data-coords');
            var center_coords = center_coords_attr.split(/[\s,]+/);
            var mapOptions = {
                center: new google.maps.LatLng(center_coords[0], center_coords[1]),
                zoom: parseInt($(o).attr('data-zoom')),
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                disableDefaultUI: true,
                panControl: false,
                streetViewControl: true,
                zoomControl: true,
                scaleControl: false,
                mapTypeControl: false,
                scrollwheel: false
            };
            var markerIcon = new google.maps.MarkerImage('/bitrix/templates/preobr/img/logo-marker.png',
                new google.maps.Size(56, 55), // размер иконки
                // The origin for this image is 0,0.
                new google.maps.Point(0, 0),
                // The anchor for this image is the base of the flagpole at 0,32.
                new google.maps.Point(25, 25));
            var map = new google.maps.Map(o, mapOptions);
            var object_coords_attr = $(o).attr('data-object');
            if (object_coords_attr !== undefined) {
                var object_coords = object_coords_attr.split(/[\s,]+/);
                var objectCoords = new google.maps.LatLng(object_coords[0], object_coords[1]);
                var objectMarker = new google.maps.Marker({
                    position: objectCoords,
                    map: map,
                    //icon: markerIcon,
                    animation: google.maps.Animation.DROP
                });
            }
        });
    }
}