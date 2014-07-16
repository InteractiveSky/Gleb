$(document).ready(function(){
    $('.bxslider').bxSlider({
        pager: false
    });
});
$(window).load(function(){

});

$(window).scroll(function () {

});

function datePicker(){
    $( ".input-text--date" ).datepicker({
        showOn: "both",
        minDate:0,
        buttonImage: false,
        buttonImageOnly: false
    });
}