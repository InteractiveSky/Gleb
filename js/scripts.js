$(document).ready(function () {
    $('.bxslider').bxSlider({
        pager: false
    });
});
$(window).load(function () {

});
$(window).scroll(function () {

});
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