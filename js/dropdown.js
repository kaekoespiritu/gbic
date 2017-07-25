$(function(){
    $('.dropdown').hover(function() {
        $(this).addClass('open');
        // if this class is open... ???
    },
    function() {
        $(this).removeClass('open');
    });
});