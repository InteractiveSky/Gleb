$(document).ready(function(){
    $("ul.tabs").tabs(".panes .panes__item", {
        current:'tabs__title--current'
    });
    $('.bxslider').bxSlider({
        pager: false
    });
    //fixedNav();
})
$(window).load(function(){

})

$(window).scroll(function () {
    //fixedNav();
});

function fixedNav(){

}