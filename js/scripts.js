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
        pager: false,
        slideWidth: 300,
        minSlides: 2,
        maxSlides: 3,
        moveSlides: 1,
        slideMargin: 10
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

            var center_coords = [55.872547, 37.521488];
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

            var map = new google.maps.Map(o, mapOptions);

            var markerIcon = new google.maps.MarkerImage('/bitrix/templates/preobr/img/logo-marker.png',
                new google.maps.Size(56, 55), // размер иконки
                // The origin for this image is 0,0.
                new google.maps.Point(0, 0),
                // The anchor for this image is the base of the flagpole at 0,32.
                new google.maps.Point(25, 25));


            var adr = $(o).attr('data-address');
            if (adr !== undefined) {
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({ 'address': adr}, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        //console.log(results);
                        //console.log(results[0].geometry.location.B);
                        //console.log(results[0].geometry.location.k);
                        map.setCenter(results[0].geometry.location);
                        var markers = [];
                        var marker = new google.maps.Marker({
                            map: map,
                            position: results[0].geometry.location,
                            animation: google.maps.Animation.DROP
                        });
                        markers.push(marker);
                        var markerBounds = new google.maps.LatLngBounds();
                        var ml = markers.length;
                        for (var i = 0; i < ml; i++) {
                            markerBounds.extend(markers[i]['position']);
                        }
                        if (results[0].geometry.location_type=='ROOFTOP' && ml==1) {
                            //console.log('rooftop');
                            map.setZoom(16);
                            map.setCenter(markerBounds.getCenter()/*, map.fitBounds(markerBounds)*/);
                        }
                        //map.setCenter(markerBounds.getCenter()/*, map.fitBounds(markerBounds)*/);
                    } else {
                        alert("Geocode was not successful for the following reason: " + status);
                    }
                });
            }
            else {
                var center_coords_attr = $(o).attr('data-coords');
                var center_coords = center_coords_attr.split(/[\s,]+/);
                map.setCenter(new google.maps.LatLng(center_coords[0], center_coords[1]));
                var object_coords_attr = $(o).attr('data-object');
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