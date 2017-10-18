var carouselItems = 0;
var screenWidth = $('body').width();
var width = screenWidth;
var initialWidth = 0;
var totalItems = 3;

$('.carousel-item').each(function (){
    $(this).css('width', screenWidth + "px");
    carouselItems = carouselItems + 1;
    width = width * carouselItems;
    initialWidth = (width/2)*(-1);
    $('#carousel_wrapper').css('width', width + "px");
    $('#carousel_wrapper').css('marginLeft',initialWidth + 'px');
    var clone = $(this).clone();
    $('#carousel_wrapper').append(clone);
});

function nextPage(){
    var marginLeft = parseInt($('#carousel_wrapper').css('marginLeft'));
    var newMargin = marginLeft - (screenWidth);
    $('#carousel_wrapper').animate({ 'marginLeft': newMargin + "px" }, 1500, function () {
        var first = $('#carousel_wrapper div.carousel-item:first');
        var newElement = first;
        first.remove();
        $('#carousel_wrapper').append(newElement);
        $('#carousel_wrapper').css('marginLeft',initialWidth);
    });
}

function prevPage(){
    var marginLeft = parseInt($('#carousel_wrapper').css('marginLeft'));
    var newMargin = marginLeft + (screenWidth);
    $('#carousel_wrapper').animate({ 'marginLeft': newMargin + "px" }, 1500, function () {
        var last = $('#carousel_wrapper div.carousel-item:last');
        var newElement = last;
        last.remove();
        $('.prev').after(newElement);
        $('#carousel_wrapper').css('marginLeft',initialWidth);
    });
}

$('.next').click(function (){
    nextPage();
});

$('.prev').click(function (){
    prevPage();
});

var intervalo = window.setInterval(nextPage, 5000);
$('#carousel_wrapper').mouseover(function (){
    window.clearInterval(intervalo);
});

$('#carousel_wrapper').mouseout(function (){
    intervalo = window.setInterval(nextPage, 5000);
});