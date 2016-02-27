$(function(){

    /* hide blocks */
    $('div.d, div.f').click(function (e) {
        if (e.target !== this) return;
        $(this).toggleClass('hide');
        e.preventDefault();
        e.stopPropagation();
    });

    /* collapse parameters */
    $('span.params, span.return').click(function (e) {
        if (e.target !== this) return;
        $(this).toggleClass('short');
        e.preventDefault();
        e.stopPropagation();
    });

    /* hide internal funcs */
    $('#internal').change(function(){
        $('div.i').toggle();
    });

    /* hide functions */
    $('span.name').click(function(e){
        if (e.target !== this) return;

        var $fn = $(this);
        var name = $(this).text();
        $("span.name:contains('"+name+"')").closest('div.f').toggleClass('hide');

        e.preventDefault();
        e.stopPropagation();
    });

    /* mark important */
    $('span.time').click(function(e){
        if (e.target !== this) return;

        $(this).closest('div.f').toggleClass('mark');

        e.preventDefault();
        e.stopPropagation();
    });

    /* hide internal funcs */
    $('#marked').change(function(){
        $('div.f').toggle();
        $('div.f.mark').show();
    });
});